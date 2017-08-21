<?php
//登录用户的详细信息
namespace App\Http\Controllers\Home;

use App\Models\Admin\OpMaster;
use App\Models\Admin\OpRole;
use App\Models\Admin\OpRoleMaster;

class HomeMaster extends HomeBase
{
    public function index()
    {
        $master = (new OpMaster())->getOne(['fields'=>['*'],'where'=>['master_id'=>$this->master['master_id']]]);
        $master_roles = [];
        $roles = (new OpRoleMaster())->getList(['fields'=>['role_id'],'where'=>['master_id'=>$this->master['master_id']]]);
        if ($roles) {
            $roleIds = array_column($roles, 'role_id');
            $usableRoles = (new OpRole())->getList(['fields' => ['role_name'], 'where' => ['in' => ['role_id' => $roleIds], 'status' => 1]]);
            if ($usableRoles) {
                $master_roles = array_column($usableRoles,'role_name');
            }
        }
        $data = ['master'=>$master,'master_roles'=>$master_roles];
        return view('home.master',$data);
    }

}
