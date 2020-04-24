<?php

namespace App\Model;

use App\Model\Base;

class GoodsGallery extends Base{

    use \App\Model\Relations\Image;

    public $timestamps = false;



    protected $table = 'goods_gallery';
    protected $fillable = ['goods_id','image_id'];


}
