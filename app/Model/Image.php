<?php


namespace App\Model;


use Illuminate\Database\Eloquent\Builder;

class Image extends UploadFile
{

    protected $appends = ['url_full'];



    protected static function booted()
    {
        static::addGlobalScope('filetype', function (Builder $builder) {
            $builder->where('filetype', 1);
        });
    }


    public function scopeBaseSelect(Builder $builder,$select =['id','fn','filetype','url','size'] ){
        $builder->select($select);
    }

    //图片全路径
    public function getUrlFullAttribute(){
        return env('APP_IMAGE_SERVER').$this->url;
    }
}
