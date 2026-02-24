<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\QuedanPrice;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Render the public homepage with dynamic previews.
     */
    public function index(): View
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

        return view('pages.home', [
            'latestNews' => $latestNews,
            'activeQuedan' => $activeQuedan,
            'previousQuedan' => $previousQuedan,
            'activePage' => 'home',
        ]);
    }
}
