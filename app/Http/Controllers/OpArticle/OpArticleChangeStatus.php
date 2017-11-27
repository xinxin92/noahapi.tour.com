<?php
//文章-修改状态
namespace App\Http\Controllers\OpArticle;

use App\Library\File;
use App\Models\TourArticle\TourArticle;
use App\Models\TourArticle\TourArticlePic;
use App\Models\TourArticle\TourArticleSchedule;
use App\Models\TourLeader\TourLeader;

class OpArticleChangeStatus extends OpArticleBase
{
    public function index()
    {
        if (!isset($this->request['id']) || !($id = intval($this->request['id']))) {
            return ['code'=>-1,'msg'=>'参数有误，没有获取到有效的id'];
        }
        if (!isset($this->request['status']) || !in_array($status = $this->request['status'],[-1,1,3])) {
            return ['code'=>-1,'msg'=>'参数有误，没有获取到有效的status'];
        }

        //文章存在性校验
        $TourArticleMod = new TourArticle();
        $article = $TourArticleMod->getOne(['fields'=>['id'],'where'=>['id'=>$id,'status <>'=>-1]]);
        if (!$article) {
            return ['code'=>-2,'msg'=>'没有查询到该文章或者该文章已经被删除'];
        }

        //更改状态
        $TourArticleMod->updateBy(['status'=>$status],['id'=>$id]);
        return ['code'=>0,'msg'=>'成功'];
    }

}
