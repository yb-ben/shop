<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebHooksController extends Controller{


    protected $target = '';
    protected $token = '';

    public function __construct()
    {
        $this->config();
    }

    protected function config(){
        $this->target = base_path();
        $this->token = env('WEBHOOkS_TOKEN');     

        if(empty($this->token))
            throw new \Exception('未设置webhooks_token');
    }


    public function onPush(Request $request){
      
        $signature = "sha1=".hash_hmac('sha1', $request->getContent(),$this->token );
        if(strcmp($signature, $request->header('X-Hub-Signature')) != 0){
            Log::info('signature invalid');
            return 'false';
        }
        exec('git pull',$output,$ret);
        Log::info("cd {$this->target} && exec git pull",['output' => $output,'ret' => $ret]);
        return 'ok';
    }
}