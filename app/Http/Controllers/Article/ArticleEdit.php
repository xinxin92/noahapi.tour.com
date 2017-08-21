<?php
//文章-编辑展示
namespace App\Http\Controllers\Article;

use App\Models\Article\Article;
use App\Models\Article\ArticleContent;

class ArticleEdit extends ArticleBase
{
    public function index()
    {
        $id = isset($this->request['id']) ? intval($this->request['id']) : 0;
        if (!$id) {
            return ['code'=>-1,'缺少文章ID'];
        }
        //获取文章主要信息
        $articleInfo = (new Article())->getOne(['fields'=>['id','type','title','introduction','pic_url'],'where'=>['id'=>$id,'status !='=>-1]]);
        if (!$articleInfo) {
            return ['code'=>-1,'没有找到该文章或者该文章已经被删除'];
        }
        //文章封面
        $articleInfo['pic_url_show'] = config('upload.fileHost').$articleInfo['pic_url'];
        //获取文章子内容
        $articleInfo['content'] = [];
        $articleContent = (new ArticleContent())->getList(['fields'=>['type','content','pic_url'],'where'=>['master_id'=>$id,'status'=>1]]);
        if ($articleContent) {
            foreach ($articleContent as &$articleContentItem) {
                if ($articleContentItem['type'] == 2) {
                    $articleContentItem['pic_url_show'] = config('upload.fileHost').$articleContentItem['pic_url'];
                }
            }
        }
        $articleInfo['content'] = $articleContent;
        $data = [
            'opt' => 'edit',
            'articleInfo' => $articleInfo,
        ];
        return view('article.data', $data);
    }

}
