<?php

namespace App\model;

use Illuminate\Database\Eloquent\SoftDeletes;

class CategoryAttr extends Base{

    use SoftDeletes;

    protected $table = 'category_attr';
    protected $fillable = ['name','status','cate_id','sort','crated_at','updated_at','deleted_at'];
 
    //protected $guarded = ['id'];




}