<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Logic\Cart\IndexLogic;
use App\Utils\Response;

class IndexController extends Controller{

    public function index(){


        return Response::api(['test'=> session('test')]);
    }



}
