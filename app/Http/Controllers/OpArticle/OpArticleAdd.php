<?php
//文章-查看
namespace App\Http\Controllers\OpArticle;

use App\Library\File;
use App\Models\TourArticle\TourArticle;
use App\Models\TourArticle\TourArticlePic;
use App\Models\TourArticle\TourArticleSchedule;
use Illuminate\Support\Facades\DB;

class OpArticleAdd extends OpArticleBase
{
    public function index()
    {
        //参数校验及获取
        $this->request['time_request'] = date('Y-m-d H:i:s');
        $resDealParams = $this->dealParams($this->request);
        if ($resDealParams['code'] < 0) {
            return $resDealParams;
        }
        $article = $resDealParams['article'];
        $schedules = $resDealParams['schedules'];
        $pics = $resDealParams['pics'];

        //开始新增
        $TourArticleMod = new TourArticle();
        $TourArticleScheduleMod = new TourArticleSchedule();
        $TourArticlePicMod = new TourArticlePic();
        DB::beginTransaction();
        try{
            //文章信息
            $id = $TourArticleMod->insertGetId($article);
            //行程安排
            if ($schedules) {
                foreach ($schedules as &$schedule) {
                    $schedule['master_id'] = $id;
                    $schedule['date'] = trim($schedule['date']);
                    $schedule['content'] = trim($schedule['content']);
                    $schedule['created_at'] = $this->request['time_request'];
                    $schedule['updated_at'] = $this->request['time_request'];
                }
                $TourArticleScheduleMod->insert($schedules);
            }
            //文章图片
            if ($pics) {
                foreach ($pics as &$pic) {
                    $pic['master_id'] = $id;
                    $pic['pic_url'] = File::delImgHost(trim($pic['pic_url']));
                    $pic['created_at'] = $this->request['time_request'];
                    $pic['updated_at'] = $this->request['time_request'];
                }
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
        //价格
        if (isset($request['price']) && $price = intval($request['price'])) {
            $article['price'] = $price;
        } else {
            return ['code'=>-1, 'msg'=>'请输入活动价格'];
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
        $article['created_at'] = $request['time_request'];
        $article['updated_at'] = $request['time_request'];

        $schedules = isset($request['schedules']) ? $request['schedules'] : [];
        $pics = isset($request['pics']) ? $request['pics'] : [];

        return ['code'=>1,'msg'=>'校验成功','article'=>$article,'schedules'=>$schedules,'pics'=>$pics];
    }

}
