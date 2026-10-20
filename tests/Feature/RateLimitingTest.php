<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RateLimitingTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_submission_is_rate_limited(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $response = $this->post(route('contact.submit'), [
                'name' => 'Tester',
                'email' => "tester{$i}@example.com",
                'message' => 'Hello',
            ]);
            $this->assertNotEquals(429, $response->getStatusCode());
        }

        // 6th attempt should be throttled
        $response = $this->post(route('contact.submit'), [
            'name' => 'Tester',
            'email' => 'tester_exceed@example.com',
            'message' => 'Hello',
        ]);

        $response->assertStatus(429);
    }

    public function test_newsletter_is_rate_limited(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $response = $this->post(route('newsletter.subscribe'), [
                'email' => "user{$i}@example.com",
            ]);
            $this->assertNotEquals(429, $response->getStatusCode());
        }

        // 6th attempt should be throttled
        $response = $this->post(route('newsletter.subscribe'), [
            'email' => 'user_exceed@example.com',
        ]);

        $response->assertStatus(429);
    }
}
