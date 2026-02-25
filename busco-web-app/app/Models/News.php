<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

// News model for the news table.
// Handles article status, categories, and stored gallery image paths.
class News extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'sub_title',
        'content',
        'image',
        'images',
        'category',
        'status',
        'is_featured',
        'created_at',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'images' => 'array',
        'deleted_at' => 'datetime',
    ];

    // Scope to filter published articles shown on public pages.
    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    // Scope to filter homepage-featured articles.
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    // Scope to filter news by category
    public function scopeCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    // Get the image url for the news
    public function getImageUrlAttribute(): string
    {
        $primaryPath = $this->primary_image_path;

        return $this->resolveImageUrl($primaryPath);
    }

    // Get all the image paths for the news
    /**
     * @return list<string>
     */
    public function getAllImagePathsAttribute(): array
    {
        $paths = [];

        if ($this->image) {
            $paths[] = (string) $this->image;
        }

        foreach ((array) ($this->images ?? []) as $path) {
            if (is_string($path) && $path !== '') {
                $paths[] = $path;
            }
        }

        return array_values(array_unique($paths));
    }

    public function getPrimaryImagePathAttribute(): ?string
    {
        return $this->all_image_paths[0] ?? null;
    }

    /**
     * @return list<array{path:string,url:string}>
     */
    public function getGalleryImagesAttribute(): array
    {
        $images = [];

        foreach ($this->all_image_paths as $path) {
            $images[] = [
                'path' => $path,
                'url' => $this->resolveImageUrl($path),
            ];
        }

        return $images;
    }

    public function getExcerptAttribute(): string
    {
        $source = trim((string) ($this->sub_title ?: $this->content));

        return Str::limit(strip_tags($source), 160);
    }

    protected function resolveImageUrl(?string $path): string
    {
        if (is_string($path) && preg_match('/^https?:\/\//i', $path)) {
            return $path;
        }

        if (is_string($path) && $path !== '' && Storage::disk('public')->exists($path)) {
            return asset('storage/' . ltrim($path, '/'));
        }

        return asset('images/no-image.svg');
    }

    public const CATEGORIES = [
        'Announcements',
        'Achievements',
        'Events',
        'CSR / Community',
    ];

    public const STATUS_DRAFT = 'draft';
    public const STATUS_PUBLISHED = 'published';
}
