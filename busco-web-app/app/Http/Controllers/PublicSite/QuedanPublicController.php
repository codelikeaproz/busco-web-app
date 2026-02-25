<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Models\QuedanPrice;
use Illuminate\View\View;

// Public Quedan page controller for active price and historical records
class QuedanPublicController extends Controller
{
    /**
     * Public Quedan announcement page with active record and history.
     */
    public function index(): View
    {
        $activePrice = QuedanPrice::active()->latest('trading_date')->first();
        $previousPrice = QuedanPrice::archived()->first();
        $history = QuedanPrice::archived()->paginate(5);

        return view('pages.quedan', [
            'activePrice' => $activePrice,
            'history' => $history,
            'previousPrice' => $previousPrice,
            'activePage' => 'quedan',
        ]);
    }
}
