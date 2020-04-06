<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{


    public function index(){

        return Auth::id();

//        return 'ok';
    }
}
