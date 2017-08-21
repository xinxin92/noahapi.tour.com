<?php
//传类
namespace App\Library;

class Upload {
    //图片上传
    public static function uploadFile($file, $params=[]){
        $file_content = @file_get_contents($file['tmp_name']);
        //校验文件是否有效
        if (!$file_content) {
            return ['code' => -10, 'msg' => '上传错误，文件不存在，请核查'];
        }
        //限定文件类型
        $file_type = Common::getFileType($file["name"]);
        if (isset($params['type']) && $params['type']) {
            if (!in_array($file_type,$params['type'])) {
                $str_types = implode(", ", $params['type']);
                return ['code' => -11, 'msg' => '文件上传失败，只能上传以下类型的文件：'.$str_types];
            }
        }
        //限定文件大小
        if (isset($params['size']) && $params['size']) {
            if ($file["size"] > $params['size']) {
                return ['code' => -12, 'msg' => '文件上传失败，文件大小超过限制（'.($params['size']/1024).'KB）'];
            }
        }
        
        //文件改名及移动
        $new_name = Common::getUniqueValue([],false).".".$file_type;
        $res = move_uploaded_file($file['tmp_name'], config('upload.fileDir').$new_name);
        if (!$res) {
            return ['code' => -13, 'msg' => '文件保存失败，请重试'];
        }
        //上传成功，返回文件信息
        $data = [
            'code' => 1,
            'msg' => '上传成功',
            'file_src' => config('upload.fileHost').$new_name,
            'file_name' => $new_name,
        ];
        return $data;
    }

}