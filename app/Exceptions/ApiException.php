<?php

namespace App\Exceptions;

use App\Utils\Format;
use Exception;

class ApiException extends Exception{


    public function render($request){

        $data = Format::api([],$this->getMessage(),400);
        return response()->json($data);
    }
}