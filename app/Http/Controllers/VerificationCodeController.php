<?php

namespace App\Http\Controllers;

use App\Utils\Response;
use Illuminate\Http\Request;

class VerificationCodeController extends Controller{


    /**
     * 验证码
     *
     * @param Request $request
     * @return void
     */
    public function code(Request $request){

        $phone = $request->input('phone');

        $code = app(TestController::class)->code($phone);
        
        return Response::api($code);
    }



}
