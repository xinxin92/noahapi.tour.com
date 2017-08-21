<?php
//数据库连接测试
namespace App\Http\Controllers\Test;

use App\Models\Article\Article;

class TestDatabase extends TestBase
{
    protected function index()
    {
        $ArticleMod = new Article();
        $list = $ArticleMod->select('*')->get()->toArray();
        return $list;
    }
}
