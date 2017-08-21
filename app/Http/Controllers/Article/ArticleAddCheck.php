<?php
//文章-新增提交
namespace App\Http\Controllers\Article;

use App\Library\Common;
use App\Models\Article\Article;
use App\Models\Article\ArticleContent;
use Illuminate\Support\Facades\DB;

class ArticleAddCheck extends ArticleBase
{
    public function index()
    {
        //参数校验及获取
        $resCheckParams = $this->checkParams($this->request);
        if ($resCheckParams['code'] < 0) {
            return $resCheckParams;
        }
        $timeNow = date('Y-m-d H:i:s');
        $article = [
            'title' => trim($this->request['title']),
            'introduction' => trim($this->request['introduction']),
            'type' => intval($this->request['type']),
            'pic_url' => trim($this->request['pic_url']),
            'created_at' => $timeNow,
            'updated_at' => $timeNow,
        ];
        //构造文章内容并校验
        $content = [];
        $index = 0;
        $has_real_content = 0;
        try{
            $contentType = $this->request['contentType'];
            $contentText = $this->request['content_text'];
            $contentPic = $this->request['content_pic'];
            foreach ($contentType as $contentTypeItem) {
                $contentItem = [];
                $type = intval($contentTypeItem);
                if ($type == 1 or $type == 3) {
                    $contentTextItem = Common::delStrBothBlank($contentText[$index]);
                    $contentItem = [
                        'type' => $type,
                        'rank' => $index,
                        'pic_url' => '',
                        'content' => $contentTextItem,
                    ];
                    if ($contentTextItem) {
                        $has_real_content = 1;
                    }
                } elseif ($type == 2) {
                    if ($contentPic[$index]) {
                        $contentItem = [
                            'type' => $type,
                            'rank' => $index,
                            'pic_url' => $contentPic[$index],
                            'content' => '',
                        ];
                        $has_real_content = 1;
                    }
                }
                if ($contentItem) {
                    $content[] = $contentItem;
                    $index++;
                }
            }
        } catch (\Exception $e) {
            return ['code'=>-2,'msg'=>'缺少文章内容或者文章内容不完整','exception_msg'=>$e->getMessage(),'exception_line'=>$e->getLine()];
        }
        if (!$has_real_content) {
            return ['code'=>-2,'msg'=>'缺少文章内容或者文章内容不完整'];
        }

        //开启事务，进行新增
        $ArticleMod = new Article();
        $ArticleContentMod = new ArticleContent();
        DB::beginTransaction();
        try{
            $id = $ArticleMod->insertGetId($article);
            foreach ($content as &$contentOne) {
                $contentOne['master_id'] = $id;
                $contentOne['created_at'] = $timeNow;
                $contentOne['updated_at'] = $timeNow;
            }
            $ArticleContentMod->insert($content);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return ['code'=>-3,'msg'=>'新增意外失败，请重试','exception'=>$e->getMessage()];
        }
        return ['code'=>1,'msg'=>'新增成功'];
    }

    //参数校验
    private function checkParams($request = []) {
        if (!isset($request['title']) || !trim($request['title'])) {
            return ['code'=>-1,'msg'=>'请输入文章标题'];
        }
        if (!isset($request['introduction']) || !trim($request['introduction'])) {
            return ['code'=>-1,'msg'=>'请输入文章简介'];
        }
        if (!isset($request['type']) || !intval($request['type'])) {
            return ['code'=>-1,'msg'=>'请选择文章类型'];
        }
        if (!isset($request['pic_url']) || !trim($request['pic_url'])) {
            return ['code'=>-1,'msg'=>'请上传封面'];
        }
        return ['code'=>1,'msg'=>'校验成功'];
    }

}
