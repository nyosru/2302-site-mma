<?php

namespace App\Http\Middleware;

use App\Helpers\Helper;
use App\Models\Token;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class VerifyToken
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     * @throws AccessDeniedHttpException
     */
    public function handle(Request $request, Closure $next)
    {
        $ip = (isset($_GET['proxied_ip'])) ? $_GET['proxied_ip'] : request()->ip();
        Log::info("VerifyToken: ip=$ip");

        if (config('auth.check_token')) {
            $token = $request->bearerToken();
            Log::info("VerifyToken: token=$token");
            if (!$token) {
                throw new AccessDeniedHttpException();
            }
            try {
                /** @var Token $eToken */
                $eToken = Token::where('token', $token)->firstOrFail();
                if (!empty($eToken->ip)) {
                    Log::info("$ip:".$eToken->ip);
                    if (!Helper::ipInRange($ip, $eToken->ip)) {
                        throw new AccessDeniedHttpException();
                    }
                }
                // Update last_used_at field
                $eToken->last_used_at = Carbon::now();
                $eToken->save();
            } catch (\Exception $e) {
                Log::info('VerifyToken: err='.$e->getMessage());
                throw new AccessDeniedHttpException();
            }
        }

        return $next($request);
    }
}
