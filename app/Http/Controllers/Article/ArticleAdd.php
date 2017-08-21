<?php
//文章-新增展示
namespace App\Http\Controllers\Article;

class ArticleAdd extends ArticleBase
{
    public function index()
    {
        $data = [
            'opt' => 'add',
        ];
        return view('article.data', $data);
    }

}
