<?php
//前端biew测试
namespace App\Http\Controllers\Test;

class TestView extends TestBase
{
    public function index()
    {
        $data = [];
        return view('test.test', $data);
    }
}
