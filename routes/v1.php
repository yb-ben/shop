<?php

use Illuminate\Support\Facades\Route;
/**
 * API VERSION V1.0
 */

Route::group(['prefix'=>'admin','namespace'=>'Admin'],function(){


    Route::group(['prefix' => 'category','namespace'=>'Category'],function(){
        //商品分类
        Route::get('/tree','IndexController@tree');//树形
        Route::post('/add','IndexController@add');//添加
        Route::post('/edit/{id}','IndexController@edit');//修改
        Route::delete('/delete/{id}','IndexController@delete');//删除
        Route::get('/detail/{id}','IndexController@detail');

        Route::get('/attrs/{id}','AttrController@list');//分类下属性
    });


    Route::group(['prefix'=>'goods','namespace' => 'Goods'],function(){
        //商品管理
        Route::post('/add','IndexController@add');//添加商品
        Route::get('/list','IndexController@list');//商品列表
        Route::get('/detail/{id}','IndexController@detail');//商品详情
        Route::post('edit','IndexController@edit');//修改商品
    });

    Route::group(['prefix' => 'upload','namespace'=>'Upload'],function(){
        //文件上传
        Route::post('/uploadImage','IndexController@uploadImage');//图片上传
    });
});
