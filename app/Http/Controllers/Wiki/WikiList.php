<?php
//Wiki-列表
namespace App\Http\Controllers\Wiki;

use App\Models\Wiki;

class WikiList extends WikiBase
{
    public function index()
    {
        //获取基本信息
        $attach = $this->attachParams($this->request);
        $params = $attach['params'];
        $where = $attach['where'];
        $orderBy = $attach['orderBy'];

        $WikiMod = new Wiki();
        $fields = ['id','project','modular','name','url','method','request','response','created_at','updated_at'];
        $lists = $WikiMod->getPageList(['fields'=>$fields,'where'=>$where,'orderBy'=>$orderBy]);

        $data = ['lists' => $lists, 'params' => $params];
        return view('wiki.list', $data);
    }

    //筛选条件处理
    private function attachParams($request=[]){
        $where = [];
        $params = [];
        $orderBy = [];
        //文章ID
        if (isset($request['id']) && $id = intval($request['id'])) {
            $params['id'] = $id;
            $where['id'] = $id;
        }
        //项目名称
        if (isset($request['project']) && $project = trim($request['project'])) {
            $params['project'] = $project;
            $where['project like'] = $project;
        }
        //模块名称
        if (isset($request['modular']) && $modular = trim($request['modular'])) {
            $params['modular'] = $modular;
            $where['modular like'] = $modular;
        }
        //接口名称
        if (isset($request['name']) && $name = trim($request['name'])) {
            $params['name'] = $name;
            $where['name like'] = $name;
        }
        //接口地址
        if (isset($request['url']) && $url = trim($request['url'])) {
            $params['url'] = $url;
            $where['url like'] = $url;
        }
        
        //排序类型
        $orderBy = ['project'=>'asc','modular'=>'asc'];  //默认按项目模块排序
        if (isset($request['order_type']) && $order_type = intval($request['order_type'])) {
            $params['order_type'] = $order_type;
            if ($order_type == 1) {  //按创建时间倒序
                $orderBy = ['created_at'=>'desc'];
            }
        }
        $where['status'] = 1;
        return ['params'=>$params,'where'=>$where,'orderBy'=>$orderBy];
    }

}
