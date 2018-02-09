<?php

Route::group([
    'namespace' => 'Home',
], function () {
    //菜单
    Route::match(['get', 'post'],'/', 'HomeMenu@index');
});


//wiki
Route::group([
    'prefix' => 'wiki',
    'namespace' => 'Wiki',
//    'middleware' => ['checkLogin', 'recordLog'],
], function () {
    Route::match(['get', 'post'],'/', 'WikiList@index');
    Route::match(['get', 'post'],'/data', 'WikiData@index');
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


//API接口：
//文章模块
Route::group([
    'prefix' => 'tour/article',
    'namespace' => 'TourArticle',
], function () {
    //文章列表
    Route::match(['get', 'post'],'/list', 'TourArticleList@index');
});
//活动收集模块
Route::group([
    'prefix' => 'tour/collection',
    'namespace' => 'TourCollection',
], function () {
    //收集列表
    Route::match(['get', 'post'],'/add', 'TourCollectionAdd@index');
});


//OP接口：
//公共方法
Route::group([
    'prefix' => 'op/common',
    'namespace' => 'OpCommon',
], function () {
    //上传图片
    Route::match(['get', 'post'],'/uploadImg', 'OpCommonUploadImg@index');
});
//文章模块
Route::group([
    'prefix' => 'op/article',
    'namespace' => 'OpArticle',
], function () {
    //文章列表
    Route::match(['get', 'post'],'/list', 'OpArticleList@index');
    //文章查看
    Route::match(['get', 'post'],'/data', 'OpArticleData@index');
    //文章新增
    Route::match(['get', 'post'],'/add', 'OpArticleAdd@index');
    //文章编辑
    Route::match(['get', 'post'],'/edit', 'OpArticleEdit@index');
});
//领队模块
Route::group([
    'prefix' => 'op/leader',
    'namespace' => 'OpLeader',
], function () {
    //领队ID+姓名数组
    Route::match(['get', 'post'],'/group', 'OpLeaderGroup@index');
    //领队列表
    Route::match(['get', 'post'],'/list', 'OpLeaderList@index');
    //领队查看
    Route::match(['get', 'post'],'/data', 'OpLeaderData@index');
    //领队新增
    Route::match(['get', 'post'],'/add', 'OpLeaderAdd@index');
    //领队编辑
    Route::match(['get', 'post'],'/edit', 'OpLeaderEdit@index');
});
//活动收集模块
Route::group([
    'prefix' => 'op/collection',
    'namespace' => 'OpCollection',
], function () {
    //收集列表
    Route::match(['get', 'post'],'/list', 'OpCollectionList@index');
    //批量删除
    Route::match(['get', 'post'],'/del', 'OpCollectionDel@index');
    //生成excel
    Route::match(['get', 'post'],'/excel', 'OpCollectionExcel@index');
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
