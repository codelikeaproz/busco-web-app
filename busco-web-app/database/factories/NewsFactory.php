<?php

namespace Database\Factories;

use App\Models\News;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<News>
 */
class NewsFactory extends Factory
{
    protected $model = News::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'sub_title' => fake()->optional()->sentence(),
            'content' => fake()->paragraphs(3, true),
            'image' => null,
            'images' => null,
            'category' => fake()->randomElement(News::CATEGORIES),
            'status' => News::STATUS_DRAFT,
            'is_featured' => false,
        ];
    }
}
