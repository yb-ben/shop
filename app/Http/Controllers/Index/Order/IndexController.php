<?php


namespace App\Http\Controllers\Index\Order;


use App\Http\Controllers\Controller;
use App\Http\Logic\Order\OrderGoodsLogic;
use App\Http\Logic\Order\OrderLogic;
use App\Http\Requests\Index\Order\Calculate;
use App\Http\Requests\Index\Order\SubmitFromCart;
use App\Model\OrderGoods;
use App\Utils\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{


    public function calculate(Calculate  $request){

        $post = $request->validated();
        $logic = new OrderGoodsLogic($post,Auth::user());

        $items = $logic->getGoodsInfo();
        $totalPrice = $logic->getTotalPrice();
        return Response::api(['items' => $items ,'totalPrice' => round(intval($totalPrice/100),2)]);
    }


    public function submit(SubmitFromCart $request){

        $post = $request->validated();
        $logic = new OrderLogic(new OrderGoodsLogic($post,Auth::user()));
        $id = $logic->createOrder();
        return Response::api(['id' => $id]);
    }



}