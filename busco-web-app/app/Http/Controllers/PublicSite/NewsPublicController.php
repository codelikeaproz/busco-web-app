<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\View\View;

// Public news pages controller (listing and article detail)
class NewsPublicController extends Controller
{
    // Show paginated published news with optional category filtering
    public function index(Request $request): View
    {
        $query = News::published()->orderByDesc('created_at')->orderByDesc('id');

        if ($request->filled('category')) {
            $query->category((string) $request->query('category'));
        }

        return view('pages.news.index', [
            'news' => $query->paginate(6)->withQueryString(),
            'categories' => News::CATEGORIES,
            'selectedCategory' => (string) $request->query('category', ''),
            'activePage' => 'news',
        ]);
    }

    // Show a published news article and related articles from the same category
    public function show(News $news): View
    {
        abort_if($news->status !== News::STATUS_PUBLISHED, 404);

        $related = News::published()
            ->where('category', $news->category)
            ->where('id', '!=', $news->id)
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->take(3)
            ->get();

        return view('pages.news.show', [
            'news' => $news,
            'related' => $related,
            'activePage' => 'news',
        ]);
    }
}
