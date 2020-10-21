<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class RedirectAfterVerification
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (
            !$request->user() ||
            ($request->user() instanceof MustVerifyEmail &&
                $request->user()->hasVerifiedEmail() &&
                false == socialUser($request->user()->id) &&
                getUserRegisterationSteps($request->user()->id) < 1)
        ) {
            return redirect()->route('photo_upload');
        }
        return $next($request);
    }
}