<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->is_active || ! $user->canManageContent()) {
            if (! $user) {
                return redirect()->route('admin.login')->with('error', 'Please sign in to continue.');
            }

            abort(403, 'You do not have permission to access the admin panel.');
        }

        return $next($request);
    }
}
