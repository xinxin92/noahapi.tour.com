<?php
//文章-编辑
namespace App\Http\Controllers\OpCollection;

use App\Http\Controllers\BaseController;
use App\Library\PHPExcel;
use App\Models\Collection\TourCollection;

class OpCollectionExcel extends BaseController
{
    public function index()
    {
        if (empty($this->request['id'])) {
            return ['code'=>-1, 'msg'=>'参数有误，缺少id'];
        }

        //存在性校验
        $TourCollectionMod = new TourCollection();
        $fields = ['id','outlet_name','tour_time','tourist_num','guide_num','guide_name', 'guide_name_py','guide_gender','guide_mobile','guide_weixin','guide_email','pay_method',];
        $collection = $TourCollectionMod->getOne(['fields'=>$fields,'where'=>['id'=>$this->request['id'],'status'=>1]]);
        if (!$collection) {
            return ['code'=>-2, 'msg'=>'没有查询到该记录或者该记录已经被删除!'];
        }

        //开始导出
        if ($collection['guide_gender'] == 1) {
            $collection['guide_gender'] = '男';
        } else if ($collection['guide_gender'] == 2) {
            $collection['guide_gender'] = '女';
        }
        $exportData = [
            ['ID', $collection['id']],
            ['outlet名称', $collection['outlet_name']],
            ['日期时间', $collection['tour_time']],
            ['客人人数', $collection['tourist_num']],
            ['司机人数+导游人数', $collection['guide_num']],
            ['导游姓名（中文）', $collection['guide_name']],
            ['导游姓名（拼音）', $collection['guide_name_py']],
            ['导游性别', $collection['guide_gender']],
            ['导游电话', $collection['guide_mobile']],
            ['导游微信', $collection['guide_weixin']],
            ['导游email', $collection['guide_email']],
            ['付款方式', $collection['pay_method']],
        ];
        try{
            PHPExcel::download($exportData, '活动收集'.'_'.$collection['id'].'_'.$collection['guide_name'].'.xls');
            return ['code'=>0,'msg'=>'导出成功'];
        } catch (\Exception $e) {
            return ['code'=>-2,'msg'=>'excel导出意外失败，请重试','exception'=>$e->getMessage()];
        }

    }

}
