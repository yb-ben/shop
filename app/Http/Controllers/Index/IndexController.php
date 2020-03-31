<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use App\Utils\Response;

class IndexController extends Controller{



    public function index(){



        return Response::api();
    }
}