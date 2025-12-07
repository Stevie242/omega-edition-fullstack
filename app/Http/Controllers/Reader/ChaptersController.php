<?php

namespace App\Http\Controllers\Reader;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class ChaptersController extends Controller
{
    public function show(string $chapter): Response
    {
        return Inertia::render('reader/Chapters/Show', [
            'chapterId' => $chapter,
        ]);
    }
}
