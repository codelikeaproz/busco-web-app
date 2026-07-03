<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NewsDetailResource;
use App\Http\Resources\NewsResource;
use App\Http\Resources\QuedanPriceResource;
use App\Models\News;
use App\Models\QuedanPrice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Schema;

class HomeApiController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $latestNews = collect();
        $activeQuedan = null;
        $previousQuedan = null;

        if (Schema::hasTable('news')) {
            $latestNews = News::published()
                ->orderByDesc('created_at')
                ->orderByDesc('id')
                ->take(3)
                ->get();
        }

        if (Schema::hasTable('quedan_prices')) {
            $activeQuedan = QuedanPrice::active()->latest('trading_date')->first();
            $previousQuedan = QuedanPrice::archived()->first();
        }

        return response()->json([
            'latest_news' => NewsResource::collection($latestNews)->resolve(),
            'active_quedan' => $activeQuedan ? QuedanPriceResource::make($activeQuedan)->resolve() : null,
            'previous_quedan' => $previousQuedan ? QuedanPriceResource::make($previousQuedan)->resolve() : null,
        ]);
    }
}
