<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        foreach ($roles as $role) {
            // Check if user has this role
            if ($user->hasRole($role)) {
                return $next($request);
            }
        }

        // If no role matched, check if user is admin
        if ($user->hasRole('admin')) {
            return $next($request);
        }

        return redirect()->back()->with('error', 'You do not have permission to access this page.');
    }
}
