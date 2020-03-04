<?php

use Illuminate\Support\Facades\Route;
/**
 * API VERSION V1.0
 */

Route::group(['prefix'=>'admin','namespace'=>'Admin'],function(){


    Route::group(['prefix' => 'category','namespace'=>'Category'],function(){
        //商品分类
        Route::get('/tree','IndexController@tree');
    
    });
});
