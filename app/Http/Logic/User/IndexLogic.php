<?php

namespace App\Http\Logic\User;

use App\Model\User;
use Huyibin\JWT;
use Huyibin\VerificationCode\Facade\VCode;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Tymon\JWTAuth\Contracts\Providers\JWT as ProvidersJWT;

class IndexLogic{


    /**
     * 注册
     *
     * @param array $data
     * @return User
     */
    public function register(array $data):User{


        return DB::transaction(function()use(&$data){

            if(User::where('name',$data['name'])->select(['id'])->first()){
                throw new \Exception('该昵称已被占用');
            }
            $data['password'] = bcrypt($data['password']);
            $user=  User::create($data);
            return $user;
        });
    }


    public function loginByCode($phone,$code){

        if(!VCode::check($phone,$code)){
            throw new \Exception('验证码不正确');
        }
        $user = User::where('phone',$phone)->first();
        $jwt = new JWT;
        $token = $jwt->authorizations(['id' => $user->id,'name' => $user->name]);
        $sign = mb_substr($token, mb_strrpos($token,'.'));
        Redis::zAdd('token_'.$user->id,time(),$sign);
        return $token;        
    }


    public function check($token){
        
        $jwt = new JWT;
        $tokenData = $jwt->verification($token);
        $time = time();
        if($time < $tokenData['exp'] && $tokenData['exp'] - $time < 300 ){
            $key = 'token_blacklist';
            
            $ret = Redis::zRank($key,mb_substr($token, mb_strrpos($token,'.')));
            
            $token = $jwt->authorizations($tokenData['data']);
            Redis::multi()
            ->zRemRangeByScore('token_'.$tokenData['sub'],0,$time)
            ->zAdd('token_'.$tokenData['sub'],)
            ;
        }
        return $token;
    }
}