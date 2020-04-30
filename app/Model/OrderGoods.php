<?php


namespace App\Model;


use Illuminate\Database\Eloquent\SoftDeletes;

class OrderGoods extends Base
{

    use SoftDeletes;

    protected $table = 'order_goods';

    protected $fillable = [
        'order_id',
        'title',
        'image_url',
        'goods_id',
        'sku_text',
        'count',
        'price',
        'created_at','updated_at','deleted_at'
    ];

    public function getImageUrlAttribute($value){
        return env('APP_IMAGE_SERVER').$value;
    }
}
