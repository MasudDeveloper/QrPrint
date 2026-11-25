<?php

namespace Tests\Feature;

use App\Models\Shop;
use App\Models\ShopSubscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RealTimeSubscriptionNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_live_subscriptions_endpoint_returns_pending_shop_and_office_requests()
    {
        $admin = User::factory()->create(['role' => 'super_admin']);
        $shopUser = User::factory()->create(['role' => 'shop_owner']);
        $shop = Shop::create([
            'user_id' => $shopUser->id,
            'name' => 'Realtime Test Shop',
            'slug' => 'realtime-test-shop',
            'phone' => '01711111111',
            'is_office_mode' => false,
            'is_active' => true,
        ]);

        $officeUser = User::factory()->create(['role' => 'shop_owner']);
        $office = Shop::create([
            'user_id' => $officeUser->id,
            'name' => 'Realtime Test Office',
            'slug' => 'realtime-test-office',
            'phone' => '01822222222',
            'is_office_mode' => true,
            'is_active' => true,
        ]);

        $sub1 = ShopSubscription::create([
            'shop_id' => $shop->id,
            'plan' => 'monthly_starter',
            'plan_name' => 'মাসিক স্টার্টার',
            'amount' => 99,
            'payment_method' => 'bkash',
            'sender_number' => '01711111111',
            'transaction_id' => 'TRXSHOP01',
            'status' => 'pending',
        ]);

        $sub2 = ShopSubscription::create([
            'shop_id' => $office->id,
            'plan' => 'office_basic_monthly',
            'plan_name' => 'অফিস বেসিক মাসিক',
            'amount' => 499,
            'payment_method' => 'nagad',
            'sender_number' => '01822222222',
            'transaction_id' => 'TRXOFFICE02',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)->getJson(route('admin.subscriptions.live'));

        $response->assertStatus(200)
            ->assertJson([
                'pending_count' => 2,
                'latest_pending_id' => $sub2->id,
                'latest_pending' => [
                    'id' => $sub2->id,
                    'shop_name' => 'Realtime Test Office',
                    'is_office_mode' => true,
                ],
            ])
            ->assertJsonCount(2, 'subscriptions');
    }

    public function test_admin_ajax_approve_returns_json_and_shop_status_updates_realtime()
    {
        $admin = User::factory()->create(['role' => 'super_admin']);
        $shopUser = User::factory()->create(['role' => 'shop_owner']);
        $shop = Shop::create([
            'user_id' => $shopUser->id,
            'name' => 'Instant Approve Shop',
            'slug' => 'instant-approve-shop',
            'phone' => '01733333333',
            'is_office_mode' => false,
            'is_active' => true,
            'subscription_expires_at' => now()->subDay(),
        ]);

        $sub = ShopSubscription::create([
            'shop_id' => $shop->id,
            'plan' => 'yearly_pro',
            'plan_name' => 'ইয়ার্লি প্রো',
            'amount' => 999,
            'payment_method' => 'rocket',
            'sender_number' => '01733333333',
            'transaction_id' => 'TRXFAST99',
            'status' => 'pending',
        ]);

        // Before approval: shop status endpoint shows pending and inactive
        $beforeRes = $this->actingAs($shopUser)->getJson(route('shop.subscription.status'));
        $beforeRes->assertStatus(200)
            ->assertJson([
                'has_pending' => true,
                'status' => 'pending',
                'is_active' => false,
            ]);

        // Admin approves via AJAX JSON request
        $approveRes = $this->actingAs($admin)->postJson(route('admin.subscriptions.approve', $sub));
        $approveRes->assertStatus(200)
            ->assertJson([
                'success' => true,
                'pending_count' => 0,
            ]);

        // After approval: shop status endpoint immediately reflects approved & active + history
        $shopUser->refresh();
        $afterRes = $this->actingAs($shopUser)->getJson(route('shop.subscription.status'));
        $afterRes->assertStatus(200)
            ->assertJson([
                'has_pending' => false,
                'status' => 'approved',
                'is_active' => true,
            ])
            ->assertJsonCount(1, 'subscriptions_history');
    }
}
