<?php


namespace App\Model;


use Illuminate\Database\Eloquent\Model;

class UserAddr extends Model
{

    protected $table = 'user_addr';

    protected $fillable = [
        'name','phone','user_id','addr_detail','addr_full','province_id','city_id','county_id','town_id','lat','lng','default'
    ];

    public $timestamps = false;


    public function scopeUser($query,$user_id){
        return $query->where('user_id',$user_id);
    }

    public function setDefaultAttribute($value){
       $this->attributes['default'] = $value? time():0;
    }
}
