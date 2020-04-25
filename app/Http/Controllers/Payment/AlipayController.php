<?php


namespace App\Http\Controllers\Payment;


use App\Http\Controllers\Controller;
use App\Model\Order;
use App\Utils\Format;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yansongda\LaravelPay\Facades\Pay;

class AlipayController extends Controller
{
    //异步回调
    public function notify()
    {
        $alipay = Pay::alipay();
        $data= $alipay->verify();
        $data = $data->toArray();
        try{

            DB::transaction(function ()use($data){

                $order = Order::find($data['trade_out_no']);
                throw_if(empty($order),\Exception::class,['订单不存在']);
                throw_if($order->total_price !== Format::moneyHuman(floatval($order['total_amount'])),\Exception::class,['订单金额不一致']);
                $order->status = 1;
                $order->method = 1;
                $order->paid_at = time();
                $order->save();

            });
        }catch (\Throwable $t){
            return $t->getMessage();
        }
        Log::debug('Alipay notify',$data);
        return $alipay->success();
    }

    /**
     * 手机网站支付
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Throwable
     */
    public function wap(Request $request){

        $order_id= $request->post('order_id');
        throw_if(empty($order_id),\Exception::class,['参数错误']);

        $order = Order::where('user_id',Auth::id())
            ->select(['id','status','total_price'])
            ->find($order_id);
        throw_if(empty($order_id),\Exception::class,['找不到该订单']);
        throw_if(empty($order->status !== 0),\Exception::class,['订单状态已变化']);
        return Pay::alipay()->wap([
            'out_trade_no'=>$order->id,
            'total_amount'=>$order->total_price,
            'subject' => 'test'
        ]);
    }


    //授权回调
    public function authCallback()
    {

    }
}
