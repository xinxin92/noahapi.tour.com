<?php
//文章-编辑
namespace App\Http\Controllers\OpCollection;

use App\Http\Controllers\BaseController;
use App\Models\Collection\TourCollection;

class OpCollectionDel extends BaseController
{
    public function index()
    {
        if (empty($this->request['ids'])) {
            return ['code'=>-1, 'msg'=>'参数有误，缺少ids'];
        }

        //开始删除
        $TourCollectionMod = new TourCollection();
        $res = $TourCollectionMod->updateBy(['status'=>-1],['in'=>['id'=>$this->request['ids']]]);
        if ($res) {
            return ['code'=>0, 'msg'=>'删除成功'];
        } else {
            return ['code'=>-100, 'msg'=>'没有需要删除的记录'];
        }
    }

}
