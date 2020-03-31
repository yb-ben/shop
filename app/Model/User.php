<?php

namespace App\Model;


use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Base implements Authenticatable
{
    use  \Illuminate\Auth\Authenticatable,Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','phone'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function getDateFormat(){
        return time();
    }


    protected $casts =[
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'email_verified_at' => 'datetime:Y-m-d H:i:s',
    ];

    protected $table = 'users';



}
