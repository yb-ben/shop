<?php

namespace App\Http\Controllers;

use Huyibin\Sms\Sms;
use Illuminate\Support\Facades\Redis;

class TestController extends Controller{



    public function index(){

       
        $code = app('VCode')->generateAndStore('15917861851',6);
        dd($code);
       // return  Sms::sendVerificationCode('15917861851',$code);

    }


    public function check($phoneNumber,$code){

        return app('VCode')->check($phoneNumber,$code);
    }
}
