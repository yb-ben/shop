<?php


namespace App\Http\Controllers\Admin;

use Tymon\JWTAuth\JWTAuth;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Login\LoginRequest;
use App\Utils\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;

class LoginController extends Controller{

   

    public function login(LoginRequest $request){
    
        $cr = $request->validated();
        $token = Auth::guard('api')->attempt($cr);
        if($token){
            return Response::api(['token' => $token]);
        }
        return Response::apiError('账号密码错误');
    }

    public function logout(){

        Auth::guard('api')->logout();
        return Response::api([],'退出成功');
    }
}