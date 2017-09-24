<?php
//领队-列表
namespace App\Http\Controllers\OpLeader;

use App\Library\Common;
use App\Models\TourArticle\TourArticle;
use App\Models\TourLeader\TourLeader;

class OpLeaderList extends OpLeaderBase
{
    public function index()
    {
        //获取基本信息
        $pageMsg = Common::getPageMsg($this->request, 20);
        $attach = $this->attachParams($this->request);
        $fields = [
            'tour_leader.id',
            'tour_leader.name',
            'tour_leader.gender',
            'tour_leader.mobile',
            'tour_leader.weixin',
            'tour_leader.times',
            'tour_leader.skill',
        ];
        $TourLeaderMod = new TourLeader();
        $lists = $TourLeaderMod->getList(['fields'=>$fields,'where'=>$attach['where'],'orderBy'=>$attach['orderBy'],'skip'=>$pageMsg['skip'],'limit'=>$pageMsg['limit']]);
        $count = $TourLeaderMod->countBy(['where'=>$attach['where']]);
        $data = ['code'=>0,'msg'=>'成功','count'=>$count,'lists' => $lists];
        return $data;
    }

    //筛选条件处理
    private function attachParams($request=[]){
        $where = [];
        $orderBy = [];
        //ID
        if (isset($request['id']) && $id = intval($request['id'])) {
            $where['tour_leader.id'] = $id;
        }
        //姓名
        if (isset($request['name']) && $name = trim($request['name'])) {
            $where['tour_leader.name like'] = $name;
        }
        //性别
        if (isset($request['gender']) && in_array($request['gender'],[1,2])) {
            $where['tour_leader.gender'] = $request['gender'];
        }
        //电话
        if (isset($request['mobile']) && $mobile = trim($request['mobile'])) {
            $where['tour_leader.mobile'] = $mobile;
        }

        $where['tour_leader.status'] = 1;
        $orderBy['tour_leader.updated_at'] = 'desc';
        return ['where'=>$where,'orderBy'=>$orderBy];
    }

}
