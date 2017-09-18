<?php
//领队-集合（ID+姓名）
namespace App\Http\Controllers\OpLeader;

use App\Models\TourLeader\TourLeader;

class OpLeaderGroup extends OpLeaderBase
{
    public function index()
    {
        $TourLeaderMod = new TourLeader();
        $group = $TourLeaderMod->getList(['fields'=>['id','name'],'where'=>['status'=>1]]);
        return $group;
    }

}
