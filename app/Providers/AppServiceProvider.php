<?php

namespace App\Providers;

use App\Model\Goods;
use App\Observers\GoodsObserver;
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


    protected function sqlListener(){
        file_put_contents(__DIR__.'/sql_listener.log','');
        DB::listen(function($query){
            
            file_put_contents(__DIR__.'/sql_listener.log',$query->sql.PHP_EOL,FILE_APPEND);
        });
    }

    protected function modelObserver(){
        Goods::observe(GoodsObserver::class);
    } 
}
