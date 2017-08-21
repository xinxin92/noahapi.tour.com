<?php

Route::group([
    'namespace' => 'Home',
], function () {
    //菜单
    Route::match(['get', 'post'],'/', 'HomeMenu@index');
});

//测试
Route::group([
    'prefix' => 'test',
    'namespace' => 'Test',
//    'middleware' => ['checkLogin', 'recordLog'],
], function () {
    Route::match(['get', 'post'],'/database/index', 'TestDatabase@index');
    Route::match(['get', 'post'],'/alipay/pay', 'TestAlipay@pay');
    Route::match(['get', 'post'],'/alipay/query', 'TestAlipay@query');
    Route::match(['get', 'post'],'/excel/export', 'TestExcel@export');
    Route::match(['get', 'post'],'/excel/import', 'TestExcel@import');
    Route::match(['get', 'post'],'/view', 'TestView@index');
//    Route::post('/db', 'TestDb@index');
//    Route::match(['get','post'],'/db', 'TestDb@index');
});

//公共方法
Route::group([
    'prefix' => 'common',
    'namespace' => 'Common',
], function () {
    //上传文件
    Route::match(['get', 'post'],'/uploadImg', 'CommonUploadImg@index');
});

//基础模块
Route::group([
    'prefix' => 'home',
    'namespace' => 'Home',
], function () {
    //菜单
    Route::match(['get', 'post'],'/menu', 'HomeMenu@index');
    //登录用户的信息
    Route::match(['get', 'post'],'/master', 'HomeMaster@index');
});

//文章模块
Route::group([
    'prefix' => 'article',
    'namespace' => 'Article',
], function () {
    //文章列表
    Route::match(['get', 'post'],'/list', 'ArticleList@index');
    Route::match(['get', 'post'],'/delete', 'ArticleDelete@index');
    Route::match(['get', 'post'],'/audit', 'ArticleAudit@index');
    Route::match(['get', 'post'],'/add', 'ArticleAdd@index');
    Route::match(['get', 'post'],'/addCheck', 'ArticleAddCheck@index');
    Route::match(['get', 'post'],'/edit', 'ArticleEdit@index');
    Route::match(['get', 'post'],'/editCheck', 'ArticleEditCheck@index');
});


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    //
});
