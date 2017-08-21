<?php
//文章-编辑提交
namespace App\Http\Controllers\Article;

use App\Library\Common;
use App\Models\Article\Article;
use App\Models\Article\ArticleContent;
use Illuminate\Support\Facades\DB;

class ArticleEditCheck extends ArticleBase
{
    public function index()
    {
        //参数校验及获取
        $resCheckParams = $this->checkParams($this->request);
        if ($resCheckParams['code'] < 0) {
            return $resCheckParams;
        }
        $id = intval($this->request['id']);
        $timeNow = date('Y-m-d H:i:s');
        $article = [
            'title' => trim($this->request['title']),
            'introduction' => trim($this->request['introduction']),
            'type' => intval($this->request['type']),
            'pic_url' => trim($this->request['pic_url']),
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
                        'master_id' => $id,
                        'created_at' => $timeNow,
                        'updated_at' => $timeNow,
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
                            'master_id' => $id,
                            'created_at' => $timeNow,
                            'updated_at' => $timeNow,
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

        //开启事务，进行修改
        $ArticleMod = new Article();
        $ArticleContentMod = new ArticleContent();
        DB::beginTransaction();
        try{
            $ArticleMod->updateBy($article,['id'=>$id]);
            $ArticleContentMod->updateBy(['status'=>-1],['master_id'=>$id]);
            $ArticleContentMod->insert($content);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return ['code'=>-3,'msg'=>'修改意外失败，请重试','exception'=>$e->getMessage()];
        }
        return ['code'=>1,'msg'=>'修改成功'];
    }

    //参数校验
    private function checkParams($request = []) {
        if (!isset($request['id']) || !intval($request['id'])) {
            return ['code'=>-1,'msg'=>'没有获取到文章ID'];
        }
        if (!isset($request['title']) || !trim($request['title'])) {
            return ['code'=>-1,'msg'=>'请输入标题'];
        }
        if (!isset($request['introduction']) || !trim($request['introduction'])) {
            return ['code'=>-1,'msg'=>'请输入简介'];
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
