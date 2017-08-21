<?php
//登录用户的权限树
namespace App\Http\Controllers\Home;

use App\Models\Admin\OpAction;
use App\Models\Admin\OpRole;
use App\Models\Admin\OpRoleAction;
use App\Models\Admin\OpRoleMaster;

class HomeMenu extends HomeBase
{
    public function index()
    {
        $actionTree = [];
        $roles = (new OpRoleMaster())->getList(['fields'=>['role_id'],'where'=>['master_id'=>$this->master['master_id']]]);
        if ($roles) {
            $roleIds = array_column($roles,'role_id');
            $usableRoles = (new OpRole())->getList(['fields'=>['role_id'],'where'=>['in'=>['role_id'=>$roleIds],'status'=>1]]);
            if ($usableRoles) {
                $usableRoleIds = array_column($usableRoles,'role_id');
                $actions = (new OpRoleAction())->getList(['fields'=>['action_id'],'where'=>['in'=>['role_id'=>$usableRoleIds]]]);
                if ($actions) {
                    $actionIds = array_column($actions,'action_id');
                    $usableActions = (new OpAction())->getList(['fields'=>['action_id','action_name','parent_actionid','type','icon','controller','action','url'],'where'=>['in'=>['action_id'=>$actionIds],'status'=>1],['rank'=>'asc']]);
                    if ($usableActions) {
                        $actionTree = $this->getRoleActionTree($usableActions,1);
                    }
                }
            }
        }
        $data = ['master'=>$this->master,'menus'=>$actionTree];
        return view('home.menu',$data);
    }

    //获取角色权限树
    private function getRoleActionTree($actions,$pid) {
        $actionTree = [];
        foreach ($actions as $action) {
            if ($action['parent_actionid'] == $pid) {
                //递归获取该权限的子权限
                $action['children'] = $this->getRoleActionTree($actions,$action['action_id']);
                $actionTree[] = $action;
            }
        }
        return $actionTree;
    }
    
}
