<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Models\QuedanPrice;
use Illuminate\View\View;

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
