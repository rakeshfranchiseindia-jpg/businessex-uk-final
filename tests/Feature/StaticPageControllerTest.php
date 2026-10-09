<?php

namespace Tests\Feature;

use Tests\TestCase;

class StaticPageControllerTest extends TestCase
{
    public function test_static_pages_render_with_businessx_theme_content(): void
    {
        foreach ([
            ['about-us', 'About BusinessX'],
            ['disclaimer', 'Disclaimer'],
            ['privacy-policy', 'Privacy Policy'],
            ['terms-and-conditions', 'Terms of Use'],
            ['contact-us', 'Contact BusinessX'],
        ] as [$path, $title]) {
            $this->get('/' . $path)
                ->assertOk()
                ->assertSee($title)
                ->assertSee('static-page__content')
                ->assertSee('BusinessX');
        }
    }

    public function test_footer_static_page_links_point_to_their_pages(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee(route('about-us'), false)
            ->assertSee(route('disclaimer'), false)
            ->assertSee(route('privacy-policy'), false)
            ->assertSee(route('terms'), false)
            ->assertSee(route('contact'), false);
    }

    public function test_contact_page_uses_working_email_contact_instead_of_a_broken_form(): void
    {
        $this->get(route('contact'))
            ->assertOk()
            ->assertSee('mailto:info@worldtradecouncil.com', false)
            ->assertDontSee('contact.submit');
    }
}
