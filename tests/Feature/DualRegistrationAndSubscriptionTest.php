<?php

namespace Tests\Feature;

use App\Models\Shop;
use App\Models\User;
use App\Models\ShopSubscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DualRegistrationAndSubscriptionTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_as_shop_partner(): void
    {
        $response = $this->post('/register', [
            'name' => 'Rahim Mia',
            'shop_name' => 'Rahim Enterprise',
            'phone' => '01711223344',
            'email' => 'rahim@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'account_type' => 'shop',
        ]);

        $response->assertRedirect(route('shop.dashboard'));
        $this->assertAuthenticated();

        $user = User::where('email', 'rahim@example.com')->first();
        $this->assertNotNull($user);

        $shop = Shop::where('user_id', $user->id)->first();
        $this->assertNotNull($shop);
        $this->assertEquals('Rahim Enterprise', $shop->name);
        $this->assertFalse((bool) $shop->is_office_mode);
        $this->assertEquals('free_trial', $shop->subscription_plan);
        $this->assertEquals(3.00, $shop->rate_bw_single);
    }

    public function test_user_can_register_as_corporate_office(): void
    {
        $response = $this->post('/register', [
            'name' => 'Meghna Corporate Admin',
            'office_name' => 'Meghna Group Headquarters',
            'phone' => '01899887766',
            'email' => 'corporate@meghna.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'account_type' => 'office',
        ]);

        $response->assertRedirect(route('shop.dashboard'));
        $this->assertAuthenticated();

        $user = User::where('email', 'corporate@meghna.com')->first();
        $this->assertNotNull($user);

        $shop = Shop::where('user_id', $user->id)->first();
        $this->assertNotNull($shop);
        $this->assertEquals('Meghna Group Headquarters', $shop->name);
        $this->assertTrue((bool) $shop->is_office_mode);
        $this->assertEquals('office_trial', $shop->subscription_plan);
        $this->assertEquals(0.00, $shop->rate_bw_single);
    }

    public function test_shopkeeper_sees_commercial_plans_on_subscription_page(): void
    {
        $user = User::factory()->create(['role' => 'shop_owner']);
        $shop = Shop::create([
            'user_id' => $user->id,
            'name' => 'Test Commercial Shop',
            'slug' => 'test-comm-shop',
            'phone' => '01700000000',
            'is_office_mode' => false,
            'subscription_plan' => 'free_trial',
        ]);

        $response = $this->actingAs($user)->get(route('shop.subscription'));

        $response->assertStatus(200);
        $response->assertSee('দোকানদার প্যাকেজ ও লাইসেন্স পোর্টাল');
        $response->assertSee('মান্থলি স্টার্টার');
        $response->assertSee('ইয়ারলি প্রো');
        $response->assertSee('লাইফটাইম আনলিমিটেড');
    }

    public function test_office_sees_corporate_plans_on_subscription_page(): void
    {
        $user = User::factory()->create(['role' => 'shop_owner']);
        $shop = Shop::create([
            'user_id' => $user->id,
            'name' => 'Test Corporate Office',
            'slug' => 'test-corp-office',
            'phone' => '01711111111',
            'is_office_mode' => true,
            'subscription_plan' => 'office_trial',
        ]);

        $response = $this->actingAs($user)->get(route('shop.subscription'));

        $response->assertStatus(200);
        $response->assertSee('অফিস ও কর্পোরেট লাইসেন্স পোর্টাল');
        $response->assertSee('অফিস বেসিক (১ মাস)');
        $response->assertSee('অফিস বেসিক (১ বছর)');
        $response->assertSee('কর্পোরেট প্রো (১ বছর)');
    }

    public function test_office_can_request_corporate_subscription_and_admin_approves(): void
    {
        $user = User::factory()->create(['role' => 'shop_owner']);
        $shop = Shop::create([
            'user_id' => $user->id,
            'name' => 'Apex Footwear IT Dept',
            'slug' => 'apex-it-dept',
            'phone' => '01912345678',
            'is_office_mode' => true,
            'subscription_plan' => 'office_trial',
        ]);

        // Office submits corporate subscription
        $subResponse = $this->actingAs($user)->post(route('shop.subscription.purchase'), [
            'plan' => 'office_basic_yearly',
            'payment_method' => 'bkash',
            'sender_number' => '01912345678',
            'transaction_id' => 'CORP_BKASH_999',
        ]);

        $subResponse->assertSessionHas('success');

        $subscription = ShopSubscription::where('shop_id', $shop->id)->first();
        $this->assertNotNull($subscription);
        $this->assertEquals('office_basic_yearly', $subscription->plan);
        $this->assertEquals(4500.00, $subscription->amount);

        // Super Admin approves subscription
        $admin = User::factory()->create(['role' => 'super_admin']);
        $approveResponse = $this->actingAs($admin)->post(route('admin.subscriptions.approve', $subscription));
        $approveResponse->assertSessionHas('success');

        $shop->refresh();
        $this->assertEquals('office_basic_yearly', $shop->subscription_plan);
        $this->assertTrue($shop->isSubscriptionActive());
        $this->assertGreaterThan(350, $shop->days_remaining);
    }

    public function test_office_agent_setup_does_not_show_payment_listener_apk(): void
    {
        $user = User::factory()->create(['role' => 'shop_owner']);
        $shop = Shop::create([
            'user_id' => $user->id,
            'name' => 'Walton Hi-Tech IT',
            'slug' => 'walton-it',
            'phone' => '01722334455',
            'is_office_mode' => true,
            'subscription_plan' => 'office_trial',
        ]);

        $response = $this->actingAs($user)->get(route('shop.agent_setup'));

        $response->assertStatus(200);
        $response->assertSee('অফিস সেন্ট্রাল অটো-প্রিন্ট এজেন্ট');
        $response->assertSee('অফিস মোডে কোনো পেমেন্ট অ্যাপ বা অ্যান্ড্রয়েড APK-র প্রয়োজন নেই');
        $response->assertDontSee('PrintSheba Payment (বিকাশ ও নগদ অটো-লিসেনার APK)');
    }

    public function test_office_settings_page_hides_rate_matrix_and_payment_numbers(): void
    {
        $user = User::factory()->create(['role' => 'shop_owner']);
        $shop = Shop::create([
            'user_id' => $user->id,
            'name' => 'Square Pharma Branch',
            'slug' => 'square-pharma',
            'phone' => '01855667788',
            'is_office_mode' => true,
            'subscription_plan' => 'office_trial',
        ]);

        $response = $this->actingAs($user)->get(route('shop.rates'));

        $response->assertStatus(200);
        $response->assertSee('অফিস সেটিংস ও প্রিন্টার কনফিগারেশন');
        $response->assertDontSee('বিকাশ পার্সোনাল নম্বর (bKash)');
        $response->assertDontSee('প্রতি পেজ প্রিন্ট রেট তালিকা (টাকা)');

        // Submit office settings without any rate fields
        $updateResponse = $this->actingAs($user)->post(route('shop.rates.update'), [
            'name' => 'Square Pharma Central',
            'phone' => '01855667799',
            'has_color_print' => 1,
            'has_duplex_print' => 1,
        ]);

        $updateResponse->assertSessionHas('success');
        $shop->refresh();
        $this->assertEquals('Square Pharma Central', $shop->name);
        $this->assertEquals(0.00, $shop->rate_bw_single);
    }
}

