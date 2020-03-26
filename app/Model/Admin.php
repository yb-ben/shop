<?php

namespace App\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as  Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable{

    use SoftDeletes,Notifiable;

    protected $table = 'admin';
    
    protected $fillable = ['username','password','email','remember_token','refresh_token'];

    protected $primaryKey = 'id';

    public function getAuthIdentifierName()
    {
        return $this->primaryKey;
    }

    public function getAuthIdentifier()
    {
        return $this->{$this->getAuthIdentifierName()};
    }

    public function getAuthPassword()
    {
        return '';
    }

    public function getRememberToken()
    {
        return '';
    }

    public function setRememberToken($value)
    {
        return true;
    }

    public function getRememberTokenName()
    {
        return '';
    }

}