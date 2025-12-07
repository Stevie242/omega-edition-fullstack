<?php

namespace App\Http\Controllers\Reader;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class SeriesController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('reader/Series/Index');
    }

    public function show(string $series): Response
    {
        return Inertia::render('reader/Series/Show', [
            'seriesId' => $series,
        ]);
    }
}
