<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\JobOpening */
class JobOpeningDetailResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            ...JobOpeningResource::make($this->resource)->toArray($request),
            'summary' => $this->summary,
            'description' => $this->description,
            'qualifications' => $this->qualifications,
            'responsibilities' => $this->responsibilities,
            'application_email' => $this->application_email,
        ];
    }
}
