<?php
//Wiki-列表
namespace App\Http\Controllers\Wiki;

use App\Models\Wiki;

class WikiData extends WikiBase
{
    public function index()
    {
        $id = isset($this->request['id']) ? intval($this->request['id']) : 0;
        if (!$id) {
            return ['code'=>-1,'msg'=>'缺少ID'];
        }

        //获取Wiki具体信息
        $WikiMod = new Wiki();
        $data = $WikiMod->getOne(['fields'=>['project','modular','name','url','method','request','response'],'where'=>['id'=>$id,'status'=>1]]);
        if (!$data) {
            return ['code'=>-2,'msg'=>'没有找到该Wiki或者该Wiki已经被删除'];
        }

        $data = ['data' => $data];
        return view('wiki.data', $data);
    }

}
