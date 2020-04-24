<?php


namespace App\Model;


use Illuminate\Database\Eloquent\Builder;

class Payment extends Base
{

    protected $table ='payment';

    public $timestamps = false;

    protected static function booted()
    {
        static::addGlobalScope('status', function (Builder $builder) {
            $builder->where('status', 1);
        });
    }
}
