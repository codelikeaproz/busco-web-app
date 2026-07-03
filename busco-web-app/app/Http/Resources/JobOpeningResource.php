<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\JobOpening */
class JobOpeningResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'slug' => $this->slug,
            'title' => $this->title,
            'department' => $this->department,
            'location' => $this->location,
            'employment_type' => $this->employment_type,
            'short_description' => $this->short_description,
            'posted_at' => $this->posted_at?->toDateString(),
            'deadline_at' => $this->deadline_at?->toDateString(),
        ];
    }
}
