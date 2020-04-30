<?php


namespace App\Http\Controllers\Index\User;


use App\Http\Controllers\Controller;
use App\Model\Order;
use App\Utils\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{


    public function info(){
        $user= Auth::user();
        $user->name;
        return Response::api([
            'name'=>$user->name,
            'id'=>$user->id,
            'avatar' => 'http://shop.com:8777/image/user_default_avatar.jpg']);
    }



}
