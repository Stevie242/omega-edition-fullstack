<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureReaderOnboarded
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user?->role !== 'reader') {
            abort(403);
        }

        if ($request->routeIs('reader.onboarding*')) {
            return $next($request);
        }

        $profile = $user->readerProfile()->first();

        if (!$profile || $profile->is_completed !== true) {
            return redirect()->route('reader.onboarding');
        }

        return $next($request);
    }
}
