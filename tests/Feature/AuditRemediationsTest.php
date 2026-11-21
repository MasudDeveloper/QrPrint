<?php

namespace Tests\Feature;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuditRemediationsTest extends TestCase
{
    use RefreshDatabase;

    private function createShop(bool $isExpired = false): array
    {
        $user = User::factory()->create([
            'email' => 'shop_' . uniqid() . '@example.com',
            'password' => bcrypt('StrongPass123!'),
        ]);

        $shop = Shop::create([
            'user_id' => $user->id,
            'name' => 'City Cyber Cafe',
            'slug' => 'city-cyber-' . uniqid(),
            'phone' => '01711223344',
            'bkash_number' => '01711223344',
            'rate_bw_single' => 4.00,
            'rate_bw_double' => 6.00,
            'rate_color_single' => 10.00,
            'rate_color_double' => 16.00,
            'is_office_mode' => false,
            'is_active' => true,
            'subscription_plan' => 'free_trial',
            'subscription_expires_at' => $isExpired ? now()->subDays(5) : now()->addHours(24),
            'api_token' => 'token_' . uniqid(),
        ]);

        return [$user, $shop];
    }

    public function test_security_headers_are_present_on_all_responses(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->assertHeader('Permissions-Policy', 'camera=(self), microphone=(), geolocation=()');
    }

    public function test_login_throttling_locks_out_after_too_many_failed_attempts(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', [
                'email' => 'hacker@example.com',
                'password' => 'wrongpass',
            ]);
        }

        // 6th attempt should be blocked with rate limiting message
        $response = $this->post('/login', [
            'email' => 'hacker@example.com',
            'password' => 'wrongpass',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertStringContainsString('খুব বেশি সংখ্যক ভুল চেষ্টা', session('errors')->first('email'));
    }

    public function test_expired_shop_is_blocked_from_agent_polling_and_sms_listener(): void
    {
        [$user, $shop] = $this->createShop(true); // Expired 5 days ago

        // 1. Windows Agent polling is blocked with 403
        $agentResponse = $this->withHeaders([
            'X-Agent-Token' => $shop->api_token,
        ])->getJson('/api/agent/jobs');

        $agentResponse->assertStatus(403);
        $agentResponse->assertJsonFragment(['status' => 'subscription_expired']);

        // 2. Android SMS Listener is blocked with 403
        $smsResponse = $this->withHeaders([
            'X-App-Token' => $shop->api_token,
        ])->postJson('/api/webhook/sms-payment', [
            'trx_id' => 'BKH1234567',
            'amount' => 50,
            'sender_phone' => '01711223344',
            'method' => 'bkash',
        ]);

        $smsResponse->assertStatus(403);
        $smsResponse->assertJsonFragment(['status' => 'subscription_expired']);

        // 3. Customer portal is blocked with 403
        $customerResponse = $this->get('/print/' . $shop->slug);
        $customerResponse->assertStatus(403);
    }

    public function test_sitemap_xml_returns_valid_xml(): void
    {
        $response = $this->get('/sitemap.xml');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/xml');
        $response->assertSee('<urlset', false);
        $response->assertSee('/privacy-policy', false);
        $response->assertSee('/terms', false);
        $response->assertSee('/refund-policy', false);
    }

    public function test_privacy_terms_and_refund_pages_return_200(): void
    {
        $this->get('/privacy-policy')->assertStatus(200)->assertSee('গোপনীয়তা নীতি');
        $this->get('/terms')->assertStatus(200)->assertSee('শর্তাবলি');
        $this->get('/refund-policy')->assertStatus(200)->assertSee('রিফান্ড');
    }

    public function test_custom_branded_404_page_renders(): void
    {
        $response = $this->get('/non-existent-page-xyz');
        $response->assertStatus(404);
        $response->assertSee('পেজটি খুঁজে পাওয়া যায়নি');
        $response->assertSee('PrintSheba');
    }

    public function test_bangla_helper_formats_dates_and_numbers_accurately(): void
    {
        $this->assertEquals('০', \App\Helpers\BanglaHelper::bnNumber(0));
        $this->assertEquals('১২৩৪৫', \App\Helpers\BanglaHelper::bnNumber(12345));
        $this->assertEquals('৳ ৯৯৯', \App\Helpers\BanglaHelper::bnCurrency(999));
        
        $date = \Carbon\Carbon::create(2026, 10, 26);
        $this->assertEquals('২৬ অক্টোবর, ২০২৬', \App\Helpers\BanglaHelper::bnDate($date));
    }
}

