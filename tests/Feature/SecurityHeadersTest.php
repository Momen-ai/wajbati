<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityHeadersTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that web responses include modern security headers.
     */
    public function test_security_headers_are_present(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertHeader('X-Frame-Options', 'DENY');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-XSS-Protection', '1; mode=block');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->assertHeader('Permissions-Policy', 'geolocation=(), camera=(), microphone=()');
        $response->assertHeader('Content-Security-Policy-Report-Only');
    }

    /**
     * Test localized error pages render properly.
     */
    public function test_error_pages_render_localized_content(): void
    {
        app()->setLocale('ar');

        $view404 = $this->view('errors.404');
        $view404->assertSee('الصفحة غير موجودة');

        $view403 = $this->view('errors.403');
        $view403->assertSee('غير مصرح بالدخول');

        $view419 = $this->view('errors.419');
        $view419->assertSee('انتهت الجلسة');

        $view429 = $this->view('errors.429');
        $view429->assertSee('طلبات كثيرة جداً');

        $view500 = $this->view('errors.500');
        $view500->assertSee('خطأ داخلي في الخادم');

        $view503 = $this->view('errors.503');
        $view503->assertSee('الخدمة غير متوفرة حالياً');
    }
}
