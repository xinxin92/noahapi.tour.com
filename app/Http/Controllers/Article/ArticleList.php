<?php
//文章-列表
namespace App\Http\Controllers\Article;

use App\Library\Common;
use App\Models\Article\Article;

class ArticleList extends ArticleBase
{
    public function index()
    {
        //获取基本信息
        $attach = $this->attachParams($this->request);
        $params = $attach['params'];
        $where = $attach['where'];
        $orderBy = $attach['orderBy'];
        $fields = ['id','status','status as status_show','type','type as type_show','title','introduction','pic_url','created_at','updated_at'];
        $lists = (new Article())->getPageList(['fields'=>$fields,'where'=>$where,'orderBy'=>$orderBy]);
        //封面
        foreach ($lists as &$list) {
            $list['pic_url_show'] = config('upload.fileHost').$list['pic_url'];
        }
        $attach = [
            'title' => ['lengthLimit'=>30],
            'introduction' => ['lengthLimit'=>30],
            'status_show' => ['1'=>'未审核','2'=>'审核不通过','3'=>'审核通过'],
            'type_show' => ['1'=>'专业博文','2'=>'外部摘录','3'=>'心得随笔'],
        ];
        $lists = Common::formatList($lists,$attach);
        $data = ['lists' => $lists, 'params' => $params];
        
        return view('article.list', $data);
    }

    //筛选条件处理
    private function attachParams($request=[]){
        $where = [];
        $params = [];
        $orderBy = [];
        //发布时间
        if (isset($request['start_time']) && trim($request['start_time'])) {
            $start_time = trim($request['start_time']);
            $params['start_time'] = $start_time;
            $where['created_at >='] = $start_time.' 00:00:00';
        }
        if (isset($request['end_time']) && trim($request['end_time'])) {
            $end_time = trim($request['end_time']);
            $params['end_time'] = $end_time;
            $where['created_at <='] = $end_time.' 23:59:59';
        }
        //文章ID
        if (isset($request['id']) && intval($request['id'])) {
            $id = intval($request['id']);
            $params['id'] = $id;
            $where['id'] = $id;
        }
        //文章标题
        if (isset($request['title']) && trim($request['title'])) {
            $title = trim($request['title']);
            $params['title'] = $title;
            $where['title like'] = $title;
        }
        //类型
        if (isset($request['type']) && intval($request['type'])) {
            $type = intval($request['type']);
            $params['type'] = $type;
            $where['type'] = $type;
        }
        //状态
        if (isset($request['status']) && intval($request['status'])) {
            $status = intval($request['status']);
            $params['status'] = $status;
            $where['status'] = $status;
        }

        $where['status !='] = -1;
        $orderBy['id'] = 'desc';
        return ['params'=>$params,'where'=>$where,'orderBy'=>$orderBy];
    }

}
