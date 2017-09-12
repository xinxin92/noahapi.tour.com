<?php
//文章-删除
namespace App\Http\Controllers\OpCommon;

use App\Library\File;

class OpCommonUploadImg extends OpCommonBase
{
    public function index()
    {
        if (!isset($_FILES['uploadFile'])) {
            return ['code'=>-1,'msg'=>'没有获取到uploadFile'];
        }

        $params['size'] = 10240000;  //10M大小
        $params['type'] = ['jpg','png','gif','bmp'];

        set_time_limit(0);
        ini_set('memory_limit', '1024M');
        $res = File::uploadImgBuf($_FILES['uploadFile'], $params);

        return $res;
    }

}
