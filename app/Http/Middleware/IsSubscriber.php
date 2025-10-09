<?php


namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class IsSubscriber
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
        $user = $request->user();

        // Kullanıcının abone olup olmadığını kontrol et
        if (!$user || !$user->isSubscriber()) {
            return response()->json([
                "message" => "Bu işlemi yapmak için abone olmanız gerekmektedir.",
                "error" => "subscription_required"
            ], 403);
        }

        return $next($request);
    }
}
