<?php

namespace Tests\Feature;

use App\Models\Shop;
use App\Models\User;
use App\Models\PrintJob;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SuspensionAndExpiryStatusTest extends TestCase
{
    use RefreshDatabase;

    public function test_suspended_shop_is_blocked_on_agent_poll_and_heartbeat(): void
    {
        $user = User::factory()->create();
        $shop = Shop::create([
            'user_id' => $user->id,
            'name' => 'Rizvi Office',
            'slug' => 'rizvi-office',
            'api_token' => 'shop_tok_test_suspension',
            'is_office_mode' => true,
            'is_active' => false, // Suspended by admin
            'subscription_expires_at' => now()->addDays(30),
        ]);

        // 1. Agent poll returns 403 suspended
        $pollRes = $this->withHeader('X-Agent-Token', $shop->api_token)->getJson('/api/agent/jobs');
        $pollRes->assertStatus(403);
        $pollRes->assertJsonFragment([
            'status' => 'suspended',
            'error' => 'account_suspended',
        ]);

        // 2. Agent heartbeat returns 403 suspended
        $hbRes = $this->withHeader('X-Agent-Token', $shop->api_token)->postJson('/api/agent/heartbeat', [
            'printers' => ['Canon LBP6030'],
            'bw_printer' => 'Canon LBP6030',
        ]);
        $hbRes->assertStatus(403);
        $hbRes->assertJsonFragment([
            'status' => 'suspended',
            'error' => 'account_suspended',
        ]);
    }

    public function test_reactivated_shop_is_allowed_on_agent_poll_and_heartbeat(): void
    {
        $user = User::factory()->create();
        $shop = Shop::create([
            'user_id' => $user->id,
            'name' => 'Rizvi Office Active',
            'slug' => 'rizvi-office-active',
            'api_token' => 'shop_tok_test_reactivation',
            'is_office_mode' => true,
            'is_active' => true,
            'subscription_expires_at' => now()->addDays(30),
        ]);

        // 1. Agent poll returns 200 OK
        $pollRes = $this->withHeader('X-Agent-Token', $shop->api_token)->getJson('/api/agent/jobs');
        $pollRes->assertStatus(200);
        $pollRes->assertJsonFragment(['status' => 'ok']);

        // 2. Agent heartbeat returns 200 OK
        $hbRes = $this->withHeader('X-Agent-Token', $shop->api_token)->postJson('/api/agent/heartbeat', [
            'printers' => ['Canon LBP6030'],
            'bw_printer' => 'Canon LBP6030',
        ]);
        $hbRes->assertStatus(200);
        $hbRes->assertJsonFragment(['status' => 'ok']);
    }

    public function test_suspended_shop_displays_prominent_notice_on_dashboard_and_blocks_quick_print(): void
    {
        $user = User::factory()->create();
        $shop = Shop::create([
            'user_id' => $user->id,
            'name' => 'Corporate Alpha',
            'slug' => 'corp-alpha',
            'api_token' => 'shop_tok_corp_alpha',
            'is_office_mode' => true,
            'is_active' => false,
            'subscription_expires_at' => now()->addDays(10),
        ]);

        // Dashboard shows suspended banner
        $dashRes = $this->actingAs($user)->get('/shop/dashboard');
        $dashRes->assertStatus(200);
        $dashRes->assertSee('আপনার প্রতিষ্ঠানটি বর্তমানে স্থগিত (Suspended) রয়েছে!');
        $dashRes->assertSee('SUSPENDED');

        // Submitting quick print is rejected
        $submitRes = $this->actingAs($user)->post('/shop/quick-print/submit', [
            'color_mode' => 'bw',
            'copies' => 1,
        ]);
        $submitRes->assertSessionHas('error_alert');
    }

    public function test_customer_portal_shows_suspended_notice_when_shop_is_suspended(): void
    {
        $user = User::factory()->create();
        $shop = Shop::create([
            'user_id' => $user->id,
            'name' => 'Beta Office',
            'slug' => 'beta-office',
            'api_token' => 'shop_tok_beta',
            'is_office_mode' => true,
            'is_active' => false,
        ]);

        $res = $this->get('/office/beta-office');
        $res->assertStatus(403);
        $res->assertSee('স্থগিত (Suspended)');
    }
}

