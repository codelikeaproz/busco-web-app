<?php

namespace Tests\Feature;

use App\Models\QuedanPrice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuedanCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_quedan_logic_handles_successive_entries_archiving_and_trends(): void
    {
        $admin = $this->adminUser();

        $this->postQuedan($admin, '2026-02-06', '2026-02-01', 1000.00)
            ->assertRedirect(route('admin.quedan.index'));

        $active = QuedanPrice::active()->firstOrFail();
        $this->assertSame('1000.00', $active->price);
        $this->assertSame('As advance subject for final price', $active->price_subtext);
        $this->assertNull($active->difference);
        $this->assertNull($active->trend);
        $this->assertSame(0, QuedanPrice::archived()->count());

        $this->postQuedan($admin, '2026-02-13', '2026-02-08', 1100.00)
            ->assertRedirect(route('admin.quedan.index'));

        $active = QuedanPrice::active()->firstOrFail();
        $this->assertSame('1100.00', $active->price);
        $this->assertSame('100.00', $active->difference);
        $this->assertSame(QuedanPrice::TREND_UP, $active->trend);
        $this->assertSame(1, QuedanPrice::archived()->count());

        $this->postQuedan($admin, '2026-02-20', '2026-02-15', 1100.00)
            ->assertRedirect(route('admin.quedan.index'));

        $active = QuedanPrice::active()->firstOrFail();
        $this->assertSame('1100.00', $active->price);
        $this->assertSame('0.00', $active->difference);
        $this->assertSame(QuedanPrice::TREND_NO_CHANGE, $active->trend);
        $this->assertSame(2, QuedanPrice::archived()->count());

        $this->postQuedan($admin, '2026-02-27', '2026-02-22', 1050.00)
            ->assertRedirect(route('admin.quedan.index'));

        $active = QuedanPrice::active()->firstOrFail();
        $this->assertSame('1050.00', $active->price);
        $this->assertSame('-50.00', $active->difference);
        $this->assertSame(QuedanPrice::TREND_DOWN, $active->trend);
        $this->assertSame(3, QuedanPrice::archived()->count());
    }

    public function test_quedan_validation_rules_trigger_for_invalid_input(): void
    {
        $admin = $this->adminUser();

        $this->actingAs($admin)
            ->post(route('admin.quedan.store'), [
                'trading_date' => '',
                'weekending_date' => '2026-02-01',
                'price' => -1,
                'notes' => str_repeat('A', 501),
            ])
            ->assertSessionHasErrors(['trading_date', 'price', 'notes']);

        $this->actingAs($admin)
            ->post(route('admin.quedan.store'), [
                'trading_date' => '2026-02-10',
                'weekending_date' => '2026-02-11',
                'price' => 1000,
            ])
            ->assertSessionHasErrors(['weekending_date']);
    }

    public function test_active_quedan_cannot_be_deleted_but_archived_record_can(): void
    {
        $admin = $this->adminUser();

        $this->postQuedan($admin, '2026-02-06', '2026-02-01', 1000.00);
        $active = QuedanPrice::active()->firstOrFail();

        $this->actingAs($admin)
            ->delete(route('admin.quedan.destroy', $active))
            ->assertRedirect(route('admin.quedan.index'));

        $this->assertDatabaseHas('quedan_prices', ['id' => $active->id]);

        $this->postQuedan($admin, '2026-02-13', '2026-02-08', 1025.00);

        $archived = QuedanPrice::archived()->firstOrFail();

        $this->actingAs($admin)
            ->delete(route('admin.quedan.destroy', $archived))
            ->assertRedirect(route('admin.quedan.index'));

        $this->assertDatabaseMissing('quedan_prices', ['id' => $archived->id]);
    }

    public function test_deleting_archived_quedan_recalculates_following_records(): void
    {
        $admin = $this->adminUser();

        $this->postQuedan($admin, '2026-02-06', '2026-02-01', 1000.00);
        $this->postQuedan($admin, '2026-02-13', '2026-02-08', 1100.00);
        $this->postQuedan($admin, '2026-02-20', '2026-02-15', 1200.00);

        $toDelete = QuedanPrice::query()
            ->whereDate('trading_date', '2026-02-13')
            ->firstOrFail();

        $this->actingAs($admin)
            ->delete(route('admin.quedan.destroy', $toDelete))
            ->assertRedirect(route('admin.quedan.index'));

        $active = QuedanPrice::active()->firstOrFail();

        $this->assertDatabaseMissing('quedan_prices', ['id' => $toDelete->id]);
        $this->assertSame('1200.00', $active->price);
        $this->assertSame('200.00', $active->difference);
        $this->assertSame(QuedanPrice::TREND_UP, $active->trend);
    }

    public function test_admin_can_edit_quedan_and_recalculate_following_records(): void
    {
        $admin = $this->adminUser();

        $this->postQuedan($admin, '2026-02-06', '2026-02-01', 1000.00);
        $this->postQuedan($admin, '2026-02-13', '2026-02-08', 1100.00);
        $this->postQuedan($admin, '2026-02-20', '2026-02-15', 1200.00);

        $middleArchived = QuedanPrice::query()
            ->whereDate('trading_date', '2026-02-13')
            ->firstOrFail();

        $this->actingAs($admin)
            ->put(route('admin.quedan.update', $middleArchived), [
                'trading_date' => '2026-02-13',
                'weekending_date' => '2026-02-08',
                'price' => 950.00,
                'price_subtext' => 'Corrected posted value',
                'notes' => 'Corrected after verification',
            ])
            ->assertRedirect(route('admin.quedan.index'));

        $middleArchived->refresh();
        $active = QuedanPrice::active()->firstOrFail();

        $this->assertSame('950.00', $middleArchived->price);
        $this->assertSame('-50.00', $middleArchived->difference);
        $this->assertSame(QuedanPrice::TREND_DOWN, $middleArchived->trend);
        $this->assertSame('Corrected posted value', $middleArchived->price_subtext);

        $this->assertSame('1200.00', $active->price);
        $this->assertSame('250.00', $active->difference);
        $this->assertSame(QuedanPrice::TREND_UP, $active->trend);
    }

    protected function postQuedan(User $admin, string $tradingDate, string $weekendingDate, float $price)
    {
        return $this->actingAs($admin)->post(route('admin.quedan.store'), [
            'trading_date' => $tradingDate,
            'weekending_date' => $weekendingDate,
            'price' => $price,
            'price_subtext' => 'As advance subject for final price',
            'notes' => 'Test note',
        ]);
    }

    protected function adminUser(): User
    {
        return User::factory()->create([
            'role' => 'admin',
        ]);
    }
}
