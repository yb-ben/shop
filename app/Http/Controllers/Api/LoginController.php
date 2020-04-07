<?php

namespace App\Http\Controllers\Api;

use App\Utils\Response;
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

            $api_token =  Auth::token();

            return Response::api(['token' => $api_token]);
        }
        return Response::apiError();
    }


    public function logout(){

        Auth::logout();
        return Response::api();
    }

}
