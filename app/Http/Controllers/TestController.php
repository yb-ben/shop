<?php

namespace App\Http\Controllers;

use App\Jobs\Test;

class TestController extends Controller{



    public function index(){


        Test::dispatch()
        ->delay(now()->addMinutes(10))
        ->onConnection('redis')
        ->onQueue('test_job')
        ;
        return 'ok';
    }
}