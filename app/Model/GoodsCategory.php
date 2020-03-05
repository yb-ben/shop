<?php

namespace App\model;

use Illuminate\Database\Eloquent\SoftDeletes;

class GoodsCategory extends Base{

    use SoftDeletes;

    protected $table = 'goods_category';

   // protected $fillable = ['name','parent_id','path','status','sort','remark','crate_at','update_at','delete_at'];
    protected $guarded = ['id'];

    

    public function add($data){
        $pid = intval($data['parent_id']);
        if($pid){
            
            $parent = $this->where('id',$pid)->first();
            if($parent){
                return static::create(array_merge($data,['parent_id' => $data['parent_id'],'path' => $parent->path.'-'.$data['parent_id']]));
            }
            
            throw new \Exception('找不到改父分类');
        }   
        return static::create($data);
    }


    public function edit($id,$data){
        $model = $this->find($id);
        $model->fill($data)->save();
        return $model;
    }

    
}