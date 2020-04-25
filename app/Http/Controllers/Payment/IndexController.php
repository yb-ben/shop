<?php


namespace App\Http\Controllers\Payment;


use App\Http\Controllers\Controller;
use App\Model\Payment;
use App\Utils\Response;
use Yansongda\Pay\Pay;

class IndexController extends Controller
{


    //支付方式
    public function methods(){

       $payments = Payment::select(['id','name'])->get();
        return Response::api($payments);
    }





}
