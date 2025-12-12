<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCreatorOnboarded
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user?->role !== 'creator') {
            abort(403);
        }

        if ($request->routeIs('creator.onboarding*')) {
            return $next($request);
        }

        $profile = $user->creatorProfile()->first();

        if (!$profile || $profile->is_completed !== true) {
            return redirect()->route('creator.onboarding');
        }

        return $next($request);
    }
}
