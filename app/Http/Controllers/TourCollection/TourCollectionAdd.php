<?php
//旅游活动收集-新增
namespace App\Http\Controllers\TourCollection;

use App\Http\Controllers\BaseController;
use App\Models\Collection\TourCollection;

class TourCollectionAdd extends BaseController
{
    public function index()
    {
        //参数校验
        $params = $this->request->all();
        $rules = [
            'outlet_name' => ['do'=>['trim'], 'check'=>['$param'], 'msg'=>['请选择outlet名称！']],
            'date' => ['do'=>['trim'], 'check'=>['$param'], 'msg'=>['请输入日期！']],
            'time' => ['do'=>['trim'], 'check'=>['$param'], 'msg'=>['请输入时间！']],
            'tourist_num' => ['do'=>['trim'], 'check'=>['$param'], 'msg'=>['请输入客人人数！']],
            'guide_num' => ['do'=>['trim'], 'check'=>['$param'], 'msg'=>['请输入司机及导游人数！']],
            'guide_name' => ['do'=>['trim'], 'check'=>['$param'], 'msg'=>['请输入导游姓名中文！']],
            'guide_name_py' => ['do'=>['trim'], 'check'=>['$param'], 'msg'=>['请输入导游姓名拼音！']],
            'guide_gender' => ['do'=>['trim'], 'check'=>['$param'], 'msg'=>['请选择导游性别！']],
            'guide_mobile' => ['do'=>['trim'], 'check'=>['$param'], 'msg'=>['请输入导游电话！']],
            'guide_weixin' => ['do'=>['trim'], 'check'=>['$param'], 'msg'=>['请输入导游微信！']],
            'guide_email' => ['do'=>['trim'], 'check'=>['$param'], 'msg'=>['请输入导游email！']],
            'pay_method' => ['do'=>['trim'], 'msg'=>['参数有误，付款方式字段缺失！']],
        ];
        $paramsRes = self::checkParams($params,$rules);
        if ($paramsRes) {
            return ['code'=>-1,'msg'=>$paramsRes];
        }

        //开始新增
        $insertCollection = [
            'outlet_name' => $params['outlet_name'],
            'tour_time' => $params['date'].' '.$params['time'],
            'tourist_num' => $params['tourist_num'],
            'guide_num' => $params['guide_num'],
            'guide_name' => $params['guide_name'],
            'guide_name_py' => $params['guide_name_py'],
            'guide_gender' => $params['guide_gender'],
            'guide_mobile' => $params['guide_mobile'],
            'guide_weixin' => $params['guide_weixin'],
            'guide_email' => $params['guide_email'],
            'pay_method' => $params['pay_method'],
        ];
        $TourCollectionMod = new TourCollection();
        $res = $TourCollectionMod->insert($insertCollection);
        if ($res) {
            return ['code'=>0,'msg'=>'提交成功'];
        } else {
            return ['code'=>-100,'msg'=>'提交意外失败，请重试'];
        }
    }

}
