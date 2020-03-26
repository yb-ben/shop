<?php

namespace App\Model\Scope;


trait GoodsScope{


    

    public function scopeSearchStatus($query,$status){
        switch($status){
            
            case 0:
                //未上架
                return $query->where('status',0)
                    ->orWhere(function($query){
                        $query->where('status',1)
                            ->where('up_at','>',time())
                        ;
                    })
                ;
            break;
            
            case 1:
                //已上架
               return $query->where('status',1)
                    ->where('up_at','<',time());
            break;

            case 2:
               return $query->where('status',2);
            break;
        }
        return $query;
    }


    public function scopeSearchPrice($query,$price){
        if(empty($price)){
            return $query;
        }
        if(!empty($price[0])){
            $query->where('price','>=',$price[0]);
        }
        if(!empty($price[1])){
            $query->where('price','<=',$price[1]);
        }
        return $query;
    }

    public function scopeSearchCategory($query, $cate_id){
        return empty($cate_id)?$query: $query->where('cate_id',$cate_id);
    }

    public function scopeSearchKw($query, $kw){
        return empty($kw)?$query: $query->where('title','like',"%$kw%");
    }
}