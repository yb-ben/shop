<?php

namespace App\Http\Controllers\Index\User;

use App\Http\Logic\User\IndexLogic;
use App\Utils\Response;
use Huyibin\VerificationCode\Facade\VCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Validation\ValidationException;
use function AlibabaCloud\Client\json;

class LoginController extends Controller
{


    /**
     * @param Request $request
     * @return \Illuminate\Http\Response|void
     * @throws ValidationException
     */
    public function login(Request $request)
    {

        $credentials = $request->only('phone', 'code');

        if (Auth::attempt($credentials)) {
            // 通过认证..
            VCode::del($credentials['phone']);
            $api_token =  Auth::token();
            return Response::api(['token' => $api_token]);
        }
        return Response::apiError();
    }

    /**
     * 退出登录
     *
     * @return void
     */
    public function logout(){
        Auth::logout();
        return Response::api();
    }

    /**
     * 手机验证码注册
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function registerByCode(Request $request){
        $credentials = $request->only('phone', 'code');

         if(!VCode::check($credentials['phone'],$credentials['code'])){
             return Response::apiError('验证码不正确');
         }
         $user = app(IndexLogic::class)->register($credentials);
         if(empty($user)){
             return Response::apiError('注册失败');
         }
         Auth::guard()->login($user);
         VCode::del($credentials['phone']);
         $token = Auth::token();
        return Response::api(['token' => $token]);

    }
}
