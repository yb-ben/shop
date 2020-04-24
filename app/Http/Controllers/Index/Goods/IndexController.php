<?php


namespace App\Http\Controllers\Index\Goods;

use App\Http\Controllers\Controller;
use App\Http\Logic\Goods\IndexLogic;
use App\Model\Goods;
use App\Utils\Response;
use Illuminate\Http\Request;

class IndexController extends Controller{


    public function lists(Request $request){

        $data = Goods::searchStatus(1)
            ->searchCategory($request->input('cate',0))
            ->withImage()
            ->select(['id','image_id','price','line_price','title'])
            ->orderby('sort')
        ->simplePaginate($request->input('limit',10))
        ;

        return Response::api([
            'data' => $data->getCollection()->append(['image_url'])->makeHidden(['image','image_id']),
            'current_page' => $data->currentPage(),
            'more' => $data->hasMorePages()
        ]);
    }


    public function detail(Request $request,$id){

        $goods = Goods::with(
            [
                'gallery'=>function($query){
                $query->withImage();
                },
                'content',
                'specs'
            ])
            ->searchStatus(1)
            ->select(['id', 'title', 'image_id', 'price', 'line_price', 'cate_id', 'count', 'spu', 'content_id'])
            ->findOrFail($id)
            ->append(['image_url'])
            ->makeHidden(['image'])
        ;
        $goods->gallery && $goods->gallery->append(['image_url'])->makeHidden(['image']);
        $goods->specs && $goods->specs->makeHidden(['cast','sell','lock','goods_id','weight','code','key','updated_at','deleted_at','created_at']);

        return Response::api($goods);
    }


}
