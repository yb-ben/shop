<?php


namespace App\Http\Controllers\Admin;

use Illuminate\Support\Str;
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
        if(Auth::guard('web')->attempt($cr)){
            $token = Str::random();
            $admin = Auth::guard('web')->user();
            $admin->api_token = $token;
            $admin->save();
            return Response::api(['token' => $token]);
        }
        return Response::apiError('账号密码错误');
    }

    public function logout(){

        Auth::guard('web')->logout();
        return Response::api([],'退出成功');
    }

    public function username(){
        return 'username';
    }
}
