<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class NewsImageStorage
{
    public function disk(): string
    {
        $disk = (string) config('news.image_disk', 'public');

        if ($disk === 'cloudinary' && ! config('filesystems.disks.cloudinary.url')) {
            return 'public';
        }

        return $disk;
    }

    public function store(UploadedFile $file): string
    {
        $disk = $this->disk();
        $folder = $disk === 'cloudinary' ? 'news' : 'news';

        return $file->store($folder, $disk);
    }

    public function url(string $reference): string
    {
        if ($this->isRemoteUrl($reference)) {
            return $reference;
        }

        $disk = $this->diskForReference($reference);

        if ($disk === 'cloudinary') {
            return Storage::disk('cloudinary')->url($reference);
        }

        if (Storage::disk('public')->exists($reference)) {
            return asset('storage/' . ltrim($reference, '/'));
        }

        return asset('images/no-image.svg');
    }

    public function delete(string $reference): void
    {
        if ($reference === '') {
            return;
        }

        if ($this->isRemoteUrl($reference)) {
            $this->deleteCloudinaryUrl($reference);

            return;
        }

        $disk = $this->diskForReference($reference);

        if ($disk === 'cloudinary' && Storage::disk('cloudinary')->exists($reference)) {
            Storage::disk('cloudinary')->delete($reference);

            return;
        }

        if (Storage::disk('public')->exists($reference)) {
            Storage::disk('public')->delete($reference);
        }
    }

    public function exists(string $reference): bool
    {
        if ($reference === '') {
            return false;
        }

        if ($this->isRemoteUrl($reference)) {
            return true;
        }

        return Storage::disk($this->diskForReference($reference))->exists($reference);
    }

    protected function diskForReference(string $reference): string
    {
        if (str_starts_with($reference, 'news/') && Storage::disk('public')->exists($reference)) {
            return 'public';
        }

        if (Storage::disk('cloudinary')->exists($reference)) {
            return 'cloudinary';
        }

        return $this->disk();
    }

    protected function isRemoteUrl(string $reference): bool
    {
        return (bool) preg_match('/^https?:\/\//i', $reference);
    }

    protected function deleteCloudinaryUrl(string $url): void
    {
        if (! config('filesystems.disks.cloudinary.url')) {
            return;
        }

        $path = parse_url($url, PHP_URL_PATH);
        if (! is_string($path) || $path === '') {
            return;
        }

        $publicId = preg_replace('#^/image/upload/(?:v\d+/)?#', '', $path);
        if (! is_string($publicId) || $publicId === '') {
            return;
        }

        $publicId = preg_replace('/\.[^.]+$/', '', $publicId);

        if (Storage::disk('cloudinary')->exists($publicId)) {
            Storage::disk('cloudinary')->delete($publicId);
        }
    }
}
