<?php

namespace App\Model\Relations;

use App\Model\Goods;
use App\Model\GoodsSpec;

trait CartRelations{


    public function goods(){
        return $this->belongsTo(Goods::class,'goods_id');
    }


    public function spec(){
        return $this->belongsTo(GoodsSpec::class,'spec_id');
    }
}