<?php


namespace App\Model;


use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Base
{

    use SoftDeletes;

    protected $table = 'order';

    public $incrementing = false;

    protected $keyType = 'string';

    //默认值
    protected $attributes = [

    ];

    protected $fillable = [
        'id',
        'user_id' ,
        'user_name',
        'out_no',
        'status',
        'method',
        'total_price' ,
        'discount_price',
        'price',
        'freight',
        'ref_type',
        'expired_at',
        'shipping_name',
        'shipping_code',
        'paid_at',
        'cancelled_at',
        'expired_at',
        'finished_at',
        'refund_at',
        'closed_at',
        'consign_at',
        'user_deleted',
        'has_comment',
        'created_at','updated_at','deleted_at'
    ];

    public function shipping(){
        return $this->hasOne(OrderShipping::class,'order_id');
    }

    public function orderGoods(){
        return $this->hasMany(OrderGoods::class,'order_id');
    }
}
