<?php

namespace App\Utils;

class Response {


    public static function api($data = [],$msg = '',$errcode = 0,$debug = '')
    {
        return response()->json(Format::api($data,$msg,$errcode,$debug));
    }

    public static function apiError($msg = '',$errcode = 40000,$data = [],$debug = '')
    {
        return response()->json(Format::api($data,$msg,$errcode,$debug));
    }
}