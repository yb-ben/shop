<?php

namespace App\Model;

use App\Model\Base;
use App\Model\Relations\GoodsRelations;
use App\Model\Scope\GoodsScope;
use App\Utils\Format;
use Illuminate\Database\Eloquent\SoftDeletes;

class Goods extends Base{

    use SoftDeletes,GoodsScope,GoodsRelations,\App\Model\Relations\Image;

    protected $table = 'goods';

    protected $fillable = ['is_timing','up_at','limit','spu','content_id','title','price','image_id','spu','code','lock','how','line_price','sell','status','cate_id','sort','created_at','updated_at','deleted_at'];


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
        return  $ret;
    }

    public function getUpdatedTimeAttribute(){
        return $this->attributes['updated_time'] = strtotime($this->updated_at);
    }


    public function getPriceAttribute($value){
        return Format::moneyHuman($value);
    }

    public function getLinePriceAttribute($value){
        return Format::moneyHuman($value);
    }

    public function getSpuAttribute($value){

        $value = json_decode($value,JSON_OBJECT_AS_ARRAY);
//        foreach($value as &$val){
//            array_walk($val['values'],function(&$v){
//                if(!empty($v['image_url'])){
//                    $v['image_url'] = env('APP_IMAGE_SERVER').$v['image_url'];
//                }
//            });
//
//        }
        return $value;

    }

    public function setSpuAttribute($value){

        $this->attributes['spu'] = json_encode( (empty($value)?[]:$value),JSON_UNESCAPED_UNICODE);
    }

    public function setLimitAttribute($value){
        $v = (empty($value)?null:$value);
        $this->attributes['limit'] = json_encode($v ,JSON_UNESCAPED_UNICODE);
    }


    public function setPriceAttribute($value){
        $this->attributes['price'] = Format::moneyIntval($value);
    }

    public function setLinePriceAttribute($value){
        $this->attributes['line_price'] = Format::moneyIntval($value);
    }

    public function getLimitAttribute($value){
        return  json_decode($value,JSON_OBJECT_AS_ARRAY);
    }



}
