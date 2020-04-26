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
    Route::get('exportRegion','TestController@exportRegion');
});



Route::get('/sms/vcode','VerificationCodeController@code');//消息验证码

Route::group(['namespace'=>'Payment'],function(){
    Route::group(['prefix' =>'alipay'],function(){
        Route::get('/notify','AlipayController@notify');//支付宝回调
        Route::post('/wap','AlipayController@wap');//手机网站支付

    });

    Route::group(['prefix'=>'payment'],function(){
       Route::get('/methods','IndexController@methods');//支付方式
    });
});
