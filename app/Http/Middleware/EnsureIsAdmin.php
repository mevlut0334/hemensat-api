<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureIsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            return redirect()->route('admin.login')
                ->withErrors(['email' => 'Bu sayfaya erişim yetkiniz yok.']);
        }

        return $next($request);
    }
}
