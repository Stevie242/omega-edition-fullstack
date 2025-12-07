<?php

namespace App\Http\Controllers\Reader;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    public function favorites(): Response
    {
        return Inertia::render('reader/Favorites');
    }

    public function history(): Response
    {
        return Inertia::render('reader/History');
    }
}
