<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

// JobOpening model for the job openings table.
// Used by both the public careers pages and the admin hiring module.
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

    // Generate and maintain a unique slug from the job title.
    protected static function booted(): void
    {
        static::creating(function (self $job): void {
            if (! $job->slug) {
                $job->slug = static::generateUniqueSlug((string) $job->title);
            }
        });

        // Regenerate the slug when the title changes.
        static::updating(function (self $job): void {
            if ($job->isDirty('title')) {
                $job->slug = static::generateUniqueSlug((string) $job->title, $job->id);
            }
        });
    }

    // Scope to filter job openings visible on the public careers page.
    public function scopePubliclyOpen($query)
    {
        return $query->where('status', self::STATUS_OPEN);
    }

    // Scope to filter job openings by status
    // Scope to filter job openings by admin-selected status.
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    // Get the route key name for the model
    // Use slug route model binding for `/careers/{jobOpening}` URLs.
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // Get the status label for the job opening
    // Return a human-friendly label for job status badges.
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

    // Get the short description for the job opening
    // Return a shortened plain-text summary for listing cards.
    public function getShortDescriptionAttribute(): string
    {
        $source = $this->summary ?: $this->description;

        return Str::limit(trim(strip_tags((string) $source)), 140);
    }
    // Generate a unique slug for the job opening
    // Build a unique slug and append a suffix when a conflict exists.
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
    // Constants for the statuses of the job openings
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

    // Constants for the employment types of the job openings
    public const EMPLOYMENT_TYPES = [
        'Full-time',
        'Part-time',
        'Contractual',
        'Seasonal',
    ];
}
