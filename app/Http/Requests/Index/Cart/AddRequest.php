<?php

namespace App\Http\Requests\Index\Cart;

use App\Http\Requests\StoreBase;

class AddRequest extends StoreBase{



    public function rules(){

        return [
            'goods_id' => 'required|integer',
            'count' => 'integer|min:1|max:200',
            'spec_id' => 'nullable|integer',
         ];
    }


    public function message(){

        return [

        ];
    }
}
