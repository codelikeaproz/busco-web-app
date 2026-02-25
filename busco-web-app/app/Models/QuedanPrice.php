<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Stores weekly Quedan price postings, including active and archived records.
 */
class QuedanPrice extends Model
{
    protected $fillable = [
        'price',
        'trading_date',
        'weekending_date',
        'difference',
        'trend',
        'price_subtext',
        'notes',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'difference' => 'decimal:2',
        'trading_date' => 'date',
        'weekending_date' => 'date',
    ];

    /**
     * Scope the current active Quedan record.
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Scope archived Quedan history ordered from newest to oldest.
     */
    public function scopeArchived($query)
    {
        return $query->where('status', self::STATUS_ARCHIVED)->orderByDesc('trading_date')->orderByDesc('id');
    }

    /**
     * Format the numeric Quedan price for UI display.
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'PHP ' . number_format((float) $this->price, 2);
    }

    /**
     * Return the CSS class associated with the current trend value.
     */
    public function getTrendClassAttribute(): string
    {
        return match ($this->trend) {
            self::TREND_UP => 'trend-up',
            self::TREND_DOWN => 'trend-down',
            self::TREND_NO_CHANGE => 'trend-neutral',
            default => 'trend-neutral',
        };
    }

    /**
     * Return a compact visual symbol for the current trend value.
     */
    public function getTrendIconAttribute(): string
    {
        return match ($this->trend) {
            self::TREND_UP => '^',
            self::TREND_DOWN => 'v',
            self::TREND_NO_CHANGE => '-',
            default => '-',
        };
    }

    public const STATUS_ACTIVE = 'active';
    public const STATUS_ARCHIVED = 'archived';

    public const TREND_UP = 'UP';
    public const TREND_DOWN = 'DOWN';
    public const TREND_NO_CHANGE = 'NO CHANGE';
}
