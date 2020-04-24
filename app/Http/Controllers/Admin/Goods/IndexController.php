<?php

namespace App\Http\Controllers\Admin\Goods;

use App\Http\Controllers\Controller;
use App\Http\Logic\Goods\IndexLogic;
use App\Http\Requests\Admin\Goods\GetGoodsList;
use App\Http\Requests\Admin\Goods\ModifyStatus;
use App\Http\Requests\Admin\Goods\StoreGoodsPost;
use App\Model\Goods;
use App\Model\GoodsAttr;
use App\Model\GoodsSpec;
use App\Model\GoodsValue;
use App\Utils\Format;
use App\Utils\Response;
use Illuminate\Http\Request;

class IndexController extends Controller
{

    /**
     * 添加商品
     *
     * @param Request $request
     * @return void
     */
    public function add(StoreGoodsPost $request)
    {


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
    public function edit(StoreGoodsPost $request)
    {

        $data = $request->validated();
        $logic = new IndexLogic;
        $logic->edit($data);
        return Response::api();
    }

    /**
     * 商品列表
     */
    public function list(GetGoodsList $request)
    {
        $data = $request->validated();

        $data = Goods::select(['id', 'title', 'price', 'line_price', 'count', 'image_id', 'status', 'updated_at'])
            ->withImage()
            ->searchStatus(isset($data['status']) ? $data['status'] : null)
            ->searchPrice(isset($data['price']) ? $data['price'] : null)
            ->searchCategory(isset($data['cate_id']) ? $data['cate_id'] : null)
            ->searchKw(isset($data['kw']) ? $data['kw'] : null)
            ->orderby('sort')
            ->orderby('updated_at', 'desc')
            ->paginate($request->input('limit', 10))

        ;

        $data->getCollection()
            ->append(['status_text','image_url'])
            ->makeHidden(['image']);
//
        $data = $data->toArray();
//        foreach ($data['data'] as &$item){
//            $item['price'] =Format::moneyHuman($item['price']);
//            $item['line_price'] = Format::moneyHuman($item['line_price']);
//        }

        return Response::api($data);
    }


    /**
     * 商品详情
     *
     * @param [type] $id
     * @return void
     */
    public function detail($id)
    {

        $goods = Goods::with([ 'gallery'=>function($query){$query->withImage();}, 'content', 'specs'])
            ->withImage()
            ->select(['id', 'title', 'image_id', 'status', 'price', 'line_price', 'cate_id', 'count', 'spu', 'content_id','limit','up_at','is_timing'])
            ->findOrFail($id)
            ->append(['image_url'])
            ->makeHidden(['image'])
        ;
        $goods->gallery && $goods->gallery->append(['image_url'])->makeHidden(['image']);

        $data = $goods->toArray();

//        $data['price'] = Format::moneyHuman($data['price']);
//        $data['line_price'] =Format::moneyHuman($data['line_price']);
//        if(!empty($data['specs'])){
//            foreach ($data['specs'] as &$spec){
//                $spec['price'] = Format::moneyHuman($spec['price']);
//                $spec['cast'] = Format::moneyHuman($spec['cast']);
//            }
//        }
        return Response::api($data);
    }


    //商品sku信息
    public function getSku($id)
    {

        $goodsSpecs = GoodsSpec::where('goods_id', $id)->get();
        $goodsAttr = GoodsAttr::where('goods_id', $id)->get();
        $goodsValues = GoodsValue::where('goods_id', $id)->get();

        return Response::api(['specs' => $goodsSpecs, 'attrs' => $goodsAttr, 'values' => $goodsValues]);
    }

    //上架
    public function takeUp(ModifyStatus $request)
    {
        $data = $request->validated();
        $logic = new IndexLogic;
        $logic->batchTakeUp($data['ids']);
        return Response::api();
    }

    //下架
    public function takeDown(ModifyStatus $request)
    {
        $data = $request->validated();
        $logic = new IndexLogic;
        $logic->batchTakeDown($data['ids']);
        return Response::api();

    }

    //删除
    public function delete(ModifyStatus $request)
    {
        $data = $request->validated();
        $logic = new IndexLogic;
        $logic->batchDelete($data['ids']);
        return Response::api();
    }
}
