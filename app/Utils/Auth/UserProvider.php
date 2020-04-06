<?php


namespace App\Utils\Auth;


use Huyibin\VerificationCode\Facade\VCode;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Support\Facades\Redis;

class UserProvider implements \Illuminate\Contracts\Auth\UserProvider
{


    protected $phoneKey = 'phone';

    protected $codeKey = 'code';

    protected $passwordKey = 'password';

    protected $sessionLifeTime = 3600;

    protected $delay = 300;

    /**
     * The Eloquent user model.
     *
     * @var string
     */
    protected $model;

    public function __construct($model)
    {
        $this->model = $model;
    }


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

    public function retrieveById($identifier)
    {
        $model = $this->createModel();

        return $this->newModelQuery($model)
            ->where($model->getAuthIdentifierName(), $identifier)
            ->first();
    }

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
        $user->setRememberToken($token);

        $timestamps = $user->timestamps;

        $user->timestamps = false;

        $user->save();

        $user->timestamps = $timestamps;
    }

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
        return Redis::multi()
            ->zAdd($this->getTokenSetName($identifier),time(),$token)
            ->hMSet($this->getTokenName($token),['id'=>$identifier])
            ->expire($this->getTokenName($token),$this->sessionLifeTime)
            ->exec()
        ;
    }





    /**
     * create a new token
     * @param $identifier
     * @return bool|string
     */
    public function newToken($identifier){
        $token = uniqid($identifier);
        $ret = $this->registerToken($identifier,$token);
        if($ret ){
            return $token;
        }
        return false;
    }

    /**
     * @param $token
     * @return mixed
     */
    public function getIdentifierByToken($token){

        return Redis::hGet($this->getTokenName($token),'id');
    }

    /**
     * @param $identifier
     * @return string
     */
    protected function getTokenSetName($identifier){
        return 'sess_'.$identifier;
    }

    /**
     * @param $token
     * @return string
     */
    protected function getTokenName($token){
        return 'sess_'.$token;
    }

    private function removeExpireToken($identifier){

        Redis::zRemRangeByRank($this->getTokenSetName($identifier),0,time()-$this->sessionLifeTime);
    }

}
