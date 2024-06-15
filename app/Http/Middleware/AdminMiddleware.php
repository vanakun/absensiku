<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => $request->getClientIp(),
        ]);
        if (Auth::check() && Auth::user() && Auth::user()->name === 'admin') {
            return $next($request);
        }

        abort(403, 'Forbidden Access'); // Redirect jika bukan admin
    }
   
}
