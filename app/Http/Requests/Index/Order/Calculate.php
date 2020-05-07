<?php


namespace App\Http\Requests\Index\Order;


use App\Http\Requests\StoreBase;

class Calculate extends StoreBase
{


    public function rules(){

        return [
            'data' => ['required','array'],
            'data.*.goods_id' => ['required','integer'],
            'data.*.spec_id' => ['nullable','integer'],
            'data.*.count' => ['required','integer','min:1']
        ];
    }


    public function message(){

        return [

        ];
    }
}
