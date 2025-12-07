<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class UsersController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('admin/Users/Index');
    }

    public function show(string $user): Response
    {
        return Inertia::render('admin/Users/Show', [
            'userId' => $user,
        ]);
    }

    public function edit(string $user): Response
    {
        return Inertia::render('admin/Users/Edit', [
            'userId' => $user,
        ]);
    }

    public function update(string $user): RedirectResponse
    {
        // Mock: replace with real update logic
        return redirect()->route('admin.users.edit', $user);
    }
}
