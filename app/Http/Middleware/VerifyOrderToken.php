<?php


namespace App\Http\Middleware;


use Illuminate\Support\Facades\Redis;

class VerifyOrderToken
{


    public function handle($request, $next){

        $token = $request->input('_token');
        $order_id = $request->input('order_id');
        throw_if(empty($token),\Exception::class,'非法订单');
        $key = 'o:'.$token;
        $oid = Redis::get($key);
        throw_if($order_id !== $oid,\Exception::class,'订单已失效');
        Redis::del($key);
        return $next($request);
    }
}
