<?php

namespace App\Model;

use App\Model\Relations\CartRelations;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Base{

    use SoftDeletes,CartRelations;

    protected $table = 'cart';

    protected $fillable = [
        'user_id',
        'goods_id',
        'spec_id',
        'count',
    ];




    public function scopeUser($query,$user_id){
        $query->where('user_id',$user_id);
    }

}
