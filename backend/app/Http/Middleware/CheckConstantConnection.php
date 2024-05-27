<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class CheckConstantConnection
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $access_token = $request->bearerToken();

        if (! $access_token) {
            abort(401, 'Unauthenticated');
        }

        $tokenHash = hash('sha256', explode('|', $access_token)[1]);

        $token = DB::table('personal_access_tokens')->where('token', $tokenHash)->first();

        if (! $token) {
            abort(401, 'Unauthenticated');
        }

        if ((new Carbon($token->updated_at))->diffInSeconds(Carbon::now(), false) > config('auth.max_age_session')) {
            abort(401, 'Unauthenticated');
        }
        if ((new Carbon($token->expires_at))->diffInSeconds(Carbon::now(), false) > 0) {
            abort(401, 'Unauthenticated');
        }

        return $next($request);
    }
}
