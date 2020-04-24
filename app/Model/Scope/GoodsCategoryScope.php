<?php


namespace App\Model\Scope;


trait GoodsCategoryScope
{
    /**
     * 两层分类
     * @param $query
     * @param array $level
     * @return mixed
     */
    public function scopeLevel($query,$level = [0,1]){
       return $query->whereIn('level',$level);
    }
}
