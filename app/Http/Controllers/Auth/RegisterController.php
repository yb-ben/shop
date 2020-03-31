<?php


namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Logic\User\IndexLogic;
use App\Http\Requests\User\Register;
use App\Utils\Response;
use Illuminate\Http\Request;

class RegisterController extends Controller{



    public function register(Register $request){


        $data = $request->validated();
        
        $logic= new IndexLogic;
        $user = $logic->register($data);
        return Response::api();
    }


    

}