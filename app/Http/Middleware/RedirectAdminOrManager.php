<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectAdminOrManager
{
    /**
     * Redirect admin/manager users to admin dashboard instead of home
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();
            if ($user->isAdmin() || $user->isManager()) {
                if (!$request->is('admin/*', 'admin')) {
                    return redirect('/admin/dashboard');
                }
            } else if ($user->isEmployee()) {
                if ($request->is('admin/*', 'admin')) {
                    return redirect('/');
                }
            }
        }

        return $next($request);
    }
}
