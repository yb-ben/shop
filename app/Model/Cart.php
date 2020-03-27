<?php

namespace App\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Base{

    use SoftDeletes;

    protected $table = 'cart';

    protected $fillable = [
        'user_id',
        'goods_id',
        'spec_id',
        'title',
        'price',
        'line_price',
        'main_image',
        'spu',
        'count',
    ];
 

    public function getMainImageFullAttribute(){
        return env('APP_URL').$this->main_image;
    }

}