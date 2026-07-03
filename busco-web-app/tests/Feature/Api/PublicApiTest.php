<?php

namespace Tests\Feature\Api;

use App\Models\JobOpening;
use App\Models\News;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_api_returns_latest_news_and_quedan(): void
    {
        News::factory()->create([
            'status' => News::STATUS_PUBLISHED,
            'title' => 'Published Article',
        ]);

        $response = $this->getJson('/api/home');

        $response->assertOk()
            ->assertJsonStructure([
                'latest_news',
                'active_quedan',
                'previous_quedan',
            ]);
    }

    public function test_news_api_filters_by_category(): void
    {
        News::factory()->create([
            'status' => News::STATUS_PUBLISHED,
            'category' => 'Announcements',
            'title' => 'Announcement One',
        ]);
        News::factory()->create([
            'status' => News::STATUS_PUBLISHED,
            'category' => 'Events',
            'title' => 'Event One',
        ]);

        $response = $this->getJson('/api/news?category=Events');

        $response->assertOk();
        $this->assertCount(1, $response->json('data'));
        $this->assertSame('Event One', $response->json('data.0.title'));
    }

    public function test_news_show_returns_404_for_draft(): void
    {
        $article = News::factory()->create([
            'status' => News::STATUS_DRAFT,
        ]);

        $this->getJson('/api/news/' . $article->id)->assertNotFound();
    }

    public function test_careers_api_search_filters_results(): void
    {
        JobOpening::factory()->create([
            'status' => JobOpening::STATUS_OPEN,
            'title' => 'Mill Engineer',
            'department' => 'Operations',
        ]);
        JobOpening::factory()->create([
            'status' => JobOpening::STATUS_OPEN,
            'title' => 'HR Assistant',
            'department' => 'Human Resources',
        ]);

        $response = $this->getJson('/api/careers?search=Engineer');

        $response->assertOk();
        $this->assertSame(1, $response->json('meta.total'));
        $this->assertSame('Mill Engineer', $response->json('data.0.title'));
    }

    public function test_careers_show_uses_slug_binding(): void
    {
        $job = JobOpening::factory()->create([
            'status' => JobOpening::STATUS_OPEN,
            'title' => 'Quality Analyst',
        ]);

        $this->getJson('/api/careers/' . $job->slug)
            ->assertOk()
            ->assertJsonPath('data.title', 'Quality Analyst');
    }

    public function test_careers_api_hides_jobs_past_deadline(): void
    {
        $this->travelTo('2026-07-03');

        JobOpening::factory()->create([
            'status' => JobOpening::STATUS_OPEN,
            'title' => 'Active Role',
            'deadline_at' => '2026-07-10',
        ]);
        JobOpening::factory()->create([
            'status' => JobOpening::STATUS_OPEN,
            'title' => 'Expired Role',
            'deadline_at' => '2026-07-02',
        ]);

        $response = $this->getJson('/api/careers');

        $response->assertOk();
        $this->assertSame(1, $response->json('meta.total'));
        $this->assertSame('Active Role', $response->json('data.0.title'));
    }

    public function test_careers_api_includes_jobs_with_deadline_today_or_no_deadline(): void
    {
        $this->travelTo('2026-07-03');

        JobOpening::factory()->create([
            'status' => JobOpening::STATUS_OPEN,
            'title' => 'Closing Today',
            'deadline_at' => '2026-07-03',
        ]);
        JobOpening::factory()->create([
            'status' => JobOpening::STATUS_OPEN,
            'title' => 'Open Until Filled',
            'deadline_at' => null,
        ]);

        $this->getJson('/api/careers')
            ->assertOk()
            ->assertJsonPath('meta.total', 2);
    }

    public function test_careers_show_returns_404_for_past_deadline(): void
    {
        $this->travelTo('2026-07-03');

        $job = JobOpening::factory()->create([
            'status' => JobOpening::STATUS_OPEN,
            'title' => 'Expired Role',
            'deadline_at' => '2026-07-01',
        ]);

        $this->getJson('/api/careers/' . $job->slug)->assertNotFound();
    }
}
