<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobOpening;
use App\Models\News;
use App\Models\QuedanPrice;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Show the admin dashboard with basic stats.
     */
    public function dashboard(): View
    {
        $hasNewsTable = Schema::hasTable('news');
        $hasQuedanTable = Schema::hasTable('quedan_prices');
        $hasJobsTable = Schema::hasTable('job_openings');

        $stats = [
            'total_news' => $hasNewsTable ? News::count() : 0,
            'published_news' => $hasNewsTable ? News::published()->count() : 0,
            'draft_news' => $hasNewsTable ? News::where('status', News::STATUS_DRAFT)->count() : 0,
            'open_jobs' => $hasJobsTable ? JobOpening::publiclyOpen()->count() : 0,
            'active_quedan' => $hasQuedanTable ? QuedanPrice::active()->latest('trading_date')->first() : null,
            'last_news' => $hasNewsTable ? News::latest()->first() : null,
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
