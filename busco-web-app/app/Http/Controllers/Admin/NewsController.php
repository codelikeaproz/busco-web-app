<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function index(Request $request): View
    {
        $query = News::withTrashed();

        if ($request->filled('category')) {
            $query->where('category', (string) $request->query('category'));
        }

        if ($request->filled('status')) {
            $query->where('status', (string) $request->query('status'));
        }

        if ($request->filled('trashed')) {
            if ($request->query('trashed') === 'only') {
                $query->onlyTrashed();
            } elseif ($request->query('trashed') === 'without') {
                $query->withoutTrashed();
            }
        }

        return view('admin.news.index', [
            'news' => $query->orderByDesc('created_at')->orderByDesc('id')->paginate(10)->withQueryString(),
            'categories' => News::CATEGORIES,
            'filters' => [
                'category' => (string) $request->query('category', ''),
                'status' => (string) $request->query('status', ''),
                'trashed' => (string) $request->query('trashed', ''),
            ],
        ]);
    }

    public function create(): View
    {
        return view('admin.news.create', [
            'categories' => News::CATEGORIES,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatedNewsData($request);
        $storedGalleryImages = $this->storeUploadedGalleryImages($request);
        $validated = $this->applyGalleryImagesToPayload($validated, $storedGalleryImages);

        $validated['is_featured'] = $request->boolean('is_featured');

        $article = News::create($validated);

        return redirect()
            ->route('admin.news.index')
            ->with('success', 'News article "' . $article->title . '" created successfully.');
    }

    public function edit(News $news): View
    {
        return view('admin.news.edit', [
            'news' => $news,
            'categories' => News::CATEGORIES,
        ]);
    }

    public function update(Request $request, News $news): RedirectResponse
    {
        $validated = $this->validatedNewsData($request);
        $existingImages = $news->all_image_paths;
        $removeImages = array_values(array_unique(array_filter((array) $request->input('remove_images', []), 'is_string')));
        $imagesToRemove = array_values(array_intersect($existingImages, $removeImages));
        $remainingImages = array_values(array_diff($existingImages, $imagesToRemove));
        $uploadedImages = $this->storeUploadedGalleryImages($request);
        $finalImages = array_values(array_slice(array_merge($remainingImages, $uploadedImages), 0, 5));

        if (count($remainingImages) + count($uploadedImages) > 5) {
            foreach ($uploadedImages as $path) {
                Storage::disk('public')->delete($path);
            }

            throw ValidationException::withMessages([
                'gallery_images' => 'You can store a maximum of 5 images per article.',
            ]);
        }

        foreach ($imagesToRemove as $path) {
            Storage::disk('public')->delete($path);
        }

        $validated = $this->applyGalleryImagesToPayload($validated, $finalImages);

        $validated['is_featured'] = $request->boolean('is_featured');

        $news->update($validated);

        return redirect()
            ->route('admin.news.index')
            ->with('success', 'News article updated successfully.');
    }

    public function destroy(News $news): RedirectResponse
    {
        $title = $news->title;
        $news->delete();

        return redirect()
            ->route('admin.news.index')
            ->with('success', '"' . $title . '" has been moved to trash.');
    }

    public function restore(int $id): RedirectResponse
    {
        $news = News::withTrashed()->findOrFail($id);
        $news->restore();

        return redirect()
            ->route('admin.news.index')
            ->with('success', '"' . $news->title . '" has been restored.');
    }

    public function toggleStatus(News $news): RedirectResponse
    {
        $news->update([
            'status' => $news->status === News::STATUS_PUBLISHED ? News::STATUS_DRAFT : News::STATUS_PUBLISHED,
        ]);

        $label = $news->status === News::STATUS_PUBLISHED ? 'published' : 'unpublished';

        return redirect()
            ->route('admin.news.index')
            ->with('success', '"' . $news->title . '" has been ' . $label . '.');
    }

    /**
     * @return array<string, mixed>
     */
    protected function validatedNewsData(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'sub_title' => ['nullable', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'category' => ['required', 'in:' . implode(',', News::CATEGORIES)],
            'status' => ['required', 'in:' . News::STATUS_DRAFT . ',' . News::STATUS_PUBLISHED],
            'is_featured' => ['nullable', 'boolean'],
            'gallery_images' => ['nullable', 'array', 'max:5'],
            'gallery_images.*' => ['file', 'mimes:jpg,jpeg', 'max:5120'],
            'remove_images' => ['nullable', 'array'],
            'remove_images.*' => ['string'],
        ]);
    }

    /**
     * @return list<string>
     */
    protected function storeUploadedGalleryImages(Request $request): array
    {
        $stored = [];

        foreach ((array) $request->file('gallery_images', []) as $file) {
            if ($file) {
                $stored[] = $file->store('news', 'public');
            }
        }

        return $stored;
    }

    /**
     * @param  array<string, mixed>  $payload
     * @param  list<string>  $images
     * @return array<string, mixed>
     */
    protected function applyGalleryImagesToPayload(array $payload, array $images): array
    {
        $payload['images'] = $images;
        $payload['image'] = $images[0] ?? null;

        return $payload;
    }
}
