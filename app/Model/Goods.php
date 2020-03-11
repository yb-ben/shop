<?php

namespace App\Model;

use App\Model\Base;
use Illuminate\Database\Eloquent\SoftDeletes;

class Goods extends Base{

    use SoftDeletes;

    protected $table = 'goods';
    
    protected $fillable = ['title','price','main_image','line_price','sell','spec_set','status','cate_id','sort','created_at','updated_at','deleted_at'];
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

}