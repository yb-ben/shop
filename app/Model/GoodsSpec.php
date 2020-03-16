<?php

namespace App\Model;

use App\Model\Base;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodsSpec extends Base{

    use SoftDeletes;

    protected $table = 'goods_spec';
    protected $fillable = ['goods_id','spu','count','line_price','sell','status','created_at','updated_at','deleted_at'];
 
   

    public function getSpuAttribute($value){
        $spus = explode(',',$value);
        $ret = [];
        foreach($spus as $spu){
            list($attr_id,$value_id) = explode(':',$spu);
            $ret[$attr_id] = $value_id;
        }
        return $ret;
    }
}