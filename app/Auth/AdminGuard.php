<?php

namespace App\Auth;

use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;

class AdminGuard implements Guard{

    use GuardHelpers;

    protected $user = null;


    protected $provider ;

    protected $request;

    protected $inputKey;

    protected $storageKey;

    protected $lastAttempted;

    public function __construct(UserProvider $provider,Request $request)
    {
        $this->require = $request;
        $this->provider = $provider;
        $this->inputKey = 'Authorization';
        $this->storageKey = 'api_token';
    }

     

  
    /**
     * Get the currently authenticated user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function user(){
        if(!is_null($this->user)){
            return $this->user;
        }
        $user = null;
        $token = $this->getTokenForRequest();
        if(!empty($token)){
            $user = $this->provider->retrieveByCredentials(
                [$this->storageKey => $token]
            );
        }
        return $this->user = $user;
    }


    /**
     * Validate a user's credentials.
     *
     * @param  array  $credentials
     * @return bool
     */
    public function validate(array $credentials = []){
        if(empty($credentials[$this->inputKey])){
            return false;
        }
        $credentials = [$this->storageKey => $credentials[$this->inputKey]];
        $this->lastAttempted = $user = $this->provider->retrieveByCredentials($credentials);
        return $this->hasValidCredentials($user,$credentials);
    }


    protected function hasValidCredentials($user,$credentials){
        return !is_null($user) && $this->provider->validateCredentials($user,$credentials);
    }


 

    public function getTokenForRequest(){

        $token = $this->request->header($this->inputKey);
        return $token;
    }
}