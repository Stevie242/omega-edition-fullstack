<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaService
{
    public function disk()
    {
        return Storage::disk(config('filesystems.images_disk', 'images'));
    }

    public function storeSeriesCover(UploadedFile $file, string $seriesSlug): string
    {
        $filename = 'cover-'.Str::random(8).'.'.$file->getClientOriginalExtension();
        return $this->disk()->putFileAs("series/{$seriesSlug}", $file, $filename);
    }

    public function storeSeriesHero(UploadedFile $file, string $seriesSlug): string
    {
        $filename = 'hero-'.Str::random(8).'.'.$file->getClientOriginalExtension();
        return $this->disk()->putFileAs("series/{$seriesSlug}", $file, $filename);
    }

    public function storeChapterPage(UploadedFile $file, string $seriesSlug, int $chapterNumber, int $order): string
    {
        $filename = 'page-'.$order.'-'.Str::random(6).'.'.$file->getClientOriginalExtension();
        return $this->disk()->putFileAs("series/{$seriesSlug}/chapters/{$chapterNumber}", $file, $filename);
    }

    public function delete(?string $path): void
    {
        if ($path) {
            $this->disk()->delete($path);
        }
    }

    public function url(?string $path): ?string
    {
        return $path ? $this->disk()->url($path) : null;
    }
}
