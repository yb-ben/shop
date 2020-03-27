<?php

namespace App\Http\Requests\Admin\Login;

use App\Http\Requests\StoreBase;

class LoginRequest extends StoreBase{



    public function rules(){

        return [
            'username' => 'required|string|max:16',
            'password' => 'required|string',
        ];
    }


    public function message(){

        return [

        ];
    }
}