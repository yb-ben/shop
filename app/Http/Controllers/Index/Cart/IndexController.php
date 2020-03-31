<?php

namespace App\Http\Controllers\Index\Cart;

use App\Http\Controllers\Controller;
use App\Http\Logic\Cart\IndexLogic;
use App\Http\Requests\Index\Cart\AddRequest;
use App\Http\Requests\Index\Cart\ModifyRequest;
use App\Http\Requests\Index\Cart\RemoveRequest;
use App\Utils\Response;
use Illuminate\Support\Facades\Auth;
use App\Model\User;

class IndexController extends Controller{


    protected  $user;

    public function __construct()
    {
        $this->user = Auth::guard('api')->user();
    }

    /**
     * 加入购物车
     *
     * @param AddRequest $request
     * @return void
     */
    public function add(AddRequest $request){

        $data = $request->validated();
        app(); IndexLogic::addToCart($this->user->id,$data['goods_id'],$data['count'],$data['spec_id']);
        return Response::api();
    }


    /**
     * 修改数量
     *
     * @param ModifyRequest $request
     * @return void
     */
    public function modify(ModifyRequest $request){

        $data = $request->validated();
        IndexLogic::modifyCount($this->user->id,$data['card_id'],$data['count']);
        return Response::api();
    }

    /**
     * 移出购物车
     *
     * @param RemoveRequest $request
     * @return void
     */
    public function remove(RemoveRequest $request){

        $data = $request->validated();
        IndexLogic::removeCartItem($this->user->id,$data['ids']);
        return Response::api();
    }
}