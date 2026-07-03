<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\QuedanPriceResource;
use App\Models\QuedanPrice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuedanApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $activePrice = QuedanPrice::active()->latest('trading_date')->first();
        $previousPrice = QuedanPrice::archived()->first();
        $history = QuedanPrice::archived()->paginate(5)->withQueryString();

        return response()->json([
            'active_price' => $activePrice ? QuedanPriceResource::make($activePrice)->resolve() : null,
            'previous_price' => $previousPrice ? QuedanPriceResource::make($previousPrice)->resolve() : null,
            'history' => [
                'data' => QuedanPriceResource::collection($history->items())->resolve(),
                'meta' => [
                    'current_page' => $history->currentPage(),
                    'last_page' => $history->lastPage(),
                    'per_page' => $history->perPage(),
                    'total' => $history->total(),
                    'from' => $history->firstItem(),
                    'to' => $history->lastItem(),
                ],
                'links' => [
                    'first' => $history->url(1),
                    'last' => $history->url($history->lastPage()),
                    'prev' => $history->previousPageUrl(),
                    'next' => $history->nextPageUrl(),
                ],
            ],
        ]);
    }
}
