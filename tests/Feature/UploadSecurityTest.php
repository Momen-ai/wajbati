<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UploadSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_cannot_upload_php_file_in_profile(): void
    {
        Storage::fake('public');
        $user = User::factory()->create(['role' => 'user']);

        $maliciousFile = UploadedFile::fake()->create('shell.php', 10, 'application/x-php');

        $response = $this->actingAs($user)->patch(route('profile.update'), [
            'name' => 'Updated Name',
            'email' => $user->email,
            'image' => $maliciousFile,
        ]);

        $response->assertSessionHasErrors('image');
        $this->assertCount(0, Storage::disk('public')->allFiles('chefs'));
    }

    public function test_admin_cannot_upload_php_file_in_dashboard_users_update(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['role' => 'admin']);
        $targetUser = User::factory()->create(['role' => 'user']);

        $maliciousFile = UploadedFile::fake()->create('shell.php', 10, 'application/x-php');

        $response = $this->actingAs($admin)->put(route('dashboard.users.update', $targetUser), [
            'name' => 'Target User',
            'email' => $targetUser->email,
            'image' => $maliciousFile,
        ]);

        $response->assertSessionHasErrors('image');
        $this->assertCount(0, Storage::disk('public')->allFiles('users'));
    }

    public function test_fake_image_with_spoofed_extension_is_rejected(): void
    {
        Storage::fake('public');
        $user = User::factory()->create(['role' => 'chef']);

        // A fake text file disguised as a png
        $fakeImage = UploadedFile::fake()->createWithContent('malicious.png', '<?php echo "evil"; ?>');

        $response = $this->actingAs($user)->patch(route('profile.update'), [
            'name' => 'Chef Name',
            'email' => $user->email,
            'image' => $fakeImage,
        ]);

        $response->assertSessionHasErrors('image');
    }
}
