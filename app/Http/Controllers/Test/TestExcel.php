<?php
//Excel测试
namespace App\Http\Controllers\Test;

use App\Library\PHPExcel;

class TestExcel extends TestBase
{
    //导出Excel
    public function export()
    {
        $lists = [
            [
                'user_id' => 2157,
                'nickname' => '弹✨🌟弹✨🌟弹✨🌟',
            ],
            [
                'user_id' => 2166,
                'nickname' => '鑫鑫xinXIN',
            ]
        ];
        try{
            PHPExcel::download($lists,'excel导出测试.xls');
            return ['code'=>0,'msg'=>'导出成功'];
        } catch (\Exception $e) {
            return ['code'=>-2,'msg'=>'excel导出意外失败，请重试','exception'=>$e->getMessage()];
        }
    }
    //导入Excel
    public function import()
    {
        if (!isset($_FILES['uploadFile']) || !$_FILES['uploadFile']) {
            return ['code'=>-1,'msg'=>'没有接收到文件'];
        }
        $file = $_FILES['uploadFile'];
        $data = PHPExcel::read($file['tmp_name']);
        return $data;
    }
    
}
