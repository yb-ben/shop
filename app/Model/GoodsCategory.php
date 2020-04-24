<?php

namespace App\Model;

use App\Model\Scope\GoodsCategoryScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodsCategory extends Base{

    use SoftDeletes,GoodsCategoryScope,\App\Model\Relations\Image;

    protected $table = 'goods_category';

    protected $fillable = ['name','parent_id','path','status','sort','remark','created_at','updated_at','deleted_at','level','image_id'];







    //路径数组
    public function getPathArrayAttribute(){

        if($this->path !== ""){
            return $this->attributes['path_array'] = array_map(function($val){ return intval($val);},explode('-', $this->path));
        }
        return [];
    }

}
