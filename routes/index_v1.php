<?php

use Illuminate\Support\Facades\Route;




Route::group(['namespace' => 'User','prefix' => 'user'], function () {

    Route::post('login', 'LoginController@login'); //登录
    Route::post('register/code','LoginController@registerByCode');//验证码登录

    Route::group(['middleware' => ['testAuth:test']], function () {

        Route::get('logout', 'LoginController@logout');//登出
        Route::get('info', 'IndexController@info');//个人信息


        //收货地址
        Route::group(['prefix'=>'addr'],function(){
            Route::get('list','AddrController@list');
            Route::post('add','AddrController@add');
            Route::post('save/{id}','AddrController@save');
            Route::post('delete/{id}','AddrController@delete');
            Route::get('default','AddrController@default');
        });


        //订单
        Route::group(['prefix'=>'order'],function(){
           Route::get('someCount','OrderController@someCount');
        });
    });

});

//分类
Route::group(['namespace'=>'Category','prefix'=>'category'],function(){

    Route::get('tree','IndexController@tree');//分类列表
});

//商品
Route::group(['namespace'=>'Goods','prefix'=>'goods'],function(){
    Route::get('lists','IndexController@lists');
    Route::get('detail/{id}','IndexController@detail');

});

//购物车
Route::group(['namespace'=>'Cart','prefix' => 'cart','middleware' => ['testAuth:test']],function(){
    Route::post('add','IndexController@add');//添加购物车
    Route::post('modify','IndexController@modify');
    Route::post('remove','IndexController@remove');
    Route::get('list','IndexController@list');//购物车列表
    Route::post('calculate','IndexController@calculate');//计算当前选中价格
});

//订单相关
Route::group(['namespace'=>'Order','prefix'=>'order','middleware'=>['testAuth:test']],function(){
   Route::post('calculate','IndexController@calculate'); //计算价格并检查商品状态
    Route::post('submit','IndexController@submit');//购物车下单
    Route::get('payment/info','IndexController@getOrderPaymentInfo');//付款信息
    Route::get('list','IndexController@list');//订单列表
    Route::get('del/{id}','IndexController@del');//删除订单
});


