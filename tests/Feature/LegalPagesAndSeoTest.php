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
        $response->assertSee('Privacy Policy');
    }

    /**
     * Test terms of service page loads with status 200.
     */
    public function test_terms_of_service_page_is_accessible(): void
    {
        $response = $this->get(route('terms'));
        $response->assertOk();
        $response->assertSee('Terms of Service');
    }
}
