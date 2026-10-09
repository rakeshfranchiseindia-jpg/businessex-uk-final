<?php

namespace Tests\Feature;

use Tests\TestCase;

class NotFoundPageTest extends TestCase
{
    public function test_unknown_url_returns_the_branded_not_found_page(): void
    {
        $this->get('/this-page-does-not-exist')
            ->assertNotFound()
            ->assertSee('Page not found')
            ->assertSee('Back to Home')
            ->assertSee('class="bx-header"', false)
            ->assertSee('class="bx-footer"', false)
            ->assertSee(route('business-listing'), false);
    }

    public function test_explicit_not_found_errors_use_the_custom_page(): void
    {
        $this->get('/registration?type=unknown')
            ->assertNotFound()
            ->assertSee('Page not found');
    }
}
