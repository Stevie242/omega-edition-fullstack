<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class CatalogController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('admin/Catalog/Index');
    }

    public function show(string $series): Response
    {
        return Inertia::render('admin/Catalog/Show', [
            'seriesId' => $series,
        ]);
    }
}
