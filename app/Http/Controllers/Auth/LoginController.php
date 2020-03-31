<?php


namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Logic\User\IndexLogic;
use App\Utils\Response;
use Huyibin\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class LoginController extends Controller{


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * 登录
     */
    public function login(Request $request){

        $cr = $request->only('phone','code');
        $logic = new IndexLogic;
        $token = $logic->loginByCode($cr['phone'],$cr['code']);
        
        return Response::api($token);
    }


    public function logout(){

        
    }


}
