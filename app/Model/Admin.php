<?php

namespace App\Model;


use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends  Authenticatable {

    use SoftDeletes,Notifiable;

    protected $table = 'admin';

    protected $fillable = ['username','password','email','remember_token'];

    protected $primaryKey = 'id';




    public function getDateFormat(){
        return time();
    }


    protected $casts =[
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s'
    ];
}
