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


    public function scopeType($query,$type){
        switch ($type){
            case 2://待付款
                $query->where('status',0);
                break;

            case 3://待收货
                $query->where('status',8);
                break;

            case 4://已完成
                $query->where('status',4);
                break;

            case 5://已取消
                $query->where('status',3);
                break;
            default:
                break;
        }
    }


    public function getStatusTextAttribute(){
        $text = '';

        switch ($this->status){
            case 0:
                $text = '待付款';
                break;
            case 1:
                $text = '待发货';
                break;
            case 3:
                $text = '已取消';
                break;
            case 4:
                $text = '已完成';
                break;

            case 5:
                $text = '已退款';
                break;

            case 8:
                $text = '待收货';
                break;
        }
        return $text;
    }
}
