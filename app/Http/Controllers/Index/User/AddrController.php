<?php


namespace App\Http\Controllers\Index\User;


use App\Http\Controllers\Controller;
use App\Http\Logic\User\AddrLogic;
use App\Http\Requests\User\Addr;
use App\Model\UserAddr;
use App\Utils\Response;
use Illuminate\Support\Facades\Auth;

class AddrController extends Controller
{

    public function list(){
        $data = UserAddr::select(['id','name','phone','addr_detail','addr_full','lat','lng','province_id','city_id','county_id','town_id'])
            ->user(Auth::id())
            ->orderby('default','desc')
            ->get()
        ;
        return Response::api($data);
    }

    public function add(Addr $request){
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        (new AddrLogic)->save($data);
        return Response::api();
    }


    public function save(Addr $request,$id){
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        (new AddrLogic)->save($data,$id);
        return Response::api();
    }

    public function delete($id){
        UserAddr::user(Auth::id())->delete($id);
        return Response::api();
    }


    public function default(){

        $addr = UserAddr::user(Auth::id())
            ->where('default','>',0)
            ->orderby('default','desc')
            ->first();
        return Response::api($addr);

    }


}
