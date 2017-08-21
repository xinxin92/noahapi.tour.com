<?php
//文章-删除
namespace App\Http\Controllers\Common;

use App\Library\Upload;

class CommonUploadImg extends CommonBase
{
    public function index()
    {
        //文件
        if (!isset($_FILES['uploadFile'])) {
            $res = ['code'=>-1,'msg'=>'没有获取到uploadFile'];
            return $this->getCallBackParams($res);
        }
        //回调函数名
        if (!isset($this->request['callBackName'])) {
            $res = ['code'=>-1,'msg'=>'没有获取到callBackName'];
            return $this->getCallBackParams($res);
        }
        //回调函数操作的元素ID值
        if (!isset($this->request['fileSrcId'])) {
            $res = ['code'=>-1,'msg'=>'没有获取到fileSrcId'];
            return $this->getCallBackParams($res);
        }
        //回调函数操作的元素ID值
        if (!isset($this->request['fileNameId'])) {
            $res = ['code'=>-1,'msg'=>'没有获取到fileNameId'];
            return $this->getCallBackParams($res);
        }
        
        set_time_limit(0);
        ini_set('memory_limit', '1024M');
        $params['size'] = 1024000;
        $params['type'] = ['jpg','png','gif','bmp'];
        $res = Upload::uploadFile($_FILES['uploadFile'], $params);
        return $this->getCallBackParams($res);
    }

    //构造回调参数
    private function getCallBackParams($res) {
        $callBackParmas = $res['code'].",'".$res['msg']."'";
        if ($res['code'] == 1) {
            $callBackParmas = $callBackParmas.",'".$res['file_src']."','".$res['file_name']."','".$this->request['fileSrcId']."','".$this->request['fileNameId']."'";
        }
        return "<script>parent.".$this->request['callBackName']."(".$callBackParmas.")</script>";
    }

}
