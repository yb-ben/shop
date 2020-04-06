<?php


namespace App\Utils\Auth;


use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

use Illuminate\Auth\Authenticatable;
class User extends Model implements AuthenticatableContract
{

    use Authenticatable,Notifiable;

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
