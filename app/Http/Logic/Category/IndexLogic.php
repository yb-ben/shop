<?php


namespace App\Http\Logic\Category;

use App\Model\CategoryAttr;
use App\Model\GoodsCategory;
use Exception;
use Illuminate\Support\Facades\DB;

class IndexLogic{


    //新增分类
    public function add($data){

        return DB::transaction(function()use($data){


            if(!empty($data['parent_id'])){
                $parent = GoodsCategory::where('id',$data['parent_id'])->first();
                if($parent){
                    $cate =  GoodsCategory::create(array_merge($data,['parent_id' => $data['parent_id'],'path' => $parent->path.'-'.$data['parent_id']]));

                }
            }else{
                $cate = GoodsCategory::create($data);
            }
            if(!empty($data['attrs'])){
                $cate->attrs()->createMany($data['attrs']);
            }
            return $cate;

        });

    }

    //修改分类
    public function edit($id,$data){
        return DB::transaction(function()use($id,$data){
            $model = GoodsCategory::find($id);
            if(!$model){
                throw new Exception('找不到该分类');
            }
            $model->name = $data['name'];
            $model->image_id = $data['image_id'];
            $model->status = $data['status'];
            $model->sort = $data['sort'];
            $model->isDirty() && $model->save();
            return $model;
        });

    }

}
