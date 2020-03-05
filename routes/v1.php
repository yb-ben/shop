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
    });
});
