<?php

namespace App\Http\Controllers\Index\Cart;

use App\Http\Controllers\Controller;
use App\Http\Logic\Cart\IndexLogic;
use App\Http\Requests\Index\Cart\AddRequest;
use App\Http\Requests\Index\Cart\ModifyRequest;
use App\Http\Requests\Index\Cart\RemoveRequest;
use App\Model\Cart;
use App\Utils\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class IndexController extends Controller
{

    /**
     * 购物车列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request)
    {

        $carts = Cart::select(['id', 'goods_id', 'spec_id', 'count'])
            ->user(Auth::id())
            ->with(['spec'=>function($query){
               return $query->select(['id','sku','price']);
            },'goods'=>function($query){
                return $query->select(['id','title','image_id','status','price','line_price'])->withImage();
            }])
            ->simplePaginate($request->input('limit', 10));


        foreach ( $carts->getCollection() as $item){
            $item->goods->append(['image_url'])->makeHidden(['image','image_id']);
            $item->spec && $item->spec->append(['sku_text'])->makeHidden(['sku']);
        }

        return Response::api([
            'data' => $carts->getCollection(),
            'current_page' => $carts->currentPage(),
            'more' => $carts->hasMorePages()
        ]);
    }

    /**
     * 购物车数量
     * @return \Illuminate\Http\JsonResponse
     */
    public function count()
    {
        $count = Cart::user(Auth::id())->count();
        return Response::api($count);
    }

    /**
     * 加入购物车
     *
     * @param AddRequest $request
     * @return void
     */
    public function add(AddRequest $request)
    {
        $user = Auth::user();
        $data = $request->validated();
        $logic = new IndexLogic;
        $logic->addToCart($user->getAuthIdentifier(), $data['goods_id'], $data['count'], $data['spec_id']);
        return Response::api();
    }


    /**
     * 修改数量
     *
     * @param ModifyRequest $request
     * @return void
     */
    public function modify(ModifyRequest $request)
    {
        $data = $request->validated();
        $logic = new IndexLogic;
        $logic->modifyCount(Auth::id(), $data['cart_id'], $data['count']);
        return Response::api();
    }

    /**
     * 移出购物车
     *
     * @param RemoveRequest $request
     * @return void
     */
    public function remove(RemoveRequest $request)
    {
        $data = $request->validated();
        $logic = new IndexLogic;
        $logic->removeCartItem(Auth::id(), $data['ids']);
        return Response::api();
    }

    /**
     * * 计算购物车价格
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function calculate(Request $request){

        $ids = $request->input('ids',[]);
        $totalPrice = (new IndexLogic())->calculate($ids,Auth::id());
        return Response::api(['totalPrice'=>$totalPrice]);
    }


}
