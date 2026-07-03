<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FrontendRedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_routes_redirect_when_frontend_url_is_configured(): void
    {
        config(['busco.frontend_url' => 'https://busco.vercel.app']);

        $this->get('/about')
            ->assertRedirect('https://busco.vercel.app/about');

        $this->get('/news?category=Events')
            ->assertRedirect('https://busco.vercel.app/news?category=Events');
    }

    public function test_public_routes_redirect_to_default_frontend_url(): void
    {
        config(['busco.frontend_url' => 'http://localhost:3000']);

        $this->get('/')
            ->assertRedirect('http://localhost:3000');

        $this->get('/about')
            ->assertRedirect('http://localhost:3000/about');
    }

    public function test_admin_routes_are_not_redirected_to_frontend(): void
    {
        config(['busco.frontend_url' => 'https://busco.vercel.app']);

        $this->get('/admin/login')->assertOk();
    }
}
