<?php

namespace App\Http\Requests\Index\Cart;

use App\Http\Requests\StoreBase;

class ModifyRequest extends StoreBase{



    public function rules(){

        return [
            'cart_id' => 'required|integer',
            'count' => 'integer|min:1|max:200',
        ];
    }


    public function message(){

        return [

        ];
    }
}
