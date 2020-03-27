<?php


namespace App\Http\Controllers\Index\Goods;

use App\Http\Controllers\Controller;
use App\Model\Goods;
use App\Utils\Response;

class IndexController extends Controller{


    public function list(){

        $data = Goods::where('status',1)
        ->select()
        ->orderby('create_time','desc')
        ->paginate();

        return Response::api($data);
    }

    

    public function buy(){
        
    }
}