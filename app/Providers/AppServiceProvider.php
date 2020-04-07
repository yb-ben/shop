<?php

namespace App\Providers;


use App\Model\Goods;
use App\Observers\GoodsObserver;
use App\Utils\Auth\User;
use App\Utils\Auth\UserGuard;
use App\Utils\Auth\UserProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        $this->modelObserver();
        $this->sqlListener();
        $this->test();
    }

    /**
     * SQL监听器
     *
     * @return void
     */
    protected function sqlListener(){
        file_put_contents(__DIR__.'/sql_listener.log','');
        DB::listen(function($query){

            file_put_contents(__DIR__.'/sql_listener.log',$query->sql.PHP_EOL,FILE_APPEND);
        });
    }

    /**
     * 模型观察者注册
     *
     * @return void
     */
    protected function modelObserver(){
        Goods::observe(GoodsObserver::class);
    }


    public function test(){

        Auth::provider('test',function ($app,$config){
            return new UserProvider(User::class,Redis::connection()) ;
        });

        Auth::extend('test',function ($app, $name, array $config){

            return new UserGuard(
                'test',
                Auth::createUserProvider($config['provider']),
                $app->make('session')->driver(),
                $app->request
            );

        });


    }

}
