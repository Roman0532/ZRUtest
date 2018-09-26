<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfSameUsers
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
//        return $request->input('fromUser') === $request->input('toUser') ?
//            redirect()->back()->with('danger', 'Невозможно перевести денежные средства самому себе') :
        return $next($request);
    }
}
