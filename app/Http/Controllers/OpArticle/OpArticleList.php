<?php
//文章-列表
namespace App\Http\Controllers\OpArticle;

use App\Library\Common;
use App\Models\TourArticle\TourArticle;
use App\Models\TourLeader\TourLeader;

class OpArticleList extends OpArticleBase
{
    public function index()
    {
        //获取基本信息
        $pageMsg = Common::getPageMsg($this->request, 50);
        $attach = $this->attachParams($this->request);
        $fields = [
            'tour_article.id',
            'tour_article.title',
            'tour_article.start_time',
            'tour_article.end_time',
            'tour_article.least_num',
            'tour_article.most_num',
            'tour_article.join_num',
            'tour_leader.name as leader_name',
        ];
        $joins = [
            'tour_leader' => [['tour_article.leader_id','=','tour_leader.id']],
        ];
        $lists = (new TourArticle())->getList(['fields'=>$fields,'joins'=>$joins,'where'=>$attach['where'],'orderBy'=>$attach['orderBy'],'skip'=>$pageMsg['skip'],'limit'=>$pageMsg['limit']]);
        //封面
        foreach ($lists as &$list) {
            if ($list['join_num'] < $list['least_num']) {
                $list['join_status'] = '名额充足';
            } else if ($list['join_num'] < $list['most_num']) {
                $list['join_status'] = '火热报名中';
            } else {
                $list['join_status'] = '报名停止';
            }
        }
        $data = ['code'=>0,'msg'=>'成功','lists' => $lists];
        return $data;
    }

    //筛选条件处理
    private function attachParams($request=[]){
        $where = [];
        $orderBy = [];
        //ID
        if (isset($request['id']) && $id = intval($request['id'])) {
            $where['tour_article.id'] = $id;
        }
        //文章标题
        if (isset($request['title']) && $title = trim($request['title'])) {
            $where['tour_article.title like'] = $title;
        }
        //领队
        if (isset($request['leader_name']) && $leader_name = trim($request['leader_name'])) {
            $leader = (new TourLeader)->getOne(['fields'=>['id'],'where'=>['name'=>$leader_name]]);
            if ($leader) {
                $where['tour_article.leader_id'] = $leader['id'];
            } else {
                $where['tour_article.id'] = -1;
            }
        }
        //开始时间
        if (isset($request['start_time']) && $start_time = trim($request['start_time'])) {
            $where['tour_article.start_time'] = $start_time;
        }
        $where['tour_article.status'] = 1;
        $orderBy['tour_article.start_time'] = 'desc';
        $orderBy['tour_article.end_time'] = 'desc';
        $orderBy['tour_article.id'] = 'desc';
        return ['where'=>$where,'orderBy'=>$orderBy];
    }

}
