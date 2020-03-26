<?php

namespace App\Model\Relations;

use App\Model\GoodsCategory;
use App\Model\GoodsContent;
use App\Model\GoodsGallery;
use App\Model\GoodsSpec;

trait GoodsRelations{



    
    public function category(){
        return $this->belongsTo(GoodsCategory::class,'cate_id');
    }

    public function gallery(){
        return $this->hasMany(GoodsGallery::class,'goods_id');
    }

    public function content(){
        return $this->hasOne(GoodsContent::class,'id','content_id');
    }

    public function specs(){
        return $this->hasMany(GoodsSpec::class,'goods_id');
    }

}