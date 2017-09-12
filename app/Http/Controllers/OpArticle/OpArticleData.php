<?php
//文章-查看
namespace App\Http\Controllers\OpArticle;

use App\Library\File;
use App\Models\TourArticle\TourArticle;
use App\Models\TourArticle\TourArticlePic;
use App\Models\TourArticle\TourArticleSchedule;
use App\Models\TourLeader\TourLeader;

class OpArticleData extends OpArticleBase
{
    public function index()
    {
        if (!isset($this->request['id']) || !($id = intval($this->request['id']))) {
            return ['code'=>-1,'msg'=>'参数有误，没有获取到有效的id'];
        }

        //文章信息
        $TourArticleMod = new TourArticle();
        $fields = ['title','start_time','end_time','least_num','most_num','join_num','price','pic_url','leader_id','introduction','information'];
        $article = $TourArticleMod->getOne(['fields'=>$fields,'where'=>['id'=>$id,'status <>'=>-1]]);
        if (!$article) {
            return ['code'=>-2,'msg'=>'没有查询到该文章或者该文章已经被删除'];
        }
        $article['pic_url'] = File::addImgHost($article['pic_url']);
        //领队信息
        $TourLeaderMod = new TourLeader();
        $leader = $TourLeaderMod->getOne(['fields'=>['name as leader_name'],'where'=>['id'=>$article['leader_id'],'status'=>1]]);
        $article['leader_name'] = isset($leader['leader_name']) ? $leader['leader_name'] : '';
        //行程安排
        $TourArticleScheduleMod = new TourArticleSchedule();
        $schedules = $TourArticleScheduleMod->getList(['fields'=>['id','date','content'],'where'=>['master_id'=>$id,'status'=>1]]);
        $article['schedules'] = $schedules;
        //文章图片
        $TourArticlePicMod = new TourArticlePic();
        $pics = $TourArticlePicMod->getList(['fields'=>['id','pic_url'],'where'=>['master_id'=>$id,'status'=>1]]);
        foreach ($pics as &$pic) {
            $pic['pic_url'] = File::addImgHost($pic['pic_url']);
        }
        $article['pics'] = $pics;

        $data = ['code'=>0,'msg'=>'成功','article'=>$article];
        return $data;
    }

}
