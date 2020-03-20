<?php

namespace App\Model;

use App\Model\Base;
use Illuminate\Database\Eloquent\SoftDeletes;

class Goods extends Base{

    use SoftDeletes;

    protected $table = 'goods';
    
    protected $fillable = ['spu','content_id','title','price','file_id','spu','code','lock','how','main_image','line_price','sell','status','cate_id','sort','created_at','updated_at','deleted_at'];
   // protected $appends = ['status_text','updated_time'];
 
    

    public function getStatusTextAttribute(){
        $ret = '';
        switch($this->status){

            case 0:
                $ret = '待上架';
            break;

            case 1:
                $ret = '上架中';
            break;

            case 2:
                $ret = '已下架';
            break;

        }
        return $this->attributes['status_text'] = $ret;
    }

    public function getUpdatedTimeAttribute(){
        return $this->attributes['updated_time'] = strtotime($this->updated_at);
    }

    public function getMainImageFullAttribute(){
        return env('APP_URL').$this->main_image;
    }


    public function category(){
        return $this->belongsTo(GoodsCategory::class,'cate_id');
    }

    public function gallery(){
        return $this->hasMany(GoodsGallery::class,'goods_id');
    }

    public function content(){
        return $this->hasOne(GoodsContent::class,'content_id');
    }

    public function specs(){
        return $this->hasMany(GoodsSpec::class,'goods_id');
    }

  
}