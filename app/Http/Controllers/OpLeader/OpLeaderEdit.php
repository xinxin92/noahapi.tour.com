<?php
//领队-修改
namespace App\Http\Controllers\OpLeader;

use App\Library\File;
use App\Models\TourLeader\TourLeader;

class OpLeaderEdit extends OpLeaderBase
{
    public function index()
    {
        //参数校验及获取
        $this->request['time_request'] = date('Y-m-d H:i:s');
        $resDealParams = $this->dealParams($this->request);
        if ($resDealParams['code'] < 0) {
            return $resDealParams;
        }
        $id = $resDealParams['id'];
        $leader = $resDealParams['leader'];

        //存在性校验
        $TourLeaderMod = new TourLeader();
        $sourceLeader = $TourLeaderMod->getOne(['fields'=>['id'],'where'=>['id'=>$id,'status'=>1]]);
        if (!$sourceLeader) {
            return ['code'=>-2, 'msg'=>'该领队不存在或者已经被删除'];
        }

        //开始修改
        $res = $TourLeaderMod->updateBy($leader,['id'=>$id]);
        if ($res) {
            return ['code'=>0,'msg'=>'成功'];
        } else {
            return ['code'=>-100,'msg'=>'修改意外失败，请重试'];
        }
    }

    //筛选条件处理
    private function dealParams($request=[]){
        //ID
        if (!isset($this->request['id']) || !($id = intval($this->request['id']))) {
            return ['code'=>-1,'msg'=>'缺少领队id'];
        }
        //姓名
        if (isset($request['name']) && $name = trim($request['name'])) {
            $leader['name'] = $name;
        } else {
            return ['code'=>-1, 'msg'=>'请输入姓名'];
        }
        //性别
        if (isset($request['gender']) && in_array($request['gender'],[1,2])) {
            $leader['gender'] = $request['gender'];
        } else {
            return ['code'=>-1, 'msg'=>'请选择性别'];
        }
        //电话
        if (isset($request['mobile']) && $mobile = trim($request['mobile'])) {
            $leader['mobile'] = $mobile;
        } else {
            return ['code'=>-1, 'msg'=>'请输入电话'];
        }
        //微信
        if (isset($request['weixin']) && $weixin = trim($request['weixin'])) {
            $leader['weixin'] = $weixin;
        } else {
            return ['code'=>-1, 'msg'=>'请输入微信'];
        }
        //特长
        if (isset($request['skill']) && $skill = trim($request['skill'])) {
            $leader['skill'] = $skill;
        } else {
            return ['code'=>-1, 'msg'=>'请输入特长'];
        }
        //简介
        if (isset($request['introduction']) && $introduction = trim($request['introduction'])) {
            $leader['introduction'] = $introduction;
        } else {
            return ['code'=>-1, 'msg'=>'请输入简介'];
        }
        //头像
        if (isset($request['pic_url']) && $pic_url = trim($request['pic_url'])) {
            $leader['pic_url'] = File::delImgHost($pic_url);
        } else {
            return ['code'=>-1, 'msg'=>'请上传头像'];
        }
        $leader['updated_at'] = $request['time_request'];

        return ['code'=>1,'msg'=>'校验成功','leader'=>$leader,'id'=>$id];
    }

}
