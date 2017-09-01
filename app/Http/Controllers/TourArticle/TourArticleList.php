<?php
//文章-列表
namespace App\Http\Controllers\TourArticle;

use App\Library\Common;
use App\Models\TourArticle\TourArticle;

class TourArticleList extends TourArticleBase
{
    public function index()
    {
        //获取基本信息
        $pageMsg = Common::getPageMsg($this->request, 10);
        $attach = $this->attachParams($this->request);
        $fields = ['id','title','introduction','pic_url','price','start_time','end_time'];
        $lists = (new TourArticle())->getList(['fields'=>$fields,'where'=>$attach['where'],'orderBy'=>$attach['orderBy'],'skip'=>$pageMsg['skip'],'limit'=>$pageMsg['limit']]);
        //封面
        foreach ($lists as &$list) {
            $list['pic_url'] = config('upload.fileHost').$list['pic_url'];
        }
        $data = ['code'=>0,'msg'=>'成功','lists' => $lists];
        return $data;
    }

    //筛选条件处理
    private function attachParams($request=[]){
        $where = [];
        $orderBy = [];
        //文章标题
        if (isset($request['title']) && $title = trim($request['title'])) {
            $where['title like'] = $title;
        }
        $where['status'] = 1;
        $orderBy['start_time'] = 'desc';
        $orderBy['end_time'] = 'desc';
        $orderBy['id'] = 'desc';
        return ['where'=>$where,'orderBy'=>$orderBy];
    }

}
