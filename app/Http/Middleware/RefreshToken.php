<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\JWTException;

class RefreshToken extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->checkForToken($request);

        try{
            if($this->auth->parseToken()->authenticate()){
                return $next($request);
            }
            throw new UnauthorizedHttpException('jwt-auth','未登录');
        }catch(TokenExpiredException $exception){
            try{
                //刷新用户的token
                $token = $this->auth->refresh();
                Auth::guard('api')->onceUsingId($this->auth->manager()->getPayloadFactory()->buildClaimsCollection()->toPlainArray()['sub']);
            }catch(JWTException $exception){
                //refresh token也过期了，需要重新登录
                throw new UnauthorizedHttpException('jwt-auth', $exception->getMessage());
            }
        }
        return $this->setAuthenticationHeader($next($request), $token);
    }
}
