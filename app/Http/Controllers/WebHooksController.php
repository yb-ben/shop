<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebHooksController extends Controller{




    public function onPush(Request $request){
        $data = $request->post();
        print_r($data);
        return 'ok';
    }
}