<?php


namespace App\Model;


class OrderShipping extends Base
{

    protected $table = 'order_shipping';

    protected $primaryKey = 'order_id';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;


    protected $fillable = [

        'order_id',
        'name',
        'phone',
        'address',
        'area_code'
    ];
}
