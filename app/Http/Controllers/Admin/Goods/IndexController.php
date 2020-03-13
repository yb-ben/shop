<?php

namespace App\Http\Controllers\Admin\Goods;

use App\Http\Controllers\Controller;
use App\Http\Logic\Goods\IndexLogic;
use App\Http\Requests\StoreGoodsPost;
use App\Model\Goods;
use App\Utils\Response;
use Illuminate\Http\Request;

class IndexController extends Controller{

    /**
     * 添加商品
     *
     * @param Request $request
     * @return void
     */
    public function add(Request $request){

        // $validatedData = $request->validate([
        //     'title' => 'bail|required|max:30',
        //     'main_image' => 'required',
        //     'mImage' => 'required|array|max:9',
        //     'price' => 'required|numeric|min:0.01',
        //     'line_price' => 'required|numeric|min:0.01',
        //     'cateId' => 'required',
        //     'attrValues' => '',
        //     'sku' => ''
        // ]);

        //$data =$request->validated();
        $data = $request->post();
        $logic = new IndexLogic;
        $logic->add($data);
        return Response::api();
    }

    /**
     * 商品列表
     */
    public function list(Request $request){

        $data = Goods::select(['id','title','price','line_price','count','status','main_image','updated_at'])
        ->orderby('sort')
        ->orderby('updated_at','desc')
        ->paginate($request->input('limit',10,'intval'))
        ;
        foreach($data as $item){
            $item->setAppends(['main_image_full']);
        }
        $data = $data->toArray();

        return Response::api($data);
    }

}