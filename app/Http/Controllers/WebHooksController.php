<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebHooksController extends Controller{




    public function onPush(Request $request){
        $target = base_path();
        $data = $request->post();
        exec('git pull',$output,$ret);
        Log::info('github event',$data);
        Log::info("cd $target && exec git pull",['output' => $output,'ret' => $ret]);
        return 'ok';
    }
}