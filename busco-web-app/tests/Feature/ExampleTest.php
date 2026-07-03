<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_the_home_route_redirects_to_the_next_js_frontend(): void
    {
        config(['busco.frontend_url' => 'http://localhost:3000']);

        $this->get('/')
            ->assertRedirect('http://localhost:3000');
    }
}
