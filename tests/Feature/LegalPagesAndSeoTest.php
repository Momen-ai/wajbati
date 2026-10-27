<?php

namespace Tests\Feature;

use Tests\TestCase;

class LegalPagesAndSeoTest extends TestCase
{
    /**
     * Test privacy policy page loads with status 200.
     */
    public function test_privacy_policy_page_is_accessible(): void
    {
        $response = $this->get(route('privacy'));
        $response->assertOk();
        $response->assertSee(__('Privacy Policy'));
    }

    /**
     * Test terms of service page loads with status 200.
     */
    public function test_terms_of_service_page_is_accessible(): void
    {
        $response = $this->get(route('terms'));
        $response->assertOk();
        $response->assertSee(__('Terms of Service'));
    }

    /**
     * Test legal pages render in Arabic when locale is ar.
     */
    public function test_legal_pages_render_in_arabic_when_locale_is_ar(): void
    {
        app()->setLocale('ar');

        $privacy = $this->get(route('privacy'));
        $privacy->assertOk();
        $privacy->assertSee(__('Privacy Policy', [], 'ar'));

        $terms = $this->get(route('terms'));
        $terms->assertOk();
        $terms->assertSee(__('Terms of Service', [], 'ar'));
    }
}
