<?php

namespace App\Http\Logic\Cart;

use App\Model\Cart;
use App\Model\Goods;
use App\Model\GoodsSpec;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IndexLogic
{

    public function addToCart(int $user_id,int  $goods_id,int  $count = 1,?int $spec_id = null):void
    {

        DB::transaction(function ()use($user_id,$goods_id,$count,$spec_id) {

            if(!Goods::searchStatus(1)->where('id',$goods_id)->count()){
                throw new \Exception('该商品不存在或已下架');
            }

            if($spec_id){
                if(!GoodsSpec::where('goods_id',$goods_id)->where('id',$spec_id)->count()){
                    throw new \Exception('该商品规格不存在');
                }

                $cart = Cart::where('user_id', $user_id)
                    ->where('goods_id', $goods_id)
                    ->where('spec_id', $spec_id)
                    ->first();
            }else{

                $cart = Cart::where('user_id', $user_id)
                    ->where('goods_id', $goods_id)
                    ->first();
            }

            if ($cart) {
                $cart->increment('count', $count);
            } else {
                Cart::create([
                    'user_id' => $user_id,
                    'goods_id' => $goods_id,
                    'spec_id' => $spec_id,
                    'count' => $count,
                ]);
            }
        });

    }

    /**
     * 减少购物车数量
     *
     * @param [type] $user_id
     * @param [type] $goods_id
     * @param integer $count
     * @param [type] $spec_id
     * @return void
     */
    public function decFromCart(int $user_id,int $cart_id,int $count = 1):void
    {
        try {

            DB::transaction(function ()use($user_id,$cart_id,$count) {

                $cart = Cart::select(['id','count'])->where('user_id',$user_id)->where('id',$cart_id)->firstOrFail();
                $ret = $cart->whereRaw("count >= $count+1")->decreament('count', $count);
                if(!$ret){
                    throw new \Exception('操作失败');
                }
            });
        } catch (ModelNotFoundException $e) {
            $name = $e->getModel();
            if($name === Goods::class){
                throw new \Exception('该商品已删除');
            }else if($name === Cart::class){
                throw new \Exception('该商品不在您的购物车里');
            }else{
                throw $e;
            }
        }
    }

    /**
     * 直接改数量
     *
     * @param [type] $user_id
     * @param [type] $cart_id
     * @param [type] $count
     * @return void
     */
    public function modifyCount(int $user_id,int $cart_id,int $count):void{
        try {

            DB::transaction(function ()use($user_id,$cart_id,$count) {

                $cart = Cart::select(['id','count'])
                    ->where('user_id',$user_id)
                    ->where('id',$cart_id)
                    ->firstOrFail();
                $cart->count = $count;
                $cart->save();
            });
        } catch (ModelNotFoundException $e) {
            $name = $e->getModel();
            if($name === Goods::class){
                throw new \Exception('该商品已删除');
            }else if($name === Cart::class){
                throw new \Exception('该商品不在您的购物车里');
            }else{
                throw $e;
            }
        }
    }

    /**
     * 移除
     *
     * @param array $ids
     * @param integer $user_id
     * @return void
     */
    public function removeCartItem(int $user_id,array $ids):void
    {
            Cart::where('user_id',$user_id)->whereIn('id',$ids)->delete();
    }


    public function calculate(Array $ids,$user_id){
        $carts = Cart::select(['id', 'goods_id', 'spec_id', 'count'])
            ->whereIn('id',$ids)
            ->where('user_id',$user_id)
            ->with(['spec'=>function($query){
                return $query->select(['id','price']);
            },'goods'=>function($query){
                return $query->select(['id','price']);
            }])->get();

        $totalPrice = 0;
        foreach ($carts as $cart){
            if($cart->spec){
                $totalPrice += $cart->spec->price * $cart->count;
            }else{
                $totalPrice += $cart->goods->price * $cart->count;
            }
        }
        return $totalPrice;
    }
}
