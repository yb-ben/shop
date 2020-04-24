<?php

namespace App\Model\Relations;

use App\Model\Cart;
use App\Model\UserAddr;

trait UserRelations{


    public function cart(){
        return $this->hasMany(Cart::class,'user_id');
    }


    public function addrs(){
        return $this->hasMany(UserAddr::class,'user_id');
    }
}
