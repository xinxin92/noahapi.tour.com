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
}
