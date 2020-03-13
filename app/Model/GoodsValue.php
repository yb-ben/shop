<?php

namespace App\Model;

use App\Model\Base;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodsValue extends Base{

    use SoftDeletes;

    protected $table = 'goods_value';
    protected $fillable = ['val','attr_id','goods_id','sort','status','created_at','updated_at','deleted_at'];
 

}