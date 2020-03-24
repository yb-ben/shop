<?php

namespace App\Observers;

use App\Model\Goods;

class GoodsObserver
{
    /**
     * Handle the goods "created" event.
     *
     * @param  \App\Model\Goods  $goods
     * @return void
     */
    public function created(Goods $goods)
    {
        //
    }


    public function saved(Goods $goods){


    }


    public function retrieved(Goods $goods){
        
        // if($goods->status === 0 && $goods->up_at <= time() ){
        //     //定时上架
        //     $goods->status = 1;
        //     $goods->save();
        // }
    }


    /**
     * Handle the goods "updated" event.
     *
     * @param  \App\Model\Goods  $goods
     * @return void
     */
    public function updated(Goods $goods)
    {
        //
    }

    /**
     * Handle the goods "deleted" event.
     *
     * @param  \App\Model\Goods  $goods
     * @return void
     */
    public function deleted(Goods $goods)
    {
        //
    }

    /**
     * Handle the goods "restored" event.
     *
     * @param  \App\Model\Goods  $goods
     * @return void
     */
    public function restored(Goods $goods)
    {
        //
    }

    /**
     * Handle the goods "force deleted" event.
     *
     * @param  \App\Model\Goods  $goods
     * @return void
     */
    public function forceDeleted(Goods $goods)
    {
        //
    }
}
