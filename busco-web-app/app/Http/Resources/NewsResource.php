<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\News */
class NewsResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'sub_title' => $this->sub_title,
            'excerpt' => $this->excerpt,
            'category' => $this->category,
            'image_url' => $this->image_url,
            'is_featured' => (bool) $this->is_featured,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
