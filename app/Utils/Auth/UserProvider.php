<?php


namespace App\Utils\Auth;


use Huyibin\VerificationCode\Facade\VCode;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Redis\Connections\Connection;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

class UserProvider implements \Illuminate\Contracts\Auth\UserProvider
{


    protected $phoneKey = 'phone';

    protected $codeKey = 'code';

    protected $passwordKey = 'password';

    protected $sessionLifeTime = 3600;

    protected $delay = 300;

    protected $redisConnection;

    /**
     * The Eloquent user model.
     *
     * @var string
     */
    protected $model;

    public function __construct(string $model,Connection $redisConnection)
    {
        $this->model = $model;
        $this->redisConnection = $redisConnection;
    }

    /**
     * 手机验证码验证 或 账号密码验证
     * @param array $credentials
     * @return UserContract|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|void|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        if(empty($credentials)
            || !isset($credentials[$this->phoneKey])
            ){
            return;
        }

        if(isset($credentials[$this->codeKey])){

            if(!VCode::check($credentials[$this->phoneKey],$credentials[$this->codeKey])){
                return;
            }
            return $this->newModelQuery()->where('phone',$credentials[$this->phoneKey])->first();

        }else if(isset($credentials[$this->passwordKey])){

            return $this->newModelQuery()
                ->where('phone',$credentials[$this->phoneKey])
                ->where('password',bcrypt($credentials[$this->passwordKey]))
                ->first();

        }
        return;

    }

    /**
     * id检索user
     * @param mixed $identifier
     * @return UserContract|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function retrieveById($identifier)
    {
        $model = $this->createModel();

        return $this->newModelQuery($model)
            ->where($model->getAuthIdentifierName(), $identifier)
            ->first();
    }

    /**
     *
     * @return mixed
     */
    public function createModel()
    {
        $class = '\\'.ltrim($this->model, '\\');

        return new $class;
    }

    /**
     * Get a new query builder for the model instance.
     *
     * @param  \Illuminate\Database\Eloquent\Model|null  $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function newModelQuery($model = null)
    {
        return is_null($model)
            ? $this->createModel()->newQuery()
            : $model->newQuery();
    }

    /**
     * 获取token对应的user
     * @param mixed $identifier
     * @param string $token
     * @return UserContract|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|void|null
     */
    public function retrieveByToken($identifier, $token)
    {
        $model = $this->createModel();

        $retrievedModel = $this->newModelQuery($model)
            ->where(
            $model->getAuthIdentifierName(), $identifier
        )->first();

        if (! $retrievedModel) {
            return;
        }

       $registed_at = Redis::zScore($this->getName($identifier),$token);

        if(is_null($registed_at)){
            return;
        }

        return $retrievedModel;

    }

    public function updateRememberToken(UserContract $user, $token)
    {
//        $user->setRememberToken($token);
//
//        $timestamps = $user->timestamps;
//
//        $user->timestamps = false;
//
//        $user->save();
//
//        $user->timestamps = $timestamps;
    }

    /**
     * 密码验证
     * @param UserContract $user
     * @param array $credentials
     * @return bool
     */
    public function validateCredentials(UserContract $user, array $credentials)
    {
        $plain = $credentials['password'];

        return (bcrypt($plain) === $user->getAuthPassword());
    }

    /**
     * put the token into the session set
     * @param $identifier
     * @param $token
     * @return integer
     */
    protected function registerToken($identifier,$token){
        $this->removeExpireToken($identifier);
        $ret = $this->redisConnection->multi();
            return $ret->zAdd($this->getTokenSetName($identifier),time(),$token)
            ->hMSet($this->getTokenName($token),['id'=>$identifier])
            ->expire($this->getTokenName($token),$this->sessionLifeTime)
            ->expire($this->getTokenSetName($identifier),$this->sessionLifeTime)
            ->exec()
        ;
    }





    /**
     * 创建新的token并注册
     * @param $identifier
     * @return bool|string
     */
    public function newToken($identifier){
        $token = $this->generateToken();
        $ret = $this->registerToken($identifier,$token);
        if($ret ){
            return $token;
        }
        return false;
    }

    /**
     * 创建token方法
     */
    protected function generateToken(){
        return Str::random(12);
    }


    /**
     * 获取id
     * @param $token
     * @return mixed
     */
    public function getIdentifierByToken($token){
        return $this->redisConnection->hGet($this->getTokenName($token),'id');
    }




    /**
     * 移除过期的token
     * @param $identifier
     */
    protected function removeExpireToken($identifier){
        $this->redisConnection->zRemRangeByScore($this->getTokenSetName($identifier),0,time()-$this->sessionLifeTime);
    }

    /**
     * 清除会话
     * @param $token
     */
    public function removeSession($token){
        if(empty($token))
            return;
        $identifier = $this->getIdentifierByToken($token);
        $this->redisConnection->zRem($this->getTokenSetName($identifier),$token);
        $this->redisConnection->del($this->getTokenName($token));
    }



    /**
     * 当前有效的token集
     * @param $identifier
     * @return string
     */
    protected function getTokenSetName($identifier){
        return 'sess_'.$identifier;
    }


    /**
     * token会话名
     * @param $token
     * @return string
     */
    protected function getTokenName($token){
        return 'sess_'.$token;
    }

    /**
     * token保活
     * @param $id
     * @param $token
     * @return bool
     */
    public function delay($id,$token){
        $tokenName = $this->getTokenName($token);
        $this->redisConnection->watch($tokenName);
        $ret = $this->redisConnection->multi();
            $ret->zAdd($token,time()+$this->sessionLifeTime);
            $ret->expire($tokenName,$this->sessionLifeTime);
            $ret->expire($this->getTokenSetName($id),$this->sessionLifeTime);

        return $ret->exec()?true:false;
    }
}
