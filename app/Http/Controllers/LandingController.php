<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class LandingController extends Controller
{
    public function home(): Response
    {
        return Inertia::render('public/Home');
    }

    public function about(): Response
    {
        return Inertia::render('public/About');
    }

    public function privacy(): Response
    {
        return Inertia::render('public/Privacy');
    }

    public function terms(): Response
    {
        return Inertia::render('public/Terms');
    }
}
