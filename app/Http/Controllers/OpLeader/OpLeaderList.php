<?php
//文章-列表
namespace App\Http\Controllers\OpLeader;

use App\Library\Common;
use App\Models\TourArticle\TourArticle;
use App\Models\TourLeader\TourLeader;

class OpLeaderList extends OpLeaderBase
{
    public function index()
    {
        //获取基本信息
        $pageMsg = Common::getPageMsg($this->request, 20);
        $attach = $this->attachParams($this->request);
        $fields = [
            'tour_leader.id',
            'tour_leader.name',
            'tour_leader.mobile',
            'tour_leader.weixin',
            'tour_leader.times',
            'tour_leader.skill',
        ];
        $TourArticleMod = new TourArticle();
        $lists = $TourArticleMod->getList(['fields'=>$fields,'where'=>$attach['where'],'orderBy'=>$attach['orderBy'],'skip'=>$pageMsg['skip'],'limit'=>$pageMsg['limit']]);
        $count = $TourArticleMod->countBy(['fields'=>$fields,'where'=>$attach['where']]);
        $data = ['code'=>0,'msg'=>'成功','count'=>$count,'lists' => $lists];
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
        //开始时间
        if (isset($request['start_time']) && $start_time = trim($request['start_time'])) {
            $where['tour_article.start_time'] = $start_time;
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
        //报名状态
        if (isset($request['join_status']) && in_array($join_status = intval($request['join_status']),[-1,1,2,3,4])) {
            if ($join_status == -1) {  //已结束
                $where['tour_article.start_time <='] = $request['time_now'];
            } else {
                $where['tour_article.start_time >'] = $request['time_now'];
                if ($join_status == 1) {  //名额充足
                    $where['raw'][] = 'tour_article.join_num < tour_article.least_num';
                } else if ($join_status == 2) {  //火热报名中
                    $where['raw'][] = 'tour_article.join_num >= tour_article.least_num and tour_article.join_num < (tour_article.most_num - 10)';
                } else if ($join_status == 3) {  //名额紧缺
                    $where['raw'][] = 'tour_article.join_num >= (tour_article.most_num - 10) and tour_article.join_num < tour_article.most_num';
                } else if ($join_status == 4) {  //名额已满
                    $where['raw'][] = 'tour_article.join_num >= tour_article.most_num';
                }
            }
        }

        $where['tour_leader.status'] = 1;
        $orderBy['tour_leader.updated_at'] = 'desc';
        return ['where'=>$where,'orderBy'=>$orderBy];
    }

}
