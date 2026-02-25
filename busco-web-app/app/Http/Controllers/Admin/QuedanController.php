<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuedanPrice;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

// Admin controller for posting, editing, and recalculating Quedan prices
class QuedanController extends Controller
{
    /**
     * List active and archived Quedan records.
     */
    public function index(): View
    {
        return view('admin.quedan.index', [
            'active' => QuedanPrice::active()->latest('trading_date')->first(),
            'archived' => QuedanPrice::archived()->paginate(10),
        ]);
    }

    /**
     * Show the form to post a new Quedan price.
     */
    public function create(): View
    {
        return view('admin.quedan.create', [
            'previousActive' => QuedanPrice::active()->latest('trading_date')->first(),
        ]);
    }

    /**
     * Show the form to edit a Quedan record.
     */
    public function edit(QuedanPrice $quedan): View
    {
        $previousRecord = QuedanPrice::query()
            ->where(function ($query) use ($quedan): void {
                $query->whereDate('trading_date', '<', $quedan->trading_date)
                    ->orWhere(function ($sameDateQuery) use ($quedan): void {
                        $sameDateQuery->whereDate('trading_date', '=', $quedan->trading_date)
                            ->where('id', '<', $quedan->id);
                    });
            })
            ->orderByDesc('trading_date')
            ->orderByDesc('id')
            ->first();

        return view('admin.quedan.edit', [
            'quedan' => $quedan,
            'previousRecord' => $previousRecord,
        ]);
    }

    /**
     * Store a new Quedan price and automatically compute difference/trend.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateQuedanPayload($request);

        $newPrice = round((float) $validated['price'], 2);

        DB::transaction(function () use ($validated, $newPrice): void {
            $previousActive = QuedanPrice::query()
                ->where('status', QuedanPrice::STATUS_ACTIVE)
                ->lockForUpdate()
                ->latest('id')
                ->first();

            $difference = null;
            $trend = null;

            if ($previousActive) {
                $previousPrice = round((float) $previousActive->price, 2);
                $difference = round($newPrice - $previousPrice, 2);

                $trend = match (true) {
                    $difference > 0 => QuedanPrice::TREND_UP,
                    $difference < 0 => QuedanPrice::TREND_DOWN,
                    default => QuedanPrice::TREND_NO_CHANGE,
                };

                $previousActive->update([
                    'status' => QuedanPrice::STATUS_ARCHIVED,
                ]);
            }

            QuedanPrice::create([
                'price' => number_format($newPrice, 2, '.', ''),
                'trading_date' => $validated['trading_date'],
                'weekending_date' => $validated['weekending_date'],
                'difference' => $difference !== null ? number_format($difference, 2, '.', '') : null,
                'trend' => $trend,
                'price_subtext' => $validated['price_subtext'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'status' => QuedanPrice::STATUS_ACTIVE,
            ]);
        });

        return redirect()
            ->route('admin.quedan.index')
            ->with('success', 'New Quedan price of PHP ' . number_format($newPrice, 2) . ' posted successfully.');
    }

    /**
     * Update a Quedan record and recalculate the full series trend/difference chain.
     */
    public function update(Request $request, QuedanPrice $quedan): RedirectResponse
    {
        $validated = $this->validateQuedanPayload($request);
        $updatedPrice = round((float) $validated['price'], 2);

        DB::transaction(function () use ($quedan, $validated, $updatedPrice): void {
            $quedan->update([
                'price' => number_format($updatedPrice, 2, '.', ''),
                'trading_date' => $validated['trading_date'],
                'weekending_date' => $validated['weekending_date'],
                'price_subtext' => $validated['price_subtext'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);

            $this->recalculateSeries();
        });

        return redirect()
            ->route('admin.quedan.index')
            ->with('success', 'Quedan record updated and price differences recalculated.');
    }

    /**
     * Delete a Quedan record (archived only).
     */
    public function destroy(QuedanPrice $quedan): RedirectResponse
    {
        if ($quedan->status === QuedanPrice::STATUS_ACTIVE) {
            return redirect()
                ->route('admin.quedan.index')
                ->with('error', 'Cannot delete the active Quedan price. Post a new price first to archive it.');
        }

        DB::transaction(function () use ($quedan): void {
            $quedan->delete();
            $this->recalculateSeries();
        });

        return redirect()
            ->route('admin.quedan.index')
            ->with('success', 'Quedan record deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    protected function validateQuedanPayload(Request $request): array
    {
        return $request->validate([
            'trading_date' => ['required', 'date'],
            'weekending_date' => ['required', 'date', 'before_or_equal:trading_date'],
            'price' => ['required', 'numeric', 'min:0'],
            'price_subtext' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);
    }

    /**
     * Recalculate difference and trend for all Quedan records based on chronological order.
     */
    protected function recalculateSeries(): void
    {
        $records = QuedanPrice::query()
            ->lockForUpdate()
            ->orderBy('trading_date')
            ->orderBy('id')
            ->get();

        $previousPrice = null;

        foreach ($records as $record) {
            $difference = null;
            $trend = null;
            $currentPrice = round((float) $record->price, 2);

            if ($previousPrice !== null) {
                $difference = round($currentPrice - $previousPrice, 2);

                $trend = match (true) {
                    $difference > 0 => QuedanPrice::TREND_UP,
                    $difference < 0 => QuedanPrice::TREND_DOWN,
                    default => QuedanPrice::TREND_NO_CHANGE,
                };
            }

            $record->update([
                'difference' => $difference !== null ? number_format($difference, 2, '.', '') : null,
                'trend' => $trend,
            ]);

            $previousPrice = $currentPrice;
        }
    }
}
