<?php
//文章-删除
namespace App\Http\Controllers\Article;

use App\Models\Article\Article;

class ArticleAudit extends ArticleBase
{
    public function index()
    {
        //获取基本信息
        if (!isset($this->request['id']) || !intval($this->request['id'])) {
            return ['code'=>-1,'msg'=>'没有获取到有效的文章ID'];
        }
        if (!isset($this->request['opt']) || !intval($this->request['opt'])) {
            return ['code'=>-1,'msg'=>'没有获取到有效的操作类型opt'];
        }
        $id = intval($this->request['id']);
        $opt = intval($this->request['opt']);

        $ArticleMod = new Article();
        $article = $ArticleMod->getOne(['fields'=>['id'],'where'=>['id'=>$id,'status !='=>-1]]);
        if (!$article){
            return ['code'=>-2,'msg'=>'没有查询到该文章或者该文章已经被删除'];
        }

        $updateArticle = [];
        if ($opt == 1) {  //审核通过
            $updateArticle['status'] = 3;
        } else if ($opt == 2) {  //审核不通过
            $updateArticle['status'] = 2;
        }
        $ArticleMod->updateBy($updateArticle,['id'=>$id]);
        return ['code'=>1,'msg'=>'操作成功'];
    }

}
