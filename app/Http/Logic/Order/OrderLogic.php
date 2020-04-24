<?php


namespace App\Http\Logic\Order;


use App\Jobs\Order\ExpireOrder;
use App\Model\Cart;
use App\Model\Goods;
use App\Model\Order;
use App\Utils\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Psy\Util\Str;

class OrderLogic
{


    protected $expireTime = 1800;

    protected $logic;

    public function __construct(OrderLogicInterface $logic)
    {
        $this->logic = $logic;
    }


    public function createOrder(){

        $ret =  DB::transaction(function(){
            $time = time();

            $order = Order::create([
                'id'=>$this->getId(),
                'user_id' => $this->logic->getUser()->getAuthIdentifier(),
                'user_name' => $this->logic->getUser()->name,
                'total_price' => $this->logic->getTotalPrice(),
                'discount_price' => $this->logic->getDiscountPrice(),
                'price' => $this->logic->getTotalPrice() - $this->logic->getDiscountPrice(),
                'freight' => $this->logic->getFreight(),
                'ref_type' => 1,
                'expired_at' => $time + $this->expireTime,
            ]);

            $this->logic->createOrder($order->id);



            return $order;

        });



        $this->fireAfterCreate($ret);

        return $ret;
    }


    private function fireAfterCreate($order){
       dispatch(new ExpireOrder($order->id))
           ->onConnection('redis')
           ->onQueue('order')
           ->delay($this->expireTime)
           ->afterResponse();
    }

    private function getId(){
        static $str;
        $str || $str = '0123456789';
        $id = date('YmdHis');
        for($i = 0 ;$i<16; $i++){
            $p = random_int(0,rand())%10;
            $id .= mb_substr($str,$p,1);
        }
        return $id;
    }
}
