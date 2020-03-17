<?php

namespace App\Model;

use App\Model\Base;

class GoodsGallery extends Base{
    public $timestamps = false;

    protected $table = 'goods_gallery';
    protected $fillable = ['goods_id','url','file_id'];
 

    public function getImgFullAttribute(){
        return env('APP_URL').$this->img;
    }
}