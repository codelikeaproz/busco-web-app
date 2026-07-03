<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\News */
class NewsDetailResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            ...NewsResource::make($this->resource)->toArray($request),
            'content' => $this->content,
            'gallery_images' => $this->gallery_images,
        ];
    }
}
