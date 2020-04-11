<?php

use Illuminate\Support\Facades\Route;




Route::group(['namespace' => 'user','prefix' => 'user'], function () {

    Route::post('login', 'LoginController@login'); //登录

    Route::group(['middleware' => ['testAuth:test']], function () {

        Route::get('logout', 'LoginController@logout');//登出
        Route::get('info', 'IndexController@info');//个人信息

    });

});
