<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class IndexController extends Controller
{


    public function index(){
//        $redis= Redis::connection();
//
//        $redis->watch('sess_2');
//        $redis->multi()
//            ->expire('sess_2',3600)
//            ->exec();

        return Auth::id();

//        return 'ok';
    }
}
