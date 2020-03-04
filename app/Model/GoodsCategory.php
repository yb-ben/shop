<?php

namespace App\model;

use Illuminate\Database\Eloquent\SoftDeletes;

class GoodsCategory extends Base{

    use SoftDeletes;

    protected $table = 'goods_category';

   // protected $fillable = ['name','parent_id','path','status','sort','remark','crate_at','update_at','delete_at'];
    protected $guarded = ['id'];

    

    public function create($data,$pid = 0){

        if($pid){
            $parent = $this->field('id,path')->find($pid);
            if($parent){
                return parent::create(array_merge($data,['parent_id' => $pid,'path' => $parent->path.'-'.$pid]));
            }
            throw new \Exception('找不到改父分类');
        }   
        return parent::create($data);
    }


    public function edit($id,$data){
        $model = $this->find($id);
        $model->fill($data);
        return $model;
    }

    
}