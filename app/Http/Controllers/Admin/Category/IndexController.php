<?php

namespace App\Http\Controllers\Admin\Category;

use App\Http\Controllers\Controller;
use App\Http\Logic\Category\IndexLogic;
use App\Model\GoodsCategory;
use App\Utils\Response;
use Huyibin\Struct\Tree;
use Illuminate\Http\Request;

class IndexController extends Controller{


    //分类树
    public function tree(Request $request){

        $model = new GoodsCategory;
        $data = $model->orderby('sort')->select(['id','name','sort','parent_id','status','path'])->get()->toArray();
        if('tree' === $request->input('type','tree')){
            $data = Tree::tree($data,[],'parent_id','children');
        }
        return Response::api(array_values($data));
    }


    //添加分类
    public function add(Request $request){

        $data = $request->post();
       // dump($data);exit;
        $model = new IndexLogic;
        $model = $model->add($data);
        return Response::api($model);
    }

    //编辑保存
    public function edit(Request $request,$id){

        $data = $request->post();
        $model = new IndexLogic;
        $model = $model->edit($id,$data);
        return Response::api($model);
    }

    //删除
    public function delete($id){

        if(GoodsCategory::where('parent_id',$id)->count()){
            return Response::apiError();    
        }
        GoodsCategory::find($id)->delete();
        return Response::api();    
    }

    //详情
    public function detail($id){

        $model = GoodsCategory::find($id);
        return Response::api($model);    
    }
}