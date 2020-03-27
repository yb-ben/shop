<?php


namespace App\Http\Logic\Cart;

use App\Model\{
    Cart,
    Goods,User
};
use Illuminate\Support\Facades\DB;

class IndexLogic{

 

    public function addToCart($user_id,$goods_id,$count = 1,$spec_id = null){

        DB::transaction(function(){

            $goods = Goods::findOrFail($goods_id);
            $spec = $spec_id? $goods->spec()->findOrFail($spec_id):null;
            
            $cart = Cart::where('user_id',$user_id)
            ->where('goods_id',$goods_id)
            ->where('spec_id',$spec_id)
            ->first();
    
            if($cart){
                $cart->increament('count',$count);
            }else{
                Cart::create([
                    'user_id' =>$user_id,
                    'goods_id' => $goods_id,
                    'spec_id' => $spec_id,
                    'title' => $goods->title,
                    'main_image' => $goods->main_image,
                    'price' => $spec? $spec->price:$goods->price,
                    'line_price' => $goods->line_price,
                    'count' => $count,
                    'spu' => $spec? $spec->sku_text:null,
                ]);
            }
        });
      
    }
}