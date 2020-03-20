<?php

namespace App\Model;

use App\Model\Base;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodsSpec extends Base{

    use SoftDeletes;

    protected $table = 'goods_spec';
    protected $fillable = ['price','goods_id','sku','count','cost','code','sell','lock','weight','created_at','updated_at','deleted_at'];
 
   

    public function getSpuAttribute($value){
        $spus = explode(',',$value);
        $ret = [];
        foreach($spus as $spu){
            list($attr,$value) = explode(':',$spu);
            $ret[$attr] = $value;
        }
        return $ret;
    }
}