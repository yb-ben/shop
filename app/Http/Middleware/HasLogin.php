<?php


namespace App\Http\Middleware;

use App\Http\Logic\User\IndexLogic;
use Huyibin\JWT;
use Illuminate\Http\Request;

class HasLogin{


    public function handle(Request $request,$next){

        $a = $request->header('Authorization');
        $a = str_replace('Bearer ','',$a);
        $logic = new IndexLogic;
        $logic->check($a);
        $response =  $next($request);
        if($a !== $token){
            $response->header('Authorization','Bearer '.$token);
        }
        return $response;
    }
}