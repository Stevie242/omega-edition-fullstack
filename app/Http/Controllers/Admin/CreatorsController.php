<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class CreatorsController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('admin/Creators/Index');
    }

    public function show(string $creator): Response
    {
        return Inertia::render('admin/Creators/Show', [
            'creatorId' => $creator,
        ]);
    }
}
