<?php

namespace App\Http\Middleware;

use App\Exceptions\AccountLockedException;
use App\Exceptions\UnauthorizedException;
use App\Libraries\Logger;
use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\MessageBag;

class Authenticate extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param $request
     * @param Closure $next
     * @param null $guard
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\RedirectResponse|mixed|\Symfony\Component\HttpFoundation\Response
     * @throw AccountLockedException
     * @throw OutOfPeriodException
     * @throw UnauthorizedException
     */
    public function handle($request, Closure $next, $guard = null)
    {
        logger('Auth Middleware: (guard type) ' . $guard);

        // 認証済みの場合抜ける
        if (auth($guard)->check()) {
            // 次の処理へ
            $user = auth($guard)->user();

            logger('Auth Middleware: logined.');
            $response = $next($request);

            return $response;
        }

        $url = $this->switchRedirect($guard);
        logger('Auth Middleware: Redirect to login page: '.$url);
        return redirect()->guest($url);
    }

    /**
     * @param $guard
     * @return string
     */
    private function switchRedirect($guard) {
        if ($guard) {
            // 認証がある場合
            $url = route(config('constant.loginRedirect.'.$guard));
        } else {
            // 認証が無い場合
            $url = route(config('constant.loginRedirect.default'));
        }
        return $url;
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }
}
