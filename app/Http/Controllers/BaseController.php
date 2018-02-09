<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Request;

class BaseController extends Controller
{
    protected $request;
    protected $master;

    public function __construct(){
        $this->request = Request::capture();
        if($this->request->method() == 'POST') {
            $data = file_get_contents('php://input');
            if (!empty($data)) {
                $rawJson = json_decode($data, true);
                if (json_last_error() != JSON_ERROR_NONE) {
                    $rawJson = [];
                }
                $rawJson['raw'] = $data;
                $this->request->merge($rawJson);
            }
        }
        $this->master = [
            'master_id' => 1,
            'master_name' => 'yuxinwei',
            'real_name' => '尉鑫伟',
        ];
    }

    //校验参数
    public static function checkParams(&$params, $rules)
    {
        foreach ($rules as $param => $rule) {
            $defaultMsg = isset($rule['msg'][0]) ? $rule['msg'][0] : '参数'.$param.'异常！';
            //参数存在性校验
            if (!isset($params[$param])) {
                return $defaultMsg;
            }
            //参数预处理
            if (!empty($rule['do'])) {
                foreach ($rule['do'] as $do) {
                    $params[$param] = $do($params[$param]);
                }
            }
            //参数正式校验并返回对应错误提示
            if (!empty($rule['check'])) {
                $index = 0;
                foreach ($rule['check'] as $check) {
                    $check = "if (". str_replace('$param','$params[$param]',$check).") { return true; } else { return false; }";
                    if (!eval($check)) {
                        $msg = isset($rule['msg'][$index]) ? $rule['msg'][$index] : $defaultMsg;
                        return $msg;
                    }
                    $index++;
                }
            }
        }
        return false;
    }
    
}
