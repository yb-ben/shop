<?php

namespace App\Http\Controllers\Admin\Category;

use App\Http\Controllers\Controller;
use App\model\GoodsCategory;
use App\Utils\Format;
use Huyibin\Struct\Tree;
use Illuminate\Http\Request;

class IndexController extends Controller{


    //分类树
    public function tree(){

        $model = new GoodsCategory;
        $data = $model->orderby('sort')->select(['id','name','sort','parent_id','status'])->get()->toArray();
        $data = Tree::tree($data,[],'parent_id','children');
        
        return response()->json(Format::api(array_values($data)));
    }


    //添加分类
    public function add(Request $request){

        $data = $request->post();
        
        $model = new GoodsCategory;
        $model = $model->add($data);
        return response()->json(Format::api($model));
    }

    //编辑保存
    public function edit(Request $request,$id){

        $data = $request->post();
        $model = new GoodsCategory;
        $model = $model->edit($id,$data);
        return response()->json(Format::api($model));
    }

    //删除
    public function delete($id){

        if(GoodsCategory::where('parent_id',$id)->count()){
            return response()->json(Format::api());    
        }
        GoodsCategory::find($id)->delete();
        return response()->json(Format::api());    
    }

    //详情
    public function detail($id){

        $model = GoodsCategory::find($id);
        return response()->json(Format::api($model));    
    }
}