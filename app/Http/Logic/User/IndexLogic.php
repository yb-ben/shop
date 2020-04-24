<?php

namespace App\Http\Logic\User;



use App\Utils\Auth\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
class IndexLogic{


    /**
     * 注册
     *
     * @param array $data
     * @return User
     */
    public function register(array $data):User{

        return DB::transaction(function()use(&$data){

             empty($data['name']) && $data['name'] = 'u'.Str::random(10);

            if(User::where('phone',$data['phone'])->select(['id'])->first()){
                throw new \Exception('该手机号已被占用');
            }
            if(User::where('name',$data['name'])->select(['id'])->first()){
                throw new \Exception('该昵称已被占用');
            }
            $user=  User::create($data);
            return $user;
        });
    }

}
