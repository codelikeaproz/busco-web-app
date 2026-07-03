<?php

namespace App\Console\Commands;

use App\Models\News;
use App\Services\NewsImageStorage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class MigrateNewsImagesToCloudinary extends Command
{
    protected $signature = 'news:migrate-images-to-cloudinary {--dry-run : List images without uploading}';

    protected $description = 'Upload local news images to Cloudinary and update database references';

    public function handle(NewsImageStorage $images): int
    {
        if ($images->disk() !== 'cloudinary') {
            $this->error('Set NEWS_IMAGE_DISK=cloudinary and configure CLOUDINARY_URL first.');

            return self::FAILURE;
        }

        $updated = 0;

        News::withTrashed()->chunkById(50, function ($articles) use ($images, &$updated): void {
            foreach ($articles as $article) {
                $paths = $article->all_image_paths;
                $newPaths = [];
                $changed = false;

                foreach ($paths as $path) {
                    if (preg_match('/^https?:\/\//i', $path)) {
                        $newPaths[] = $path;

                        continue;
                    }

                    if (! Storage::disk('public')->exists($path)) {
                        $this->warn("Missing local file for article {$article->id}: {$path}");

                        continue;
                    }

                    if ($this->option('dry-run')) {
                        $this->line("Would migrate: {$path}");
                        $newPaths[] = $path;

                        continue;
                    }

                    $contents = Storage::disk('public')->get($path);
                    $cloudPath = 'news/' . basename($path);
                    Storage::disk('cloudinary')->put($cloudPath, $contents);
                    $newPaths[] = $cloudPath;
                    Storage::disk('public')->delete($path);
                    $changed = true;
                }

                if ($changed && ! $this->option('dry-run')) {
                    $article->update([
                        'images' => $newPaths,
                        'image' => $newPaths[0] ?? null,
                    ]);
                    $updated++;
                }
            }
        });

        $this->info($this->option('dry-run')
            ? 'Dry run complete.'
            : "Migrated images for {$updated} article(s).");

        return self::SUCCESS;
    }
}
