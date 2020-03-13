<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebHooksController extends Controller{




    public function onPush(Request $request){
        $target = base_path();
        $data = $request->post();
        $token = env('WEBHOOkS_TOKEN');

        if(!isset($data['token']) || $data['token'] !== $token ){
            Log::info('webhooks token not equals',['value' => $data['token'],'expect' => $token]);
            return 'false';
        }
        exec('git pull',$output,$ret);
        Log::info("cd $target && exec git pull",['output' => $output,'ret' => $ret]);
        return 'ok';
    }
}