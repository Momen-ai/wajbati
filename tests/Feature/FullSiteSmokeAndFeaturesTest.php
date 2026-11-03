<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Category;
use App\Models\ChefApplication;
use App\Models\Contact;
use App\Models\Meal;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FullSiteSmokeAndFeaturesTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $chef;

    protected User $user;

    protected Category $category;

    protected Meal $meal;

    protected function setUp(): void
    {
        parent::setUp();
        \Illuminate\Database\Eloquent\Model::preventLazyLoading(true);

        $this->admin = User::factory()->admin()->create([
            'email_verified_at' => now(),
        ]);

        $this->chef = User::factory()->chef()->create([
            'kitchen_name' => 'Chef Kitchen Special',
            'email_verified_at' => now(),
        ]);

        $this->user = User::factory()->create([
            'role' => 'user',
            'email_verified_at' => now(),
        ]);

        $this->category = Category::create([
            'name' => 'Main Dishes',
            'description' => 'Hearty homemade meals',
        ]);

        $this->meal = Meal::create([
            'chef_id' => $this->chef->id,
            'category_id' => $this->category->id,
            'name' => 'Delicious Maqluba',
            'description' => 'Traditional rice and chicken dish',
            'price' => 25.00,
            'preparation_time' => 45,
            'is_available' => true,
            'is_active' => true,
        ]);
    }

    public function test_public_pages_render_successfully(): void
    {
        $urls = [
            '/',
            '/meals',
            '/meals/'.$this->meal->id,
            '/chefs',
            '/chefs/'.$this->chef->id,
            '/contact',
            '/cart',
            '/privacy-policy',
            '/terms-of-service',
            '/login',
            '/register',
            '/forgot-password',
        ];

        foreach ($urls as $url) {
            $response = $this->get($url);
            $response->assertOk();
        }
    }

    public function test_guest_interactive_features(): void
    {
        // 1. Language switcher
        $this->get(route('lang.switch', 'ar'))->assertRedirect();
        $this->assertEquals('ar', session('locale'));
        $this->get(route('lang.switch', 'en'))->assertRedirect();
        $this->assertEquals('en', session('locale'));

        // 2. Submit Contact Form
        $contactResponse = $this->post(route('contact.submit'), [
            'name' => 'Visitor One',
            'email' => 'visitor@example.com',
            'subject' => 'Inquiry about service',
            'message' => 'Hello, I love your platform!',
        ]);
        $contactResponse->assertRedirect();
        $this->assertDatabaseHas('contacts', ['email' => 'visitor@example.com']);

        // 3. Newsletter subscription
        $newsResponse = $this->post(route('newsletter.subscribe'), [
            'email' => 'newsletter@example.com',
        ]);
        $newsResponse->assertRedirect();
        $this->assertDatabaseHas('newsletter_subscribers', ['email' => 'newsletter@example.com']);

        // 4. Cart Add, Update, Remove
        $addCart = $this->post(route('cart.add'), [
            'meal_id' => $this->meal->id,
            'quantity' => 2,
        ]);
        $addCart->assertRedirect();
        $this->get('/cart')->assertOk()->assertSee('Delicious Maqluba');

        $updateCart = $this->patch(route('cart.update'), [
            'meal_id' => $this->meal->id,
            'quantity' => 3,
        ]);
        $updateCart->assertRedirect();

        $removeCart = $this->post(route('cart.remove'), [
            'meal_id' => $this->meal->id,
        ]);
        $removeCart->assertRedirect();
    }

    public function test_customer_pages_and_features(): void
    {
        // Address for user
        $address = Address::create([
            'user_id' => $this->user->id,
            'address' => 'Ramallah Main Street, Building 4',
            'city' => 'Ramallah',
            'type' => 'home',
            'is_default' => true,
        ]);

        // Place an order via cart checkout
        $this->actingAs($this->user)->post(route('cart.add'), [
            'meal_id' => $this->meal->id,
            'quantity' => 2,
        ]);

        $checkoutResponse = $this->actingAs($this->user)->post(route('orders.store'), [
            'address_id' => $address->id,
            'phone' => '+970599123456',
            'payment_method' => 'cash',
        ]);
        $checkoutResponse->assertRedirect();

        $order = Order::where('user_id', $this->user->id)->first();
        $this->assertNotNull($order);

        // Customer orders index & show
        $this->actingAs($this->user)->get('/orders')->assertOk()->assertSee((string) $order->id);
        $this->actingAs($this->user)->get('/orders/'.$order->id)->assertOk();

        // Customer cancel order
        $cancelResponse = $this->actingAs($this->user)->post(route('orders.cancel', $order->id));
        $cancelResponse->assertRedirect();
        $this->assertEquals('cancelled', $order->fresh()->status);

        // Customer reorder
        $reorderResponse = $this->actingAs($this->user)->post(route('orders.reorder', $order->id));
        $reorderResponse->assertRedirect(route('cart.index'));

        // Favourites: index, toggle meal, toggle chef
        $this->actingAs($this->user)->get(route('favourites.index'))->assertOk();
        $this->actingAs($this->user)->post(route('favourites.meal.toggle', $this->meal->id))->assertRedirect();
        $this->actingAs($this->user)->post(route('favourites.chef.toggle', $this->chef->id))->assertRedirect();

        // Profile: edit, update, address add, address delete
        $this->actingAs($this->user)->get(route('profile.edit'))->assertOk();
        $this->actingAs($this->user)->patch(route('profile.update'), [
            'name' => 'Updated User Name',
            'email' => $this->user->email,
            'phone' => '0599123456',
        ])->assertRedirect(route('profile.edit'));

        $newAddressResponse = $this->actingAs($this->user)->post(route('profile.address.store'), [
            'type' => 'work',
            'address' => 'Al-Masyoun, Near Cultural Palace',
            'city' => 'Ramallah',
        ]);
        $newAddressResponse->assertRedirect();
        $newAddress = Address::where('address', 'Al-Masyoun, Near Cultural Palace')->first();
        $this->assertNotNull($newAddress);

        $this->actingAs($this->user)->delete(route('profile.address.destroy', $newAddress->id))->assertRedirect();

        // Chef Application
        $this->actingAs($this->user)->get(route('chef.apply'))->assertOk();
        $this->actingAs($this->user)->post(route('chef.apply.store'), [
            'kitchen_name' => 'New Homemade Treats',
            'phone' => '0598765432',
            'address' => 'Nablus Old City',
            'specialty' => 'Palestinian Pastries',
            'bio' => 'Passionate about authentic flavours and fresh ingredients.',
        ])->assertRedirect();

        // Rate meal on delivered order
        $deliveredOrder = Order::create([
            'user_id' => $this->user->id,
            'chef_id' => $this->chef->id,
            'status' => 'delivered',
            'subtotal' => 50.00,
            'delivery_fee' => 5.00,
            'total' => 55.00,
            'payment_method' => 'cash',
            'payment_status' => 'paid',
            'address' => 'Ramallah',
        ]);
        OrderItem::create([
            'order_id' => $deliveredOrder->id,
            'meal_id' => $this->meal->id,
            'quantity' => 2,
            'price' => 25.00,
        ]);

        $rateResponse = $this->actingAs($this->user)->post(route('meals.rate', $this->meal->id), [
            'star' => 5,
            'body' => 'Exceptional food and swift delivery!',
        ]);
        $rateResponse->assertRedirect();
        $this->assertDatabaseHas('ratings', [
            'user_id' => $this->user->id,
            'meal_id' => $this->meal->id,
            'star' => 5,
        ]);
    }

    public function test_chef_pages_and_features(): void
    {
        Storage::fake('public');

        // Chef Dashboard & Earnings
        $this->actingAs($this->chef)->get(route('chef.dashboard'))->assertOk();
        $this->actingAs($this->chef)->get(route('chef.earnings'))->assertOk();
        $this->actingAs($this->chef)->get(route('chef.orders.export'))->assertOk();

        // Update capacity
        $this->actingAs($this->chef)->post(route('chef.capacity.update'), [
            'daily_capacity' => 35,
        ])->assertRedirect();
        $this->assertEquals(35, $this->chef->fresh()->daily_capacity);

        // Meals CRUD
        $this->actingAs($this->chef)->get(route('chef.meals.index'))->assertOk();
        $this->actingAs($this->chef)->get(route('chef.meals.create'))->assertOk();

        $createMeal = $this->actingAs($this->chef)->post(route('chef.meals.store'), [
            'name' => 'Chef Musakhan Roll',
            'category_id' => $this->category->id,
            'description' => 'Crispy sumac chicken rolls',
            'price' => 18.00,
            'preparation_time' => 30,
            'is_available' => 1,
            'image' => UploadedFile::fake()->image('musakhan.jpg'),
        ]);
        $createMeal->assertRedirect();
        $newMeal = Meal::where('name', 'Chef Musakhan Roll')->first();
        $this->assertNotNull($newMeal);

        $this->actingAs($this->chef)->get(route('chef.meals.edit', $newMeal->id))->assertOk();

        $this->actingAs($this->chef)->put(route('chef.meals.update', $newMeal->id), [
            'name' => 'Chef Musakhan Roll Updated',
            'category_id' => $this->category->id,
            'description' => 'Updated delicious sumac rolls',
            'price' => 20.00,
            'preparation_time' => 35,
            'is_available' => 1,
        ])->assertRedirect();

        // Chef Orders list & update status
        $chefOrder = Order::create([
            'user_id' => $this->user->id,
            'chef_id' => $this->chef->id,
            'status' => 'pending',
            'subtotal' => 20.00,
            'delivery_fee' => 5.00,
            'total' => 25.00,
            'payment_method' => 'cash',
            'payment_status' => 'pending',
            'address' => 'Ramallah',
        ]);
        OrderItem::create([
            'order_id' => $chefOrder->id,
            'meal_id' => $newMeal->id,
            'quantity' => 1,
            'price' => 20.00,
        ]);

        $this->actingAs($this->chef)->get(route('chef.orders.index'))->assertOk();
        $this->actingAs($this->chef)->get(route('chef.orders.show', $chefOrder->id))->assertOk();

        $statusUpdate = $this->actingAs($this->chef)->patch(route('chef.orders.update-status', $chefOrder->id), [
            'status' => 'accepted',
        ]);
        $statusUpdate->assertRedirect();
        $this->assertEquals('accepted', $chefOrder->fresh()->status);

        // Delete meal
        $this->actingAs($this->chef)->delete(route('chef.meals.destroy', $newMeal->id))->assertRedirect();
    }

    public function test_admin_pages_and_features(): void
    {
        Storage::fake('public');

        // Admin Dashboard & Audit Log
        $this->actingAs($this->admin)->get(route('dashboard'))->assertOk();
        $this->actingAs($this->admin)->get(route('dashboard.audit-log.index'))->assertOk();

        // 1. Category CRUD
        $this->actingAs($this->admin)->get(route('dashboard.category.index'))->assertOk();
        $this->actingAs($this->admin)->get(route('dashboard.category.create'))->assertOk();

        $catStore = $this->actingAs($this->admin)->post(route('dashboard.category.store'), [
            'name' => 'Soups & Appetizers',
            'description' => 'Warm and comforting soups',
        ]);
        $catStore->assertRedirect();
        $cat = Category::where('name', 'Soups & Appetizers')->first();
        $this->assertNotNull($cat);

        $this->actingAs($this->admin)->get(route('dashboard.category.edit', $cat->id))->assertOk();
        $this->actingAs($this->admin)->put(route('dashboard.category.update', $cat->id), [
            'name' => 'Soups & Appetizers Updated',
            'description' => 'Updated description',
        ])->assertRedirect();
        $this->actingAs($this->admin)->delete(route('dashboard.category.destroy', $cat->id))->assertRedirect();

        // 2. Users CRUD
        $this->actingAs($this->admin)->get(route('dashboard.users.index'))->assertOk();
        $this->actingAs($this->admin)->get(route('dashboard.users.create'))->assertOk();

        $userStore = $this->actingAs($this->admin)->post(route('dashboard.users.store'), [
            'name' => 'Sample Chef User',
            'email' => 'samplechef@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'phone' => '0599998877',
            'role' => 'chef',
            'kitchen_name' => 'Sample Kitchen',
        ]);
        $userStore->assertRedirect();
        $createdUser = User::where('email', 'samplechef@example.com')->first();
        $this->assertNotNull($createdUser);

        $this->actingAs($this->admin)->get(route('dashboard.users.show', $createdUser->id))->assertOk();
        $this->actingAs($this->admin)->get(route('dashboard.users.edit', $createdUser->id))->assertOk();
        $this->actingAs($this->admin)->put(route('dashboard.users.update', $createdUser->id), [
            'name' => 'Sample Chef User Renamed',
            'email' => $createdUser->email,
            'phone' => $createdUser->phone,
        ])->assertRedirect();
        $this->actingAs($this->admin)->delete(route('dashboard.users.destroy', $createdUser->id))->assertRedirect();

        // 3. Meals CRUD
        $this->actingAs($this->admin)->get(route('dashboard.meals.index'))->assertOk();
        $this->actingAs($this->admin)->get(route('dashboard.meals.create'))->assertOk();

        $mealStore = $this->actingAs($this->admin)->post(route('dashboard.meals.store'), [
            'chef_id' => $this->chef->id,
            'category_id' => $this->category->id,
            'name' => 'Admin Special Kabab',
            'description' => 'Charcoal grilled kebabs',
            'price' => 30.00,
            'preparation_time' => 40,
            'is_available' => 1,
            'image' => UploadedFile::fake()->image('kabab.jpg'),
        ]);
        $mealStore->assertRedirect();
        $createdMeal = Meal::where('name', 'Admin Special Kabab')->first();
        $this->assertNotNull($createdMeal);

        $this->actingAs($this->admin)->get(route('dashboard.meals.show', $createdMeal->id))->assertOk();
        $this->actingAs($this->admin)->get(route('dashboard.meals.edit', $createdMeal->id))->assertOk();
        $this->actingAs($this->admin)->put(route('dashboard.meals.update', $createdMeal->id), [
            'name' => 'Admin Special Kabab Edited',
            'chef_id' => $this->chef->id,
            'category_id' => $this->category->id,
            'description' => 'Updated kebabs',
            'price' => 32.00,
            'preparation_time' => 45,
            'is_available' => 1,
        ])->assertRedirect();
        $this->actingAs($this->admin)->delete(route('dashboard.meals.destroy', $createdMeal->id))->assertRedirect();

        // 4. Orders management & CSV export
        $adminOrder = Order::create([
            'user_id' => $this->user->id,
            'chef_id' => $this->chef->id,
            'status' => 'pending',
            'subtotal' => 25.00,
            'delivery_fee' => 5.00,
            'total' => 30.00,
            'payment_method' => 'cash',
            'payment_status' => 'pending',
            'address' => 'Ramallah Center',
        ]);

        $this->actingAs($this->admin)->get(route('dashboard.orders.index'))->assertOk();
        $this->actingAs($this->admin)->get(route('dashboard.orders.export-csv'))->assertOk();
        $this->actingAs($this->admin)->get(route('dashboard.orders.show', $adminOrder->id))->assertOk();
        $this->actingAs($this->admin)->get(route('dashboard.orders.edit', $adminOrder->id))->assertOk();

        // 5. Ratings list & delete
        $rating = Rating::create([
            'user_id' => $this->user->id,
            'chef_id' => $this->chef->id,
            'meal_id' => $this->meal->id,
            'star' => 4,
            'body' => 'Very good taste',
        ]);
        $this->actingAs($this->admin)->get(route('dashboard.ratings.index'))->assertOk();
        $this->actingAs($this->admin)->delete(route('dashboard.ratings.destroy', $rating->id))->assertRedirect();

        // 6. Contacts list, show, toggle read, delete
        $contact = Contact::create([
            'name' => 'Test Sender',
            'email' => 'sender@example.com',
            'title' => 'Question',
            'message' => 'Test message body',
            'is_read' => false,
        ]);
        $this->actingAs($this->admin)->get(route('dashboard.contacts.index'))->assertOk();
        // Visiting show marks it as read
        $this->actingAs($this->admin)->get(route('dashboard.contacts.show', $contact->id))->assertOk();
        $this->assertTrue((bool) $contact->fresh()->is_read);
        // Toggling read toggles it to unread
        $this->actingAs($this->admin)->patch(route('dashboard.contacts.toggle-read', $contact->id))->assertRedirect();
        $this->assertFalse((bool) $contact->fresh()->is_read);
        $this->actingAs($this->admin)->delete(route('dashboard.contacts.destroy', $contact->id))->assertRedirect();

        // 7. Chef Applications list, approve, reject
        $app1 = ChefApplication::create([
            'user_id' => $this->user->id,
            'kitchen_name' => 'Mama Sweets',
            'phone' => '0599112233',
            'address' => 'Bethlehem',
            'specialty' => 'Desserts',
            'bio' => 'Traditional knafeh and sweets',
            'status' => 'pending',
        ]);
        $this->actingAs($this->admin)->get(route('dashboard.chef-applications.index'))->assertOk();
        $this->actingAs($this->admin)->post(route('dashboard.chef-applications.approve', $app1->id))->assertRedirect();
        $this->assertEquals('approved', $app1->fresh()->status);
        $this->assertEquals('chef', $this->user->fresh()->role);

        $otherUser = User::factory()->create(['role' => 'user']);
        $app2 = ChefApplication::create([
            'user_id' => $otherUser->id,
            'kitchen_name' => 'Quick Bites',
            'phone' => '0599445566',
            'address' => 'Hebron',
            'specialty' => 'Snacks',
            'bio' => 'Fast finger food',
            'status' => 'pending',
        ]);
        $this->actingAs($this->admin)->post(route('dashboard.chef-applications.reject', $app2->id))->assertRedirect();
        $this->assertEquals('rejected', $app2->fresh()->status);
    }
}
