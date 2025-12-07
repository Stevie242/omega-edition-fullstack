<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Series;
use App\Models\Tag;
use App\Services\MediaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class SeriesController extends Controller
{
    public function index(Request $request): Response
    {
        $userId = Auth::id();

        $series = Series::query()
            ->with('tags')
            ->withCount('chapters')
            ->where('creator_id', $userId)
            ->when($request->string('status'), fn ($q, $status) => $q->where('status', $status))
            ->when($request->string('type'), fn ($q, $type) => $q->where('type', $type))
            ->when($request->string('search'), function ($q, $search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('title', 'like', "%{$search}%")
                        ->orWhere('synopsis', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $series->getCollection()->transform(fn (Series $serie) => $this->mapSeries($serie));

        return Inertia::render('creator/Series/Index', [
            'series' => $series->items(),
            'meta' => [
                'current_page' => $series->currentPage(),
                'last_page' => $series->lastPage(),
                'total' => $series->total(),
            ],
        ]);
    }

    public function create(): Response
    {
        $tags = Tag::orderBy('name')
            ->pluck('name', 'id')
            ->map(fn (string $name, string $id) => ['id' => $id, 'label' => $name])
            ->values();

        return Inertia::render('creator/Series/Create', [
            'tags' => $tags,
        ]);
    }

    public function store(Request $request, MediaService $media): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::in(['manga', 'webtoon'])],
            'status' => ['required', Rule::in(['ongoing', 'hiatus', 'completed'])],
            'frequency' => ['required', Rule::in(['weekly', 'biweekly', 'monthly', 'irregular'])],
            'is_one_shot' => ['sometimes', 'boolean'],
            'synopsis' => ['nullable', 'string'],
            'cover' => ['nullable', 'image'],
            'hero' => ['nullable', 'image'],
            'tags' => ['array'],
            'tags.*' => ['string', 'exists:tags,id'],
        ]);

        $slug = Str::slug($data['title']);
        if (Series::where('slug', $slug)->exists()) {
            $slug .= '-'.Str::random(5);
        }

        $series = Series::create([
            'creator_id' => Auth::id(),
            'title' => $data['title'],
            'slug' => $slug,
            'type' => $data['type'],
            'status' => $data['status'],
            'format' => !empty($data['is_one_shot']) ? 'oneshot' : 'series',
            'frequency' => $data['frequency'],
            'synopsis' => $data['synopsis'] ?? null,
        ]);

        if ($request->hasFile('cover')) {
            $series->cover_path = $media->storeImage($request->file('cover'), 'covers');
        }

        if ($request->hasFile('hero')) {
            $series->hero_path = $media->storeImage($request->file('hero'), 'heroes');
        }

        $series->tags()->sync($data['tags'] ?? []);

        $series->save();

        return redirect('/creator/series')->with('success', 'Série créée.');
    }

    public function show(string $series): Response
    {
        $serie = Series::with(['tags'])
            ->withCount('chapters')
            ->findOrFail($series);

        $chapters = Chapter::query()
            ->where('series_id', $serie->id)
            ->with(['pages' => fn ($q) => $q->orderBy('order')->limit(1)])
            ->orderByDesc('number')
            ->paginate(12);

        $mappedChapters = $chapters->getCollection()->map(function (Chapter $chapter) {
            $previewPath = optional($chapter->pages->first())->path;
            return [
                'id' => $chapter->id,
                'title' => $chapter->title,
                'number' => $chapter->number,
                'status' => $chapter->status,
                'scheduledFor' => optional($chapter->scheduled_for)?->toDateTimeString(),
                'publishedAt' => optional($chapter->published_at)?->toDateTimeString(),
                'pages' => $chapter->pages_count,
                'views' => $chapter->views,
                'likes' => $chapter->likes_count,
                'dislikes' => $chapter->dislikes_count,
                'rating' => $chapter->rating,
                'preview' => $previewPath ? url(Storage()->disk(config('filesystems.images_disk', 'images'))->url($previewPath)) : null,
            ];
        });

        return Inertia::render('creator/Series/Show', [
            'seriesId' => $serie->id,
            'series' => $this->mapSeries($serie),
            'chapters' => $mappedChapters,
            'chaptersMeta' => [
                'current_page' => $chapters->currentPage(),
                'last_page' => $chapters->lastPage(),
                'total' => $chapters->total(),
            ],
        ]);
    }

    public function edit(string $series): Response
    {
        $serie = Series::with('tags')->findOrFail($series);

        return Inertia::render('creator/Series/Edit', [
            'seriesId' => $serie->id,
            'series' => $this->mapSeries($serie),
            'tags' => Tag::orderBy('name')->get(['id', 'name', 'slug']),
        ]);
    }

    public function update(string $series, Request $request, MediaService $media): RedirectResponse
    {
        $serie = Series::findOrFail($series);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:manga,webtoon'],
            'status' => ['required', 'in:ongoing,hiatus,completed'],
            'format' => ['required', 'in:oneshot,miniseries,series'],
            'frequency' => ['required', 'in:weekly,biweekly,monthly,irregular'],
            'language' => ['required', 'string', 'max:8'],
            'synopsis' => ['nullable', 'string'],
            'cover' => ['nullable', 'image'],
            'hero' => ['nullable', 'image'],
            'tags' => ['array'],
            'tags.*' => ['integer', 'exists:tags,id'],
        ]);

        $serie->fill([
            'title' => $data['title'],
            'type' => $data['type'],
            'status' => $data['status'],
            'format' => $data['format'],
            'frequency' => $data['frequency'],
            'language' => $data['language'],
            'synopsis' => $data['synopsis'] ?? null,
        ]);

        if ($request->hasFile('cover')) {
            $media->delete($serie->cover_path);
            $serie->cover_path = $media->storeSeriesCover($request->file('cover'), $serie->slug);
        }

        if ($request->hasFile('hero')) {
            $media->delete($serie->hero_path);
            $serie->hero_path = $media->storeSeriesHero($request->file('hero'), $serie->slug);
        }

        if (!empty($data['tags'])) {
            $serie->tags()->sync($data['tags']);
        } else {
            $serie->tags()->detach();
        }

        $serie->save();

        return redirect()->route('creator.series.edit', $serie->id)->with('success', 'Série mise à jour.');
    }

    private function mapSeries(Series $series): array
    {
        $cover = $series->cover_path ? Storage()->disk(config('filesystems.images_disk', 'images'))->url($series->cover_path) : null;
        $hero = $series->hero_path ? Storage()->disk(config('filesystems.images_disk', 'images'))->url($series->hero_path) : null;

        $nextRelease = Chapter::where('series_id', $series->id)
            ->where('status', 'scheduled')
            ->min('scheduled_for');

        $views = Chapter::where('series_id', $series->id)->sum('views');

        return [
            'id' => $series->id,
            'title' => $series->title,
            'type' => $series->type,
            'status' => $series->status,
            'format' => $series->format,
            'frequency' => $series->frequency,
            'language' => $series->language,
            'synopsis' => $series->synopsis,
            'cover' => $cover ? url($cover) : null,
            'hero' => $hero ? url($hero) : null,
            'likes' => $series->likes_count,
            'dislikes' => $series->dislikes_count,
            'rating' => $series->rating,
            'chapters' => $series->chapters_count ?? 0,
            'views' => $views,
            'nextRelease' => $nextRelease ? (string) $nextRelease : null,
            'tags' => $series->tags->pluck('name'),
            'updatedAt' => $series->updated_at?->toDateTimeString(),
        ];
    }
}
