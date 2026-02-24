<?php

namespace Tests\Feature;

use App\Models\News;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class NewsCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_complete_news_crud_cycle_with_publish_toggle_and_restore(): void
    {
        Storage::fake('public');

        $admin = $this->adminUser();

        $createResponse = $this->actingAs($admin)->post(route('admin.news.store'), [
            'title' => 'BUSCO Test Article',
            'content' => 'Initial content for testing the news CRUD workflow.',
            'category' => News::CATEGORIES[0],
            'status' => News::STATUS_DRAFT,
            'is_featured' => '1',
            'gallery_images' => [
                UploadedFile::fake()->image('news-image.jpg', 1200, 800),
            ],
        ]);

        $createResponse->assertRedirect(route('admin.news.index'));

        $article = News::query()->firstOrFail();
        $this->assertSame(News::STATUS_DRAFT, $article->status);
        $this->assertTrue($article->is_featured);
        $this->assertNotNull($article->image);
        $this->assertIsArray($article->images);
        $this->assertCount(1, $article->images);
        Storage::disk('public')->assertExists($article->image);

        $oldImagePath = $article->image;

        $updateResponse = $this->actingAs($admin)->put(route('admin.news.update', $article), [
            'title' => 'BUSCO Test Article Updated',
            'content' => 'Updated content for testing the edit workflow.',
            'category' => News::CATEGORIES[1],
            'status' => News::STATUS_PUBLISHED,
            'remove_images' => [$oldImagePath],
            'gallery_images' => [
                UploadedFile::fake()->image('replacement.jpg', 800, 600),
            ],
        ]);

        $updateResponse->assertRedirect(route('admin.news.index'));

        $article->refresh();
        $this->assertSame('BUSCO Test Article Updated', $article->title);
        $this->assertSame(News::STATUS_PUBLISHED, $article->status);
        $this->assertSame(News::CATEGORIES[1], $article->category);
        $this->assertFalse($article->is_featured);
        $this->assertNotSame($oldImagePath, $article->image);
        $this->assertIsArray($article->images);
        $this->assertCount(1, $article->images);
        Storage::disk('public')->assertMissing($oldImagePath);
        Storage::disk('public')->assertExists($article->image);

        $this->actingAs($admin)
            ->post(route('admin.news.toggle', $article))
            ->assertRedirect(route('admin.news.index'));

        $article->refresh();
        $this->assertSame(News::STATUS_DRAFT, $article->status);

        $this->actingAs($admin)
            ->delete(route('admin.news.destroy', $article))
            ->assertRedirect(route('admin.news.index'));

        $this->assertSoftDeleted('news', ['id' => $article->id]);

        $this->actingAs($admin)
            ->post(route('admin.news.restore', $article->id))
            ->assertRedirect(route('admin.news.index'));

        $this->assertDatabaseHas('news', [
            'id' => $article->id,
            'deleted_at' => null,
        ]);
    }

    public function test_news_store_accepts_missing_optional_image(): void
    {
        $admin = $this->adminUser();

        $this->actingAs($admin)
            ->post(route('admin.news.store'), [
                'title' => 'No Image Article',
                'content' => 'Content without image upload.',
                'category' => News::CATEGORIES[2],
                'status' => News::STATUS_PUBLISHED,
            ])
            ->assertRedirect(route('admin.news.index'));

        $article = News::query()->firstOrFail();
        $this->assertNull($article->image);
    }

    public function test_news_validation_rejects_invalid_file_type_and_oversized_image(): void
    {
        Storage::fake('public');

        $admin = $this->adminUser();

        $basePayload = [
            'title' => 'Upload Validation Test',
            'content' => 'Testing invalid uploads.',
            'category' => News::CATEGORIES[0],
            'status' => News::STATUS_DRAFT,
        ];

        $this->actingAs($admin)
            ->post(route('admin.news.store'), $basePayload + [
                'gallery_images' => [
                    UploadedFile::fake()->create('not-image.pdf', 50, 'application/pdf'),
                ],
            ])
            ->assertSessionHasErrors('gallery_images.0');

        $this->actingAs($admin)
            ->post(route('admin.news.store'), $basePayload + [
                'gallery_images' => [
                    UploadedFile::fake()->image('too-large.jpg')->size(6000),
                ],
            ])
            ->assertSessionHasErrors('gallery_images.0');
    }

    public function test_news_validation_rejects_invalid_category_and_status(): void
    {
        $admin = $this->adminUser();

        $this->actingAs($admin)
            ->post(route('admin.news.store'), [
                'title' => '',
                'content' => '',
                'category' => 'Invalid Category',
                'status' => 'invalid',
            ])
            ->assertSessionHasErrors(['title', 'content', 'category', 'status']);
    }

    protected function adminUser(): User
    {
        return User::factory()->create([
            'role' => 'admin',
        ]);
    }
}
