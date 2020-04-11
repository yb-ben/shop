<?php


namespace App\Http\Controllers\Index\User;


use App\Http\Controllers\Controller;
use App\Utils\Response;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{


    public function info(){
        return Response::api(Auth::id());
    }
}
