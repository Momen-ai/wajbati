<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\NewsletterSubscriber;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactAndNewsletterTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_submit_contact_form(): void
    {
        $response = $this->post(route('contact.submit'), [
            'name' => 'Sara Ahmad',
            'email' => 'sara@example.com',
            'phone' => '0599123456',
            'subject' => 'Inquiry about catering',
            'message' => 'Hello, do you offer party catering?',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('contacts', [
            'name' => 'Sara Ahmad',
            'email' => 'sara@example.com',
            'phone' => '0599123456',
            'title' => 'Inquiry about catering',
            'message' => 'Hello, do you offer party catering?',
            'user_id' => null,
            'is_read' => 0,
        ]);
    }

    public function test_authenticated_user_contact_stores_user_id(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->post(route('contact.submit'), [
            'name' => $user->name,
            'email' => $user->email,
            'phone' => '0599765432',
            'subject' => 'Order Question',
            'message' => 'Can I modify my delivery address?',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('contacts', [
            'user_id' => $user->id,
            'email' => $user->email,
        ]);
    }

    public function test_user_can_subscribe_to_newsletter_without_duplicates(): void
    {
        $response1 = $this->post(route('newsletter.subscribe'), [
            'email' => 'newsletter_fan@example.com',
        ]);

        $response1->assertRedirect();
        $response1->assertSessionHas('success');

        $this->assertDatabaseHas('newsletter_subscribers', [
            'email' => 'newsletter_fan@example.com',
        ]);
        $this->assertEquals(1, NewsletterSubscriber::where('email', 'newsletter_fan@example.com')->count());

        // Subscribing again with the same email
        $response2 = $this->post(route('newsletter.subscribe'), [
            'email' => 'newsletter_fan@example.com',
        ]);

        $response2->assertRedirect();
        $this->assertEquals(1, NewsletterSubscriber::where('email', 'newsletter_fan@example.com')->count());
    }

    public function test_admin_can_toggle_read_status_of_contact(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $contact = Contact::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'title' => 'Feedback',
            'message' => 'Great platform',
            'is_read' => false,
        ]);

        $response = $this->actingAs($admin)->patch(route('dashboard.contacts.toggle-read', $contact));

        $response->assertRedirect();
        $this->assertTrue((bool) $contact->fresh()->is_read);
    }
}
