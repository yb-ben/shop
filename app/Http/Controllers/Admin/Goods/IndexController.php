<?php

namespace App\Http\Controllers\Admin\Goods;

use App\Http\Controllers\Controller;
use App\Http\Logic\Goods\IndexLogic;
use App\Http\Requests\Goods\GetGoodsList;
use App\Http\Requests\Goods\ModifyStatus;
use App\Http\Requests\Goods\StoreGoodsPost;
use App\Model\Goods;
use App\Model\GoodsAttr;
use App\Model\GoodsSpec;
use App\Model\GoodsValue;
use App\Utils\Response;
use Illuminate\Http\Request;

class IndexController extends Controller{

    /**
     * 添加商品
     *
     * @param Request $request
     * @return void
     */
    public function add(StoreGoodsPost $request){

      

        $data = $request->validated();
       
         $logic = new IndexLogic;
         $logic->add($data);
        return Response::api($data);
    }

    /**
     * 修改商品
     *
     * @return void
     */
    public function edit(StoreGoodsPost $request){

        $data = $request->validated();
        $logic = new IndexLogic;
        $logic->edit($data);
        return Response::api();
    }

    /**
     * 商品列表
     */
    public function list(GetGoodsList $request){
        $data = $request->validated();

        $data = Goods::select(['id','title','price','line_price','count','status','main_image','updated_at'])
        ->status($data['status']?:null)
        ->price($data['price']?:null)
        ->category($data['cate_id']?:null)
        ->kw($data['kw']?:null)
        ->orderby('sort')
        ->orderby('updated_at','desc')
        ->paginate($request->input('limit',10))
        ;
        foreach($data as $item){
            $item->setAppends(['main_image_full','status_text']);
        }
        $data = $data->toArray();

        return Response::api($data);
    }


    /**
     * 商品详情
     *
     * @param [type] $id
     * @return void
     */
    public function detail($id){
            
        $goodsLogic = new IndexLogic;

        $goods = $goodsLogic->detail($id);
        $goods->setAppends(['main_image_full']);
        foreach($goods->gallery as $gallery){
            $gallery->setAppends(['url_full']);
        }

        return Response::api($goods);
    }


    //商品sku信息
    public function getSku($id){

        $goodsSpecs = GoodsSpec::where('goods_id',$id)->get();
        $goodsAttr = GoodsAttr::where('goods_id',$id)->get();
        $goodsValues = GoodsValue::where('goods_id',$id)->get();

        return Response::api(['specs' => $goodsSpecs,'attrs'=>$goodsAttr ,'values'=>$goodsValues]);
    }

    //上架
    public function takeUp(ModifyStatus $request){
        $data = $request->validated();
        $logic = new IndexLogic;
        $logic->batchTakeUp($data['ids']);
        return Response::api();
    }

    //下架
    public function takeDown(ModifyStatus $request){
        $data = $request->validated();
        $logic = new IndexLogic;
        $logic->batchTakeDown($data['ids']);
        return Response::api();
   
    }

    //删除
    public function delete(ModifyStatus $request){
        $data = $request->validated();
        $logic = new IndexLogic;
        $logic->batchDelete($data['ids']);
        return Response::api();
    }
}