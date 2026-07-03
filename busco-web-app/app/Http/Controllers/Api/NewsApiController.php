<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NewsDetailResource;
use App\Http\Resources\NewsResource;
use App\Models\News;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class NewsApiController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = News::published()->orderByDesc('created_at')->orderByDesc('id');

        if ($request->filled('category')) {
            $query->category((string) $request->query('category'));
        }

        return NewsResource::collection(
            $query->paginate(6)->withQueryString()
        );
    }

    public function show(News $news): JsonResponse
    {
        abort_if($news->status !== News::STATUS_PUBLISHED, 404);

        $related = News::published()
            ->where('category', $news->category)
            ->where('id', '!=', $news->id)
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->take(3)
            ->get();

        return response()->json([
            'data' => NewsDetailResource::make($news)->resolve(),
            'related' => NewsResource::collection($related)->resolve(),
        ]);
    }
}
