<?php


namespace App\Http\Logic\Order;


use App\Model\Cart;
use App\Model\Goods;
use App\Model\Order;
use App\Model\OrderGoods;
use App\Model\OrderShipping;
use App\Model\UserAddr;
use App\Utils\Auth\Authenticate;
use App\Utils\Auth\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderGoodsLogic implements OrderLogicInterface
{

    protected $data;

    protected $goodsInfo ;

    protected $totalPrice ;

    protected $discountPrice = 0;


    protected $user;

    public function __construct(array $data,Authenticatable $user)
    {
        $this->data = $data;
        $this->user = $user;
        $this->init();
        $this->calculate();
    }

    /**
     * 加载数据
     */
    protected function init(){

        $this->data['data'] = array_column($this->data['data'],null,'goods_id');


        $ids = array_column($this->data['data'], 'goods_id');

        $spec_ids = array_column($this->data['data'],'spec_id');

        $this->goodsInfo = Goods::whereIn('id', $ids)
            ->with([
                'specs' => function ($query) use($spec_ids){
                    return $query->select(['id','goods_id','sku','price','count','updated_at'])
                        ->whereIn('id',$spec_ids);
                }])
            ->select(['id', 'price', 'title', 'image_id', 'line_price','status','count','updated_at'])
            ->get()
        ;
    }



    /**
     * 计算
     */
    protected function calculate()
    {
        $totalPrice = 0;
        $pointer = null;



        foreach ($this->goodsInfo as $goods){

            $pointer =  &$this->data['data'][$goods->id];

            if($goods->status !== 1){
                throw new \Exception('部分商品已失效，请刷新');
            }
             if(!empty($goods->specs) && !empty($goods->specs[0])){

                $totalPrice += intval($goods->specs[0]->price) * 100 * $pointer['count'];

            }else{

                $totalPrice += intval($goods->price) * 100 * $pointer['count'];

            }


        }
        $this->totalPrice = $totalPrice;

    }



    /**
     * 返回商品集合
     * @return mixed
     */
    public function getGoodsInfo(){

        $this->goodsInfo->load(['image'=>function($query){
            $query->baseSelect();
        }]);
        $this->goodsInfo
            ->append(['image_url'])
            ->makeHidden(['image_id','image','status','count','updated_at']);
        foreach ($this->goodsInfo as $item){
            empty($item->specs) || empty($item->specs[0]) || $item->specs[0]->append(['sku_text'])->makeHidden(['sku','count','updated_at']);
            $item->setAttribute( 'submit_count',$this->data['data'][$item->id]['count']);
        }
        return $this->goodsInfo;
    }

    public function createOrder($order_id)
    {
        $time = time();
        $goodsInfo = $this->getGoodsInfo()->load(['image'=>function($query){
            $query->baseSelect();
        }]);
        $goodsInfo->append(['image_url']);

        foreach ($goodsInfo as $item){

            $inserts[] = [
                'order_id' => $order_id,
                'title' => $item->title,
                'image_url' => $item->image_url,
                'goods_id' => $item->id,
                'sku_text' => ($item->specs && $item->specs[0])?$item->specs[0]->append(['sku_text'])->sku_text:null,
                'count' => $item->submit_count,
                'price' => (($item->specs && $item->specs[0])?$item->specs[0]->price:$item->price)*100,
                'created_at' => $time
             ];
        }
        if(!OrderGoods::insert($inserts)){

            throw new \Exception('创建订单失败');
        }
        foreach ($goodsInfo as $item){
             //修改库存
            if($item->specs && $item->specs[0]){
                $item->specs[0]->count -=  $item->submit_count;
                $rt = $item->specs[0]
                    ->where('id',$item->specs[0]->id)
                  //  ->where('updated_at',$item->specs[0]->updated_at)
                    ->where('count','>=', $item->specs[0]->count )
                    ->decrement('count',$item->submit_count,['updated_at'=> $time ]);
                if(!$rt){
                    throw new \Exception('库存不足');
                }
            }
            $item->count -=  $item->submit_count;
            $rt = $item
                ->where('id',$item->id)
                //->where('updated_at',$item->updated_at)
                ->where('count','>=',$item->submit_count)
                ->decrement('count',$item->submit_count,['updated_at'=> $time ])
            ;
            if(!$rt){
                throw new \Exception('库存不足');
            }

            //修改购物车
            $p = &$this->data['data'][$item->id];
            if(!empty($p['cart_id'])){
                 $cart = Cart::where('id',$p['cart_id'])
                    ->where('user_id',$this->user->id)
                    ->first();
                 if(!empty($cart)){
                     if($cart->count > $item->submit_count){
                         $cart->count -= $item->submit_count;
                         $cart->save();
                     }else{
                         $cart->delete();
                     }

                 }
            }

            $this->setShipping($order_id);

        }
    }


    private function setShipping($order_id){

        $addr = UserAddr::where('user_id',$this->user->id)->find($this->data['addr_id']);
        if(empty($addr)){
            throw new \Exception('找不到地址信息');
        }
        OrderShipping::create([
            'order_id' => $order_id,
            'name' => $addr->name,
            'phone'=> $addr->phone,
            'address' => $addr->addr_full,
            'area_code' => $addr->county_id
        ]);
    }



    public function getFreight(){

        return 0;
    }



    public function getUser(){

        return $this->user;
    }

    public function getDiscountPrice(){
        return $this->discountPrice;
    }



    public function getTotalPrice(){
        return $this->totalPrice;
    }

}
