<?php

namespace Database\Seeders;

use App\Models\QuedanPrice;
use Illuminate\Database\Seeder;

class QuedanSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['trading_date' => '2026-01-09', 'weekending_date' => '2026-01-04', 'price' => 2280.00],
            ['trading_date' => '2026-01-16', 'weekending_date' => '2026-01-11', 'price' => 2310.00],
            ['trading_date' => '2026-01-23', 'weekending_date' => '2026-01-18', 'price' => 2295.00],
            ['trading_date' => '2026-01-30', 'weekending_date' => '2026-01-25', 'price' => 2330.00],
            ['trading_date' => '2026-02-06', 'weekending_date' => '2026-02-01', 'price' => 2350.00],
            ['trading_date' => '2026-02-13', 'weekending_date' => '2026-02-08', 'price' => 2400.00],
            ['trading_date' => '2026-02-20', 'weekending_date' => '2026-02-15', 'price' => 2380.00],
            ['trading_date' => '2026-02-27', 'weekending_date' => '2026-02-22', 'price' => 2425.00],
            ['trading_date' => '2026-03-06', 'weekending_date' => '2026-03-01', 'price' => 2450.00],
            ['trading_date' => '2026-03-13', 'weekending_date' => '2026-03-08', 'price' => 2475.00],
            ['trading_date' => '2026-03-20', 'weekending_date' => '2026-03-15', 'price' => 2460.00],
            ['trading_date' => '2026-03-27', 'weekending_date' => '2026-03-22', 'price' => 2500.00],
        ];

        $defaultNote = 'Note: Negros buying price is Gross Price and Busco buying price is Net Price.';
        $previousPrice = null;
        $lastIndex = count($rows) - 1;

        foreach ($rows as $index => $row) {
            $price = (float) $row['price'];
            $difference = null;
            $trend = null;

            if ($previousPrice !== null) {
                $difference = round($price - $previousPrice, 2);
                $trend = match (true) {
                    $difference > 0 => QuedanPrice::TREND_UP,
                    $difference < 0 => QuedanPrice::TREND_DOWN,
                    default => QuedanPrice::TREND_NO_CHANGE,
                };
            }

            QuedanPrice::create([
                'price' => number_format($price, 2, '.', ''),
                'trading_date' => $row['trading_date'],
                'weekending_date' => $row['weekending_date'],
                'difference' => $difference !== null ? number_format($difference, 2, '.', '') : null,
                'trend' => $trend,
                'price_subtext' => $index === $lastIndex
                    ? 'As advance subject for final price'
                    : 'Net of Taxes & Liens',
                'notes' => $defaultNote,
                'status' => $index === $lastIndex ? QuedanPrice::STATUS_ACTIVE : QuedanPrice::STATUS_ARCHIVED,
            ]);

            $previousPrice = $price;
        }

        $this->command?->info('Demo Quedan price records seeded (11 archived, 1 active).');
    }
}
