<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class RateLimitServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        RateLimiter::for('global', function (Request $request) {
            return Limit::perMinute(5)
                ->by($request->user()?->id ?: $request->ip())
                ->response(function () {
                    return response()->json([
                        'message' => 'Thao tác quá nhiều lần. Vui lòng thử lại sau.'
                    ], 429);
                });
        });

        RateLimiter::for('auth', function (Request $request) {
            return Limit::perMinute(5)
                ->by($request->user()?->id ?: $request->ip())
                ->response(function () {
                    return response()->json([
                        'message' => 'Thao tác quá nhiều lần. Hãy thử lại sau.'
                    ], 429);
                });
        });

        RateLimiter::for('burst', function (Request $request) {
            return [
                Limit::perMinute(1000),
                Limit::perSecond(20),
            ];
        });

        RateLimiter::for('otp-login', function (Request $request) {
            return Limit::perMinute(3)
                ->by($request->ip())
                ->response(function () {
                    return response()->json([
                        'message' => 'Bạn đã yêu cầu OTP quá nhiều lần. Hãy thử lại sau vài phút.'
                    ], 429);
                });
        });

        RateLimiter::for('otp-lookup', function (Request $request) {
            return Limit::perMinute(3)
                ->by($request->ip())
                ->response(function () {
                    return response()->json([
                        'message' => 'Bạn đã tra cứu quá nhiều lần. Vui lòng thử lại sau.'
                    ], 429);
                });
        });
    }
}
