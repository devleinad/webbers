<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class RedirectAfterCategorySelection
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
                getUserRegisterationSteps($request->user()->id) < 2)
        ) {
            return redirect()->route('user_category_choice');
        }
        return $next($request);
    }
}