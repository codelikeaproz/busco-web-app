<?php

namespace Database\Factories;

use App\Models\JobOpening;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<JobOpening>
 */
class JobOpeningFactory extends Factory
{
    protected $model = JobOpening::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->jobTitle();

        return [
            'title' => $title,
            'slug' => null,
            'department' => fake()->randomElement(['Operations', 'Human Resources', 'Finance', 'Agriculture']),
            'location' => 'Bukidnon, Philippines',
            'employment_type' => fake()->randomElement(JobOpening::EMPLOYMENT_TYPES),
            'status' => JobOpening::STATUS_DRAFT,
            'application_email' => 'hrd_buscosugarmill@yahoo.com',
            'posted_at' => now()->toDateString(),
            'deadline_at' => null,
            'summary' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'qualifications' => fake()->paragraph(),
            'responsibilities' => fake()->paragraph(),
        ];
    }
}
