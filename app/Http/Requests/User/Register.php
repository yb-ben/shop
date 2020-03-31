<?php


namespace App\Http\Requests\User;

use App\Http\Requests\StoreBase;
use Huyibin\VerificationCode\Facade\VCode;

class Register extends StoreBase{



    public function rules(){

        return [
            'name' => ['required','string','min:6'],
            'password' => 'required|string',
            'phone' => 'required',
            'code' => [
                'required',
                function($attribute,$value,$fails){
                   if(!VCode::check( $this->phone,$value)){
                        $fails('invalid code!');
                   }
                }
            ],
            'email' => 'required'
        ];
    }


    public function message(){

        return [

        ];
    }

}