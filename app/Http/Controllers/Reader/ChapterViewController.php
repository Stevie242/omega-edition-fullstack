<?php

namespace App\Http\Controllers\Reader;

use App\Http\Controllers\Controller;
use App\Services\ViewTrackingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChapterViewController extends Controller
{
    public function store(Request $request, ViewTrackingService $service): JsonResponse
    {
        $validated = $request->validate([
            'chapter_id' => ['required', 'string'],
            'series_id' => ['nullable', 'string'],
            'creator_id' => ['nullable', 'string'],
            'duration_seconds' => ['required', 'integer', 'min:0'],
            'completion_ratio' => ['required', 'integer', 'min:0', 'max:100'],
            'meta' => ['nullable', 'array'],
        ]);

        $counted = $service->recordView(
            $request->user(),
            $validated['chapter_id'],
            $validated['series_id'] ?? null,
            $validated['creator_id'] ?? null,
            $validated['duration_seconds'],
            $validated['completion_ratio'],
            $validated['meta'] ?? [],
        );

        return response()->json(['counted' => $counted]);
    }
}
