<?php


use Illuminate\Support\Facades\Route;


Route::group([],function(){


    Route::post('register','RegisterController@register');
    Route::post('login','LoginController@login');
});