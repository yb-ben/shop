<?php


namespace App\Model\Relations;


use Illuminate\Database\Eloquent\Builder;

trait Image
{

    public function getImageUrlAttribute(){
        return $this->image? $this->image->url_full:null;
    }


    //图片关联
    public function scopeWithImage(Builder $builder){
        $builder->with(['image'=>function($query){
            $query->baseSelect();
        }]);
    }
    public function image(){

        return $this->belongsTo(\App\Model\Image::class,'image_id');
    }
}
