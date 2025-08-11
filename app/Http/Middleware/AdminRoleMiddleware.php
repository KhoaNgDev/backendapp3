<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class AdminRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
     public function handle(Request $request, Closure $next, string $requiredRole): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('admin.admins.login');
        }

        if (Str::lower($user->group_role) !== Str::lower($requiredRole)) {
            abort(403, 'Tài khoản không có quyền truy cập');
        }

        if ($user->is_active !== 'active') {
            abort(403, 'Tài khoản không hoạt động');
        }

        return $next($request);
    }
}
