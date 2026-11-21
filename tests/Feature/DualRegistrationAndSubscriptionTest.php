<?php

namespace Tests\Feature;

use App\Models\Shop;
use App\Models\User;
use App\Models\ShopSubscription;
use App\Models\Setting;
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
        $response->assertDontSee('অফিস মোডে কোনো পেমেন্ট অ্যাপ বা অ্যান্ড্রয়েড APK-র প্রয়োজন নেই');
        $response->assertDontSee('PrintSheba Payment (বিকাশ ও নগদ অটো-লিসেনার APK)');
        $response->assertDontSee('জিরো পেমেন্ট');
    }

    public function test_office_dashboard_and_poster_have_no_payment_traces(): void
    {
        $user = User::factory()->create(['role' => 'shop_owner']);
        $shop = Shop::create([
            'user_id' => $user->id,
            'name' => 'Apex Footwear HQ',
            'slug' => 'apex-hq',
            'phone' => '01711223344',
            'is_office_mode' => true,
            'subscription_plan' => 'office_basic_yearly',
        ]);

        // Dashboard check
        $dashResponse = $this->actingAs($user)->get(route('shop.dashboard'));
        $dashResponse->assertStatus(200);
        $dashResponse->assertDontSee('জিরো পেমেন্ট');
        $dashResponse->assertDontSee('লাইভ বিকাশ ও নগদ পেমেন্ট লগ');
        $dashResponse->assertDontSee('ক্যাশ পেলাম / প্রিন্ট');
        $dashResponse->assertDontSee('ফ্রি (অফিস)');

        // Poster check
        $posterResponse = $this->actingAs($user)->get(route('shop.qr_poster'));
        $posterResponse->assertStatus(200);
        $posterResponse->assertDontSee('জিরো পেমেন্ট');
        $posterResponse->assertDontSee('১০০% ফ্রি');
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

    public function test_user_can_purchase_subscription_with_rocket_and_valid_phone(): void
    {
        $user = User::factory()->create(['role' => 'shop_owner']);
        $shop = Shop::create([
            'user_id' => $user->id,
            'name' => 'Rocket Test Shop',
            'slug' => 'rocket-test-shop',
            'phone' => '01712345678',
            'is_office_mode' => false,
            'subscription_plan' => 'free_trial',
        ]);

        $response = $this->actingAs($user)->post(route('shop.subscription.purchase'), [
            'plan' => 'yearly_pro',
            'payment_method' => 'rocket',
            'sender_number' => '01712345678',
            'transaction_id' => 'rkt987654',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('shop_subscriptions', [
            'shop_id' => $shop->id,
            'plan' => 'yearly_pro',
            'payment_method' => 'rocket',
            'sender_number' => '01712345678',
            'transaction_id' => 'RKT987654',
            'status' => 'pending',
        ]);
    }

    public function test_subscription_validation_enforces_bangladeshi_phone_pattern(): void
    {
        $user = User::factory()->create(['role' => 'shop_owner']);
        $shop = Shop::create([
            'user_id' => $user->id,
            'name' => 'Validation Test Shop',
            'slug' => 'val-test-shop',
            'phone' => '01712345678',
            'is_office_mode' => false,
            'subscription_plan' => 'free_trial',
        ]);

        // Invalid phone number: not starting with 01[3-9] or wrong length
        $response = $this->actingAs($user)->post(route('shop.subscription.purchase'), [
            'plan' => 'yearly_pro',
            'payment_method' => 'bkash',
            'sender_number' => '02987654321', // landline pattern
            'transaction_id' => 'BKB123456',
        ]);

        $response->assertSessionHasErrors('sender_number');

        // Short TrxID (< 6 chars)
        $shortTrxResponse = $this->actingAs($user)->post(route('shop.subscription.purchase'), [
            'plan' => 'yearly_pro',
            'payment_method' => 'nagad',
            'sender_number' => '01712345678',
            'transaction_id' => '1234',
        ]);

        $shortTrxResponse->assertSessionHasErrors('transaction_id');
    }

    public function test_subscription_status_polling_returns_accurate_state(): void
    {
        $user = User::factory()->create(['role' => 'shop_owner']);
        $shop = Shop::create([
            'user_id' => $user->id,
            'name' => 'Polling Test Shop',
            'slug' => 'poll-test-shop',
            'phone' => '01712345678',
            'is_office_mode' => false,
            'subscription_plan' => 'free_trial',
        ]);

        $sub = ShopSubscription::create([
            'shop_id' => $shop->id,
            'plan' => 'yearly_pro',
            'plan_name' => 'ইয়ারলি প্রো (১ বছর - দোকান)',
            'amount' => 999.00,
            'payment_method' => 'rocket',
            'transaction_id' => 'RKT112233',
            'sender_number' => '01712345678',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($user)->getJson(route('shop.subscription.status'));
        $response->assertStatus(200);
        $response->assertJson([
            'has_pending' => true,
            'status' => 'pending',
            'subscription' => [
                'id' => $sub->id,
                'plan' => 'yearly_pro',
                'payment_method' => 'rocket',
                'transaction_id' => 'RKT112233',
            ],
        ]);
    }

    public function test_admin_settings_can_update_rocket_number(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post(route('admin.settings.update'), [
            'admin_bkash_number' => '01700000001',
            'admin_nagad_number' => '01700000002',
            'admin_rocket_number' => '01700000003',
            'admin_payment_instructions' => 'রকেটে টাকা পাঠানোর নির্দেশিকা',
        ]);

        $response->assertSessionHas('success');
        $this->assertEquals('01700000003', Setting::get('admin_rocket_number'));
    }
}

