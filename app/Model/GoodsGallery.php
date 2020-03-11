<?php

namespace App\Model;

use App\Model\Base;

class GoodsGallery extends Base{
    public $timestamps = false;

    protected $table = 'goods_gallery';
    protected $fillable = ['goods_id','img'];
 
}