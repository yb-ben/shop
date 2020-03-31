<?php


namespace App\Http\Controllers\Admin\Goods;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Goods\StoreAttributePost;
use App\Http\Requests\Admin\Goods\StoreValuesPost;
use App\Model\GoodsAttr;
use App\Model\GoodsValue;
use App\Utils\Response;
use Illuminate\Http\Request;

class SpuController extends Controller{

    //属性列表
    public function attrs(){
        $attrs= GoodsAttr::select('id as k_id','name as k')->get();
        return Response::api( $attrs);
    }

    //添加属性
    public function addAttr(StoreAttributePost $request){
        
        $data = $request->validated();
        $attr = GoodsAttr::create([
            'name' =>$data['name']
        ]);
        return Response::api($attr->id);
    }

    //属性值列表
    public function values(Request $request){
 
        $values = GoodsValue::where(function($query)use($request){
            $id = $request->input('_id',0);
            if($request->has('batch')){
                $ids = explode(',',$id);
                $query->whereIn('attr_id',$ids);
            }else{
                $query->where('attr_id',$id);
            }

        })
        ->select('id as v_id','val as v')
        ->get();
        return Response::api($values);
    }

    //添加属性值
    public function addValue(StoreValuesPost $request){
        $data = $request->validated();
        $values = GoodsValue::create([
            'val' => $data['val'],
            'attr_id' =>$data['_id'],
        ]);
        return Response::api($values->id);
    }

}