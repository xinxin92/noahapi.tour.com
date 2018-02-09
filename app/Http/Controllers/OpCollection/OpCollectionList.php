<?php
//活动收集-列表
namespace App\Http\Controllers\OpCollection;

use App\Http\Controllers\BaseController;
use App\Library\Common;
use App\Models\Collection\TourCollection;

class OpCollectionList extends BaseController
{
    public function index()
    {
        //获取基本信息
        $this->request['time_now'] = date('Y-m-d');
        $pageMsg = Common::getPageMsg($this->request, 50);
        $attach = $this->attachParams($this->request);
        $fields = [
            'tour_collection.id',
            'tour_collection.outlet_name',
            'tour_collection.tour_time',
            'tour_collection.tourist_num',
            'tour_collection.guide_num',
            'tour_collection.guide_name',
            'tour_collection.guide_name_py',
            'tour_collection.guide_gender',
            'tour_collection.guide_mobile',
            'tour_collection.guide_weixin',
            'tour_collection.guide_email',
            'tour_collection.pay_method',
        ];
        $TourCollectionMod = new TourCollection();
        $lists = $TourCollectionMod->getList(['fields'=>$fields,'where'=>$attach['where'],'orderBy'=>$attach['orderBy'],'skip'=>$pageMsg['skip'],'limit'=>$pageMsg['limit']]);
        $count = $TourCollectionMod->countBy(['where'=>$attach['where']]);
        
        $data = ['code'=>0,'msg'=>'成功','count'=>$count,'lists' => $lists];
        return $data;
    }

    //筛选条件处理
    private function attachParams($request=[]){
        $where = [];
        $orderBy = [];
        //ID
        if (!empty($request['id'])) {
            $where['tour_collection.id'] = $request['id'];
        }
        //时间范围左
        if (!empty($request['tour_time_start'])) {
            $where['tour_collection.tour_time >='] = $request['tour_time_start'];
        }
        //时间范围右
        if (!empty($request['tour_time_end'])) {
            $where['tour_collection.tour_time <='] = $request['tour_time_end'];
        }
        //导游姓名中文
        if (!empty($request['guide_name'])) {
            $where['tour_collection.guide_name'] = $request['guide_name'];
        }
        //导游姓名拼音
        if (!empty($request['guide_name_py'])) {
            $where['tour_collection.guide_name_py'] = $request['guide_name_py'];
        }
        //导游电话
        if (!empty($request['guide_mobile'])) {
            $where['tour_collection.guide_mobile'] = $request['guide_mobile'];
        }

        $where['tour_collection.status'] = 1;
        $orderBy['tour_collection.tour_time'] = 'desc';
        $orderBy['tour_collection.id'] = 'desc';
        return ['where'=>$where,'orderBy'=>$orderBy];
    }

}
