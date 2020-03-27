<?php

namespace App\Providers;

use App\Auth\AdminGuard;
use App\Auth\AdminProvider;
use App\Model\Goods;
use App\Observers\GoodsObserver;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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


   
}
