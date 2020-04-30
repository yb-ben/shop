<?php


namespace App\Http\Controllers\Index\Order;


use App\Http\Controllers\Controller;
use App\Http\Logic\Order\OrderGoodsLogic;
use App\Http\Logic\Order\OrderLogic;
use App\Http\Requests\Index\Order\Calculate;
use App\Http\Requests\Index\Order\SubmitFromCart;
use App\Model\Order;
use App\Model\OrderGoods;
use App\Utils\Format;
use App\Utils\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

class IndexController extends Controller
{

    /**
     * 计算价格
     * @param Calculate $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function calculate(Calculate  $request){

        $post = $request->validated();
        $logic = new OrderGoodsLogic($post,Auth::user());

        $items = $logic->getGoodsInfo();
        $totalPrice = $logic->getTotalPrice();
        return Response::api(['items' => $items ,'totalPrice' => round(intval($totalPrice/100),2)]);
    }

    /**
     * 下单
     * @param SubmitFromCart $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function submit(SubmitFromCart $request){
        $post = $request->validated();
        $logic = new OrderLogic(new OrderGoodsLogic($post,Auth::user()));
        $ret = $logic->createOrder();
        return Response::api([
            'id' => $ret->id,
            'expire'=>$logic->getExpireTime()
        ]);
    }

    /**
     * 获取付款信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOrderPaymentInfo(Request $request){
        $oid = $request->input('oid');
        $order = Order::select(['id','total_price','status','expired_at'])
            ->where('user_id',Auth::id())
            ->find($oid);
        throw_if(empty($order),\Exception::class,'找不到该订单');
        if($order->status === 1){
            return Response::api([],'该订单已完成支付',301);
        }
        $order = $order->toArray();
        $order['total_price'] = Format::moneyHuman($order['total_price']);

        $token = Str::random();
        Redis::set('o:'.$token,$oid,$order['expired_at'] - time());
        $order['_token'] = $token;

        return  Response::api($order);
    }

    /**
     * 订单列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request){
        $t = $request->get('t',0);
        $orders = Order::with(['orderGoods'=>function($query){
                $query->select(['id','order_id','title','image_url','goods_id','count','price']);
            }])
            ->type($t)
            ->where('user_id',Auth::id())
            ->select(['id','status','created_at','total_price','discount_price','price'])
            ->orderby('created_at','desc')
            ->simplePaginate($request->get('limit',10));

        return Response::api([
            'data'=>  $orders->getCollection()->append(['status_text']),
            'current_page' => $orders->currentPage(),
            'more' => $orders->hasMorePages()
        ]);
    }

    /**
     * 删除订单
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function del($id){
        $order = Order::select(['id','status'])->where('user_id',Auth::id())->find($id);
        throw_if(empty($order),\Exception::class,'订单不存在');
        throw_if($order->status !== 3,\Exception::class,'该订单不能删除');
        $order->user_deleted = 1;
        $order->save();
        return Response::api();
    }


    public function detail($id){
        $order = Order::where('user_id',Auth::id())->find($id);
        throw_if(empty($order),\Exception::class,'订单不存在');

        return Response::api();
    }
}
