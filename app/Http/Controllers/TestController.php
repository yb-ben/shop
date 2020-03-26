<?php

namespace App\Http\Controllers;

use App\Jobs\Test;

class TestController extends Controller{



    public function index(){


        Test::dispatch()->delay(now()->addMinutes(10));
        return 'ok';
    }
}