<?php
//文件相关的工具类
namespace App\Library;

class File
{
    //将图片添加host或者去掉host（http://imgup.xin.com是图片服务器地址）
    public static function getImgUrl ($file='',$host = '') {
        if (!$file) {
            return '';
        }
        if (preg_match('/^http.*?com/', $file)) {
            $file = preg_replace('/^http.*?com/', $host, $file);
        } else {
            $file = $host.$file;
        }
        return $file;
    }

    /*
     * 获取文件后缀名（处理了附带随机参数的特殊情况）
     * @param $file 文件名
     * @return 后缀名
     * */
    public static function getFileType($file){
        $params_str = strrchr($file, '?');
        if ($params_str) {
            $file = str_replace($params_str,'',$file);
        }
        $file_type = strtolower(substr(strrchr($file, '.'), 1));
        return $file_type;
    }

    /*
     * URL图片上传
     * params:  $file $_FILES['uploadFile']
     * params:  $params array 可选参数，如：['type','size']  定制条件
     * return:  ['code','message'] or ['code','message', 'file_src', 'file_name']
     */
    public static function uploadImgBuf ($file='', $params=[]) {
        $file_type = self::getFileType($file["name"]);
        //限定文件类型
        if (isset($params['type']) && $params['type']) {
            if (!in_array($file_type,$params['type'])) {
                $str_types = implode(", ", $params['type']);
                return ['code' => -11, 'msg' => '文件上传失败，只能上传以下类型的文件：'.$str_types];
            }
        }
        //限定文件大小
        if (isset($params['size']) && $params['size']) {
            $file_size = @strlen(file_get_contents($file['tmp_name']));
            if (!$file_size) {
                return ['code' => -10, 'msg' => '上传错误，文件不存在，请核查'];
            }
            if ($file_size > $params['size']) {
                return ['code' => -12, 'msg' => '文件上传失败，文件大小超过限制（'.($params['size']/1024).'KB）'];
            }
        }
        //文件改名及移动
        try {
            $new_name = Common::getUniqueValue([],false).".".$file_type;
            $res = move_uploaded_file($file['tmp_name'], config('upload.fileDir').$new_name);
        } catch (\Exception $e) {
            return ['code' => -100, 'msg' => $e->getMessage()];
        }
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