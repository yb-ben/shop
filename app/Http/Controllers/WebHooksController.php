<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebHooksController extends Controller{




    public function onPush(Request $request){
        $target = base_path();

        $token = env('WEBHOOkS_TOKEN');
        $signature = "sha1=".hash_hmac('sha1', $request->getContent(),$token );

        if(strcmp($signature, $request->header('X-Hub-Signature')) != 0){
            Log::info('signature invalid');
            return 'false';
        }
        exec('git pull',$output,$ret);
        Log::info("cd $target && exec git pull",['output' => $output,'ret' => $ret]);
        return 'ok';
    }
}