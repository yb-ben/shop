<?php

namespace App\Http\Controllers\Admin\Category;

use App\Http\Controllers\Controller;
use App\Model\CategoryAttr;
use App\Model\GoodsCategory;
use App\Utils\Response;
use Illuminate\Http\Request;

class AttrController extends Controller
{

    //属性列表
    function list($id) {

        $cate = GoodsCategory::select(['id'])->findOrFail($id);

        $cateList = CategoryAttr::with(['values'])->where('cate_id', $cate->id)->where('status',1)->select(['id','name'])->get();

        return Response::api($cateList);
    }

    //父属性列表
    public function parentAttr($cate_id)
    {

        $cate = GoodsCategory::select(['id', 'parent_id', 'path'])
            ->find($cate_id);
        $cateList = CategoryAttr::where('cate_id', 'in', $cate->path_array)
            ->select(['id', 'name'])
            ->groupby('name')
            ->get();

        return Response::api($cateList);
    }

    //添加
    public function add(Request $request)
    {
        $data = $request->post();
        $model = CategoryAttr::create($data);
        return Response::api($model);
    }

    //编辑
    public function edit(Request $request,$id)
    {
        $data = $request->post();
        $model=  CategoryAttr::find($id);
        $model->fill($data)->save();
        return Response::api($model);
    }

    //删除
    public function delete($id)
    {

    }
}
