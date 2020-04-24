<?php


namespace App\Http\Requests\Index\Order;


use App\Http\Requests\StoreBase;

class SubmitFromCart extends StoreBase
{

    public function rules(){

        return [
            'price' => ['integer'],
            'addr_id' => ['required'],
            'data' => ['required','array'],
            'data.*.goods_id'=>['required','integer'],
            'data.*.spec_id' => ['nullable','integer'],
            'data.*.count' => ['required','integer'],
            'data.*.cart_id' =>  ['integer']
        ];
    }


    public function message(){

        return [

        ];
    }
}
