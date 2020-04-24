<?php

namespace App\Model;

use App\Model\Base;
use App\Utils\Format;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodsSpec extends Base{

    use SoftDeletes;

    protected $table = 'goods_spec';
    protected $fillable = ['key','price','goods_id','sku','count','cast','code','sell','lock','weight','created_at','updated_at','deleted_at'];



    public function getSkuAttribute($value){
        return json_decode($value,JSON_OBJECT_AS_ARRAY);
    }

    public function setSkuAttribute($value){
        $this->attributes['sku'] = json_encode($value,JSON_UNESCAPED_UNICODE);
    }


    public function setPriceAttribute($value){
        $this->attributes['price'] = Format::moneyIntval($value);
    }

    public function setCastAttribute($value){
        $this->attributes['cast'] = Format::moneyIntval($value);
    }

    public function getSkuTextAttribute(){
        $sku = $this->sku;
        $p = null;
        $str = [];
        foreach($sku as $k => $v){
            if(preg_match('/^k(\d+)$/',$k,$match) && !empty($match)){
                $str[] = $v .':'. $sku[ 'v'.$match[1]];

            }
//
//            if($k{0} === 'k' && mb_strpos($k,'_',1) === -1){
//                $p = mb_substr($k,1);
//                $str[] = $v . ':' . $sku["v$p"];
//            }
        }
        return implode(' ',$str);
    }




    public function getPriceAttribute($value){
        return Format::moneyHuman($value);
    }

    public function getCastAttribute($value){
        return Format::moneyHuman($value);
    }

}
