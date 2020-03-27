<?php

namespace App\Model;


use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends  Authenticatable implements JWTSubject{

    use SoftDeletes,Notifiable;

    protected $table = 'admin';
    
    protected $fillable = ['username','password','email'];

    protected $primaryKey = 'id';


    public function getJWTIdentifier(){

        return $this->getKey();
    }
    

    public function getJWTCustomClaims(){
        return [
            'id' => $this->id,
            'usn' => $this->username,
        ];
        
    }


  

}