<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

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
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'images' => 'array',
        'deleted_at' => 'datetime',
    ];

    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    public function getImageUrlAttribute(): string
    {
        $primaryPath = $this->primary_image_path;

        if ($primaryPath && file_exists(storage_path('app/public/' . $primaryPath))) {
            return asset('storage/' . $primaryPath);
        }

        return asset('images/no-image.jpg');
    }

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
                'url' => file_exists(storage_path('app/public/' . $path))
                    ? asset('storage/' . $path)
                    : asset('images/no-image.jpg'),
            ];
        }

        return $images;
    }

    public function getExcerptAttribute(): string
    {
        $source = trim((string) ($this->sub_title ?: $this->content));

        return Str::limit(strip_tags($source), 160);
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
