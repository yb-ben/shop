<?php


namespace App\Http\Logic\User;


use App\Model\UserAddr;

class AddrLogic
{


    public function save($data,$id = null){
        if($id){
            $addr = UserAddr::where('user_id',$data['user_id'])->findOrFail($id);
            $addr->fill($data);
            $addr->isDirty() && $addr->save();
        }else{
            $addr = UserAddr::create($data);
        }
        return $addr;
    }
}
