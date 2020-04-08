<?php

namespace App\Http\Controllers;

use App\Model\Region;
use Huyibin\Sms\Sms;
use Huyibin\Struct\Tree;
use Illuminate\Support\Facades\Redis;

class TestController extends Controller{



    public function index(){


        $code = app('VCode')->generateAndStore('15917861851',6);
        dump(session()->all());
        dd($code);

       // return  Sms::sendVerificationCode('15917861851',$code);

    }


    public function check($phoneNumber,$code){

        return app('VCode')->check($phoneNumber,$code);
    }


    public function exportRegion(){

        $data= Region::select(['id','pid','name','shortname','level'])->get()->toArray();
        $data = Tree::tree($data,[],'pid');
        file_put_contents('region.json',json_encode($data[0]['nodes'],JSON_UNESCAPED_UNICODE));
    }
}
