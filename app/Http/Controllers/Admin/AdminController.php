<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Utils\Response;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller{

    public function info(){

        $user = Auth::guard('api')->user();

        return Response::api([
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email
        ]);
    }


    public function resetPassword(){
        
    }
}