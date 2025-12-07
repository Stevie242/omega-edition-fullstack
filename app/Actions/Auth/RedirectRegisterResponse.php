<?php

namespace App\Actions\Auth;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;

class RedirectRegisterResponse implements RegisterResponseContract
{
    public function toResponse($request): RedirectResponse|JsonResponse
    {
        $user = $request->user();

        $redirect = match (true) {
            $user?->role === 'admin' => route('admin.dashboard'),
            $user?->role === 'creator' => route('creator.dashboard'),
            default => route('reader.dashboard'),
        };

        if ($request->wantsJson()) {
            return new JsonResponse(['redirect' => $redirect], 201);
        }

        return redirect()->intended($redirect);
    }
}
