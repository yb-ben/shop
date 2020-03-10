<?php

namespace App\model;

use Illuminate\Database\Eloquent\SoftDeletes;

class GoodsCategory extends Base{

    use SoftDeletes;

    protected $table = 'goods_category';

    protected $fillable = ['name','parent_id','path','status','sort','remark','created_at','updated_at','deleted_at'];
   //protected $guarded = ['id'];

    

    public function attrs(){

        return $this->hasMany(CategoryAttr::class,'cate_id');
    }


    public function getPathArrayAttribute(){

        if($this->path !== ""){
            return $this->attributes['path_array'] = explode('-', str_replace('0','',$this->path));
        }
        return [];
    }
    
}