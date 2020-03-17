<?php

namespace App\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class GoodsAttr extends Base{

    use SoftDeletes;

    protected $table = 'goods_attr';
    protected $fillable = ['name','created_at','updated_at','deleted_at'];
 
    //protected $guarded = ['id'];


    public function values(){

        return $this->hasMany(GoodsValue::class,'attr_id');
    }

}