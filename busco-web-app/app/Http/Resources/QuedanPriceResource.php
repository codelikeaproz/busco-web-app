<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\QuedanPrice */
class QuedanPriceResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'formatted_price' => $this->formatted_price,
            'trading_date' => $this->trading_date?->toDateString(),
            'weekending_date' => $this->weekending_date?->toDateString(),
            'difference' => $this->difference !== null ? (float) $this->difference : null,
            'trend' => $this->trend,
            'trend_class' => $this->trend_class,
            'trend_icon' => $this->trend_icon,
            'price_subtext' => $this->price_subtext,
            'notes' => $this->notes,
        ];
    }
}
