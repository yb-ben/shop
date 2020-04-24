<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Logic\Cart\IndexLogic;
use App\Jobs\Order\ExpireOrder;
use App\Jobs\Test;
use App\Model\Payment;
use App\Utils\Response;
use Illuminate\Support\Facades\Redis;

class IndexController extends Controller{

    public function index(){

        dispatch(new ExpireOrder('1'))->delay(60)->afterResponse();
        dispatch(new Test())->delay(60)->afterResponse();
        return Response::api();
    }



}
