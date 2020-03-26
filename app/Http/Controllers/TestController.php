<?php

namespace App\Http\Controllers;

use App\Jobs\Test;

class TestController extends Controller{



    public function index(){


        Test::dispatch()
        ->delay(now()->addSeconds(60))
        ->onConnection('redis')
        ;
        return 'ok';
    }
}