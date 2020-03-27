<?php

namespace App\Model\Relations;

use App\Model\Cart;

trait UserRelations{


    public function cart(){
        return $this->hasMany(Cart::class,'user_id');
    }
}