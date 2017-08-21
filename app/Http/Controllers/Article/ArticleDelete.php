<?php
//文章-删除
namespace App\Http\Controllers\Article;

use App\Models\Article\Article;

class ArticleDelete extends ArticleBase
{
    public function index()
    {
        //获取基本信息
        if (!isset($this->request['id']) || !intval($this->request['id'])) {
            return ['code'=>-1,'msg'=>'没有获取到有效的文章ID'];
        }
        $id = intval($this->request['id']);

        $ArticleMod = new Article();
        $article = $ArticleMod->getOne(['fields'=>['id'],'where'=>['id'=>$id,'status !='=>-1]]);
        if (!$article){
            return ['code'=>-2,'msg'=>'没有查询到该文章或者该文章已经被删除'];
        }

        $ArticleMod->updateBy(['status'=>-1],['id'=>$id]);
        return ['code'=>1,'msg'=>'删除成功'];
    }

}
