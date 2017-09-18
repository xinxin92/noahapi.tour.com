<?php
//文章-编辑
namespace App\Http\Controllers\OpArticle;

use App\Library\File;
use App\Models\TourArticle\TourArticle;
use App\Models\TourArticle\TourArticlePic;
use App\Models\TourArticle\TourArticleSchedule;
use Illuminate\Support\Facades\DB;

class OpArticleEdit extends OpArticleBase
{
    public function index()
    {
        //测试备用
//        $this->request['schedules'] = [
//            ['date'=>'2017-10-26','content'=>'自由活动'],
//            ['date'=>'2017-10-27','content'=>'自由活动'],
//        ];
//        $this->request['pics'] = [
//            ['pic_url'=>'bb45a84347f92a3a9378130011c28a27.jpg'],
//            ['pic_url'=>'96748850eab968d089090e62304af8eb.jpg'],
//        ];

        //参数校验及获取
        $this->request['time_request'] = date('Y-m-d H:i:s');
        $resDealParams = $this->dealParams($this->request);
        if ($resDealParams['code'] < 0) {
            return $resDealParams;
        }
        $id = $resDealParams['id'];
        $article = $resDealParams['article'];
        $schedules = $resDealParams['schedules'];
        $pics = $resDealParams['pics'];

        //存在性校验
        $TourArticleMod = new TourArticle();
        $sourceArticle = $TourArticleMod->getOne(['fields'=>['id'],'where'=>['id'=>$id,'status <>'=>-1]]);
        if (!$sourceArticle) {
            return ['code'=>-1, 'msg'=>'该活动不存在或者已经被删除'];
        }

        //开始修改
        $TourArticleScheduleMod = new TourArticleSchedule();
        $TourArticlePicMod = new TourArticlePic();
        DB::beginTransaction();
        try{
            //文章信息
            $TourArticleMod->updateBy($article,['id'=>$id]);
            //行程安排
            if ($schedules) {
                foreach ($schedules as &$schedule) {
                    $schedule['date'] = trim($schedule['date']);
                    $schedule['content'] = trim($schedule['content']);
                    $schedule['master_id'] = $id;
                    $schedule['created_at'] = $this->request['time_request'];
                    $schedule['updated_at'] = $this->request['time_request'];
                }
                $TourArticleScheduleMod->updateBy(['status'=>-1],['master_id'=>$id]);
                $TourArticleScheduleMod->insert($schedules);
            }
            //文章图片
            if ($pics) {
                foreach ($pics as &$pic) {
                    $pic['pic_url'] = File::delImgHost(trim($pic['pic_url']));
                    $pic['master_id'] = $id;
                    $pic['created_at'] = $this->request['time_request'];
                    $pic['updated_at'] = $this->request['time_request'];
                }
                $TourArticlePicMod->updateBy(['status'=>-1],['master_id'=>$id]);
                $TourArticlePicMod->insert($pics);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return ['code'=>-100, 'msg'=>$e->getMessage()];
        }

        $data = ['code'=>0,'msg'=>'成功','id'=>$id];
        return $data;
    }

    //筛选条件处理
    private function dealParams($request=[]){
        //ID
        if (!isset($request['id']) || !($id = intval($request['id']))) {
            return ['code'=>-1, 'msg'=>'没有获取到活动ID'];
        }
        //开始时间
        if (isset($request['start_time']) && $start_time = trim($request['start_time'])) {
            $article['start_time'] = $start_time;
        } else {
            return ['code'=>-1, 'msg'=>'请选择开始时间'];
        }
        //结束时间
        if (isset($request['end_time']) && $end_time = trim($request['end_time'])) {
            $article['end_time'] = $end_time;
        } else {
            return ['code'=>-1, 'msg'=>'请选择结束时间'];
        }
        //标题
        if (isset($request['title']) && $title = trim($request['title'])) {
            $article['title'] = $title;
        } else {
            return ['code'=>-1, 'msg'=>'请输入活动标题'];
        }
        //封面
        if (isset($request['pic_url']) && $pic_url = trim($request['pic_url'])) {
            $article['pic_url'] = File::delImgHost($pic_url);
        } else {
            return ['code'=>-1, 'msg'=>'请上传封面'];
        }
        //领队
        if (isset($request['leader_id']) && $leader_id = intval($request['leader_id'])) {
            $article['leader_id'] = $leader_id;
        } else {
            return ['code'=>-1, 'msg'=>'请选择领队'];
        }
        //成团人数
        if (isset($request['least_num']) && $least_num = intval($request['least_num'])) {
            $article['least_num'] = $least_num;
        } else {
            return ['code'=>-1, 'msg'=>'请输入成团人数'];
        }
        //人数上线
        if (isset($request['most_num']) && $most_num = intval($request['most_num'])) {
            $article['most_num'] = $most_num;
        } else {
            return ['code'=>-1, 'msg'=>'请输入人数上线'];
        }
        //价格
        if (isset($request['price']) && $price = intval($request['price'])) {
            $article['price'] = $price;
        } else {
            return ['code'=>-1, 'msg'=>'请输入活动价格'];
        }
        //费用说明
        if (isset($request['price_explain']) && $price_explain = trim($request['price_explain'])) {
            $article['price_explain'] = $price_explain;
        } else {
            return ['code'=>-1, 'msg'=>'请输入费用说明'];
        }
        //报名须知
        if (isset($request['notice']) && $notice = trim($request['notice'])) {
            $article['notice'] = $notice;
        } else {
            return ['code'=>-1, 'msg'=>'请输入报名须知'];
        }
        //活动概述
        if (isset($request['introduction']) && $introduction = trim($request['introduction'])) {
            $article['introduction'] = $introduction;
        } else {
            return ['code'=>-1, 'msg'=>'请输入活动概述'];
        }
        //基本信息
        if (isset($request['information']) && $information = trim($request['information'])) {
            $article['information'] = $information;
        } else {
            return ['code'=>-1, 'msg'=>'请输入基本信息'];
        }
        //行程安排
        if (isset($request['schedules']) && $request['schedules']) {
            $schedules = $request['schedules'];
        } else {
            return ['code'=>-1, 'msg'=>'请新建行程安排'];
        }
        //宣传图片
        if (isset($request['pics']) && $request['pics']) {
            $pics = $request['pics'];
        } else {
            return ['code'=>-1, 'msg'=>'请新建宣传图片'];
        }

        $article['updated_at'] = $request['time_request'];

        return ['code'=>1,'msg'=>'校验成功','id'=>$id,'article'=>$article,'schedules'=>$schedules,'pics'=>$pics];
    }

}
