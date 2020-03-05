<?php

namespace App\Utils;

class Format {


    public static function api($data = [],$msg = '',$errcode = 0,$debug = '')
    {
        return [
            'data' =>$data,
            'msg' => $msg,
            'code' => $errcode
        ];
    }

 
}