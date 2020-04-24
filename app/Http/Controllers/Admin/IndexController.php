<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Logic\Cart\IndexLogic;
use App\Jobs\Order\ExpireOrder;
use App\Model\Payment;
use App\Utils\Response;

class IndexController extends Controller{

    public function index(){

        ExpireOrder::dispatch('1')->delay(60)->onQueue('order')->afterResponse();

        return Response::api();
    }



}
