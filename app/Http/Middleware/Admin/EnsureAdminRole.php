<?php

namespace App\Http\Middleware\Admin;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Usage: ->middleware('admin.role:profiles') where "profiles" is a key of
 * config('admin.permissions'). Super admins always pass.
 */
class EnsureAdminRole
{
    public function handle(Request $request, Closure $next, string $area): Response
    {
        $admin = Auth::guard('admin')->user();

        if (! $admin || ! $admin->canAccess($area)) {
            abort(403, 'You do not have permission to access this section.');
        }

        return $next($request);
    }
}
