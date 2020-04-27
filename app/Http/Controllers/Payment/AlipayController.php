<?php


namespace App\Http\Controllers\Payment;


use App\Http\Controllers\Controller;
use App\Model\Order;
use App\Utils\Format;
use App\Utils\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yansongda\LaravelPay\Facades\Pay;
use Yansongda\Pay\Events;
use Yansongda\Pay\Exceptions\InvalidConfigException;
use Yansongda\Pay\Exceptions\InvalidSignException;
use Yansongda\Pay\Gateways\Alipay\Support;
use Yansongda\Supports\Config;

class AlipayController extends Controller
{
    //异步回调
    public function notify(Request $request)
    {
        Log::channel('alipay_notify')->info('Alipay notify start');

        $alipay = Pay::alipay();
        try {
            $d = $request->post();
            Log::channel('alipay_notify')->info('Alipay notify ',$d);

            $data =  $alipay->verify($d)->toArray();

            Log::channel('alipay_notify')->info('Alipay notify '.var_export($data,true));

            DB::transaction(function ()use($data){

                $order = Order::find($data['out_trade_no']);
                throw_if(empty($order),new \Exception('订单不存在'));
                throw_if($order->total_price !== Format::moneyHuman(floatval($order['total_amount'])),\Exception::class,'订单金额不一致');
                if($order->status === 1){
                    return ;
                }
                $order->status = 1;
                $order->method = 1;
                $order->paid_at = time();
                $order->save();

            });
        } catch (InvalidConfigException $e) {
            Log::channel('alipay_notify')->info('Alipay notify config '.$e->getMessage());

        } catch (InvalidSignException $e) {
            Log::channel('alipay_notify')->info('Alipay notify sign '.$e->getMessage());
            return 'false';
        }catch (\Throwable $t){
            Log::channel('alipay_notify')->info('Alipay notify exception '.$t->getMessage());

            return 'false';
        }
        return $alipay->success();
    }


    //同步回调
    public function sync(Request $request)
    {
        Log::channel('alipay_notify')->info('Alipay sync start');

        try {
            $data = $request->except(['_url']);
            Log::channel('alipay_notify')->info('Alipay sync '.var_export($data,true));

             $data = Pay::alipay()->verify($data)->toArray();



            DB::transaction(function ()use($data){

                $order = Order::find($data['out_trade_no']);
                throw_if(empty($order),\Exception::class,'订单不存在');
                throw_if($order->total_price !== Format::moneyHuman(floatval($order['total_amount'])),\Exception::class,'订单金额不一致');
                if($order->status === 1){
                    return ;
                }
                $order->status = 1;
                $order->method = 1;
                $order->paid_at = time();
                $order->save();

            });
        } catch (InvalidConfigException $e) {
            //Log::channel('alipay_notify')->info('Alipay sync config '.$e->getMessage());
            //return Response::apiError([],$e->getMessage());
            throw $e;
        } catch (InvalidSignException $e) {
            //Log::channel('alipay_notify')->info('Alipay sync sign '.$e->getMessage());
            //return Response::apiError([],$e->getMessage());
            throw $e;
        }catch (\Throwable $t){
            //return Response::apiError([],$t->getMessage());
            throw $t;
        }
        return Response::api([],'success');
    }

    /**
     * 手机网站支付
     * @param Request $request
     * @return false|string
     * @throws \Throwable
     */
    public function wap(Request $request){

        $order_id= $request->post('order_id');
        throw_if(empty($order_id),new \Exception('参数错误'));

        $order = Order::where('user_id',Auth::id())
            ->select(['id','status','total_price'])
            ->find($order_id);
        throw_if(empty($order_id),new \Exception('找不到该订单'));
        throw_if($order->status !== 0,new \Exception('订单状态已变化'));
        return Response::api(Pay::alipay()->wap([
            'out_trade_no'=>$order->id,
            'total_amount'=> Format::moneyHuman($order->total_price),
            'subject' => 'test',
            'return_url'=> env('APP_CLIENT_SERVER').'/#/order/result?oid='.$order->id
        ])->getContent());
    }


    //授权回调
    public function authCallback()
    {

    }
}
