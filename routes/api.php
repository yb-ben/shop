<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group([ 'prefix' =>'webhooks'],function(){

    Route::post('/onpush','WebHooksController@onPush');
});



Route::group(['prefix' => 'test'], function () {

    Route::get('/', 'TestController@index');
});

Route::group(['namespace' => 'api'],function(){
    Route::post('login','LoginController@login');
    Route::get("index",'IndexController@index')
        ->middleware(['testAuth:test']);
    Route::get('logout','LoginController@logout');
});
