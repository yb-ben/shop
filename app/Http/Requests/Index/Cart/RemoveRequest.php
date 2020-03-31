<?php

namespace App\Http\Requests\Index\Cart;

use App\Http\Requests\StoreBase;

class RemoveRequest extends StoreBase{



    public function rules(){

        return [
            'ids' => 'required|array',
            'ids.*' => 'integer|min:1'
        ];
    }


    public function message(){

        return [

        ];
    }
}