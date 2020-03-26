<?php

namespace App\Model;

use App\Model\Base;
use Illuminate\Database\Eloquent\SoftDeletes;

class Goods extends Base{

    use SoftDeletes;

    protected $table = 'goods';
    
    protected $fillable = ['is_timing','up_at','limit','spu','content_id','title','price','file_id','spu','code','lock','how','main_image','line_price','sell','status','cate_id','sort','created_at','updated_at','deleted_at'];
   // protected $appends = ['status_text','updated_time'];
 
    

    public function getStatusTextAttribute(){
        $ret = '';
        switch($this->status){

            case 0:
                $ret = '待上架';
            break;

            case 1:
                $ret = '销售中';
            break;

            case 2:
                $ret = '已售罄';
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
        return $this->hasOne(GoodsContent::class,'id','content_id');
    }

    public function specs(){
        return $this->hasMany(GoodsSpec::class,'goods_id');
    }

    public function getSpuAttribute($value){
     
        $value = json_decode($value,JSON_OBJECT_AS_ARRAY);
        foreach($value as &$val){
            array_walk($val['values'],function(&$v){
                if(!empty($v['path'])){
                    $v['path_full'] = env('APP_URL').$v['path'];
                }
            });
            
        }
        return $value;
   
    }

    public function setSpuAttribute($value){
        
        $this->attributes['spu'] = json_encode( (empty($value)?[]:$value),JSON_UNESCAPED_UNICODE);
    }

    public function setLimitAttribute($value){
        $v = (empty($value)?null:$value);
        $this->attributes['limit'] = json_encode($v ,JSON_UNESCAPED_UNICODE);
    }

    

    public function getLimitAttribute($value){
        return  json_decode($value,JSON_OBJECT_AS_ARRAY);
    }




    public function scopeStatus($query,$status){
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
}