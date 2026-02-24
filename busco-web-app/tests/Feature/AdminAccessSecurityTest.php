<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AdminAccessSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_routes_redirect_to_admin_login_when_guest(): void
    {
        $routes = [
            route('admin.dashboard'),
            route('admin.news.index'),
            route('admin.quedan.index'),
        ];

        foreach ($routes as $url) {
            $this->get($url)->assertRedirect(route('admin.login'));
        }
    }

    public function test_non_admin_user_is_forbidden_from_admin_dashboard(): void
    {
        $user = User::factory()->create([
            'role' => 'user',
        ]);

        $this->actingAs($user)
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_users_table_has_remember_token_column(): void
    {
        $this->assertTrue(Schema::hasColumn('users', 'remember_token'));
    }

    public function test_custom_404_page_renders_when_debug_is_disabled(): void
    {
        config(['app.debug' => false]);

        $this->get('/__missing-route-for-404-check')
            ->assertStatus(404)
            ->assertSee('Page Not Found')
            ->assertDontSee('Stack trace');
    }
}
