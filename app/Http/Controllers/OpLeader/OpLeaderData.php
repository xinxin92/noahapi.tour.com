<?php
//领队-查看
namespace App\Http\Controllers\OpLeader;

use App\Library\File;
use App\Models\TourArticle\TourArticle;
use App\Models\TourArticle\TourArticlePic;
use App\Models\TourArticle\TourArticleSchedule;
use App\Models\TourLeader\TourLeader;

class OpLeaderData extends OpLeaderBase
{
    public function index()
    {
        if (!isset($this->request['id']) || !($id = intval($this->request['id']))) {
            return ['code'=>-1,'msg'=>'参数有误，没有获取到有效的id'];
        }

        //领队信息
        $TourLeaderMod = new TourLeader();
        $fields = ['name','gender','pic_url','mobile','weixin','times','skill','introduction'];
        $leader = $TourLeaderMod->getOne(['fields'=>$fields,'where'=>['id'=>$id,'status'=>1]]);
        if (!$leader) {
            return ['code'=>-2,'msg'=>'没有查询到该领队信息或者该领队已经被删除'];
        }
        $leader['pic_url'] = File::addImgHost($leader['pic_url']);

        $data = ['code'=>0,'msg'=>'成功','leader'=>$leader];
        return $data;
    }

}
