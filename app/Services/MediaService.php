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

    public function storeImage(UploadedFile $file, string $folder): string
    {
        $filename = Str::random(20).'.'.$file->getClientOriginalExtension();
        return $this->disk()->putFileAs($folder, $file, $filename);
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

    public function storeContent(string $content, string $folder, string $filename): ?string
    {
        $path = rtrim($folder, '/').'/'.$filename;
        $stored = $this->disk()->put($path, $content);

        return $stored ? $path : null;
    }

    public function url(?string $path): ?string
    {
        return $path ? $this->disk()->url($path) : null;
    }
}
