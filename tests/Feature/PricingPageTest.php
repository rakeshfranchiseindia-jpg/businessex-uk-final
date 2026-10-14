<?php

namespace Tests\Feature;

use Tests\TestCase;

class PricingPageTest extends TestCase
{
    public function test_pricing_page_shows_plan_details_before_the_plan_cards(): void
    {
        $this->get(route('pricing'))
            ->assertOk()
            ->assertSeeInOrder([
                'class="pricing-hero"',
                'Choose The Right Membership',
                'assets/img/hero-bg.jpg',
                'Choose a Plan',
                'Create your profile',
                'Profile Type:',
                'name="pricing-profile-type" value="business" checked',
                'id="pricing-name"',
                'id="pricing-mobile"',
                'id="pricing-email"',
                'id="pricing-company"',
                'id="pricing-payment-mode"',
                'class="pricing-plans"',
                'class="pricing-cards"',
                'Basic',
                'Premium',
                'Gold',
                'Platinum',
                'Everything in Gold Plan',
            ], false)
            ->assertDontSee('pricing-promo-code', false)
            ->assertSee('value="+44" selected', false)
            ->assertSee('data-pricing-toggle', false);
    }
}
