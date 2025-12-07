<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class SettingsController extends Controller
{
    public function profile(): Response
    {
        return Inertia::render('admin/settings/Profile');
    }

    public function appearance(): Response
    {
        return Inertia::render('admin/settings/Appearance');
    }

    public function password(): Response
    {
        return Inertia::render('admin/settings/Password');
    }

    public function twoFactor(): Response
    {
        return Inertia::render('admin/settings/TwoFactor');
    }

    public function security(): Response
    {
        return Inertia::render('admin/settings/Security');
    }
}
