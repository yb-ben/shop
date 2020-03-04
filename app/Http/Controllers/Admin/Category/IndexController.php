<?php

namespace App\Http\Controllers\Admin\Category;

use App\Http\Controllers\Controller;
use App\model\GoodsCategory;
use Illuminate\Http\Request;

class IndexController extends Controller{


    //分类树
    public function tree(){

        $model = new GoodsCategory;
        $data = $model->select();
        return response()->json($data);
    }

    public function add(Request $request){

        $data = $request->post();
        $model = GoodsCategory::create($data,isset($data['pid'])?:0);
        return response()->json(['data'=>$model]);
    }


    public function edit(Request $request,$id){

        $data = $request->post();
        $model = GoodsCategory::edit($id,$data);
        return response()->json(['data'=> $model]);
    }

    public function delete($id){

        if(GoodsCategory::where('parent_id',$id)->count()){
            return response()->json([]);    
        }
        GoodsCategory::find($id)->delete();
        return response()->json([]);    
    }


    public function detail($id){

        $model = GoodsCategory::find($id);
        return response()->json(['data' =>$model]);    
    }
}