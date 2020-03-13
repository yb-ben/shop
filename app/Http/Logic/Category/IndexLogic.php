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
            $pid = intval($data['parent_id']);
       
            if($pid){          
                $parent = GoodsCategory::where('id',$pid)->first();
                if($parent){
                    $cate =  GoodsCategory::create(array_merge($data,['parent_id' => $data['parent_id'],'path' => $parent->path.'-'.$data['parent_id']]));
                 
                }else{
                    throw new \Exception('找不到改父分类');
            
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
            $model = GoodsCategory::with(['attrs'])->find($id);
            if(!$model){
                throw new Exception('找不到该分类');
            }
            $model->fill($data)->save();
            return $model;
        });
       
    }

}