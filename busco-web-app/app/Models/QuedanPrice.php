<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeArchived($query)
    {
        return $query->where('status', self::STATUS_ARCHIVED)->orderByDesc('trading_date')->orderByDesc('id');
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'PHP ' . number_format((float) $this->price, 2);
    }

    public function getTrendClassAttribute(): string
    {
        return match ($this->trend) {
            self::TREND_UP => 'trend-up',
            self::TREND_DOWN => 'trend-down',
            self::TREND_NO_CHANGE => 'trend-neutral',
            default => 'trend-neutral',
        };
    }

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
