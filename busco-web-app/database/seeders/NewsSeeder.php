<?php

namespace Database\Seeders;

use App\Models\News;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        $articles = [
            [
                'title' => 'BUSCO Recognized for Quality Milling Standards',
                'sub_title' => 'BUSCO was formally recognized by the SRA for strong performance in quality milling operations during the regional industry summit.',
                'content' => 'BUSCO Sugar Milling Co., Inc. has been formally recognized by the Philippine Sugar Regulatory Administration (SRA) for outstanding performance in quality milling operations. The recognition was awarded during the Regional Sugar Industry Summit held in Cagayan de Oro City...',
                'category' => 'Achievements',
                'status' => 'published',
                'is_featured' => true,
            ],
            // ... keep the rest as-is
        ];

        foreach ($articles as $index => $article) {
            $createdAt = now()->subDays(count($articles) - $index);

            // Optional but recommended: stable unique key
            $slug = Str::slug($article['title']);

            News::updateOrCreate(
                ['title' => $article['title']], // or ['slug' => $slug] if you have slug column
                array_merge($article, [
                    // If you have a slug column, uncomment:
                    // 'slug' => $slug,

                    // If your app expects an image column, ensure it's null for seeded items
                    // 'image' => null, // or 'image_path' => null

                    'created_at' => $createdAt, // note: updateOrCreate will update these too
                    'updated_at' => $createdAt,
                ])
            );
        }

        $this->command?->info(count($articles) . ' demo news articles seeded (idempotent).');
    }
}
