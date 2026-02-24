<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class JobOpening extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'department',
        'location',
        'employment_type',
        'status',
        'application_email',
        'posted_at',
        'deadline_at',
        'summary',
        'description',
        'qualifications',
        'responsibilities',
    ];

    protected $casts = [
        'posted_at' => 'date',
        'deadline_at' => 'date',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $job): void {
            if (! $job->slug) {
                $job->slug = static::generateUniqueSlug((string) $job->title);
            }
        });

        static::updating(function (self $job): void {
            if ($job->isDirty('title')) {
                $job->slug = static::generateUniqueSlug((string) $job->title, $job->id);
            }
        });
    }

    public function scopePubliclyOpen($query)
    {
        return $query->where('status', self::STATUS_OPEN);
    }

    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_OPEN => 'Now Hiring',
            self::STATUS_HIRED => 'Hired',
            self::STATUS_CLOSED => 'Closed',
            self::STATUS_DRAFT => 'Draft',
            default => Str::headline((string) $this->status),
        };
    }

    public function getShortDescriptionAttribute(): string
    {
        $source = $this->summary ?: $this->description;

        return Str::limit(trim(strip_tags((string) $source)), 140);
    }

    protected static function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'job-opening';
        $slug = $base;
        $suffix = 2;

        while (static::query()
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->where('slug', $slug)
            ->exists()) {
            $slug = $base . '-' . $suffix;
            $suffix++;
        }

        return $slug;
    }

    public const STATUS_OPEN = 'open';
    public const STATUS_HIRED = 'hired';
    public const STATUS_CLOSED = 'closed';
    public const STATUS_DRAFT = 'draft';

    public const STATUSES = [
        self::STATUS_OPEN,
        self::STATUS_HIRED,
        self::STATUS_CLOSED,
        self::STATUS_DRAFT,
    ];

    public const EMPLOYMENT_TYPES = [
        'Full-time',
        'Part-time',
        'Contractual',
        'Seasonal',
    ];
}
