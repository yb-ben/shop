<?php

namespace App\Auth;

use App\Model\Admin;
use Illuminate\Contracts\Auth\UserProvider;

class AdminProvider implements UserProvider{


    public function retrieveById($identifier)
    {
        return app(Admin::class)::getUserByGuId($identifier);
    }

    public function retrieveByToken($identifier, $token)
    {
        return null;
    }

    public function updateRememberToken(\Illuminate\Contracts\Auth\Authenticatable $user, $token)
    {
        return true;
    }

    public function retrieveByCredentials(array $credentials)
    {
        if(!isset($credentials['api_token'])){
            return null;
        }
        return app(Admin::class)::getUserByToken($credentials['api_token']);
    }

    public function validateCredentials(\Illuminate\Contracts\Auth\Authenticatable $user, array $credentials)
    {
        if(!isset($credentials['api_token'])){
            return false;
        }
        return true;
    }
}