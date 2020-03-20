<?php

namespace App\Model;

use App\Model\Base;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodsSpec extends Base{

    use SoftDeletes;

    protected $table = 'goods_spec';
    protected $fillable = ['key','price','goods_id','sku','count','cast','code','sell','lock','weight','created_at','updated_at','deleted_at'];
 
   

    public function getSkuAttribute($value){
        return json_decode($value);
    }

    public function setSkuAttribute($value){
        $this->attributes['sku'] = json_encode($value,JSON_UNESCAPED_UNICODE);
    }
}