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


    public static function moneyHuman($money){
        return number_format($money/100,2,'.','');
    }


    public static function moneyIntval($money){
        return intval(floatval($money)*100);
    }
}
