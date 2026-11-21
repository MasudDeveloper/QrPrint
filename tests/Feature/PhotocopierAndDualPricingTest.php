<?php

namespace Tests\Feature;

use App\Models\Shop;
use App\Models\User;
use App\Models\Printer;
use App\Models\PrintJob;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class PhotocopierAndDualPricingTest extends TestCase
{
    use RefreshDatabase;

    private function createShop(bool $isOffice = false): array
    {
        $user = User::factory()->create([
            'email' => ($isOffice ? 'office' : 'shop') . '_' . uniqid() . '@example.com',
        ]);

        $shop = Shop::create([
            'user_id' => $user->id,
            'name' => $isOffice ? 'Apex HQ Office' : 'Dhaka Modern Press',
            'slug' => ($isOffice ? 'apex-hq-' : 'dhaka-press-') . uniqid(),
            'phone' => '01711223344',
            'bkash_number' => $isOffice ? null : '01711223344',
            'nagad_number' => $isOffice ? null : '01811223344',
            'rate_bw_single' => $isOffice ? 0.00 : 4.00,
            'rate_bw_double' => $isOffice ? 0.00 : 6.00,
            'rate_photocopy_single' => $isOffice ? 0.00 : 2.00,
            'rate_photocopy_double' => $isOffice ? 0.00 : 3.00,
            'rate_color_single' => $isOffice ? 0.00 : 10.00,
            'rate_color_double' => $isOffice ? 0.00 : 16.00,
            'has_color_print' => true,
            'has_duplex_print' => true,
            'has_photocopier' => true,
            'has_passport_photo' => false,
            'has_nid_print' => true,
            'is_office_mode' => $isOffice,
            'is_active' => true,
            'subscription_plan' => 'free_trial',
            'subscription_expires_at' => now()->addDays(30),
        ]);

        return [$user, $shop];
    }

    public function test_shopkeeper_can_configure_photocopier_service_and_dual_bw_rates(): void
    {
        [$user, $shop] = $this->createShop(false);

        $response = $this->actingAs($user)->post(route('shop.rates.update'), [
            'name' => 'Dhaka Modern Press Updated',
            'phone' => '01711223344',
            'bkash_number' => '01711223344',
            'nagad_number' => '01811223344',
            'rate_bw_single' => 5.00,
            'rate_bw_double' => 8.00,
            'rate_photocopy_single' => 2.50,
            'rate_photocopy_double' => 4.00,
            'rate_color_single' => 12.00,
            'rate_color_double' => 20.00,
            'rate_photo_passport' => 30.00,
            'rate_nid_bw' => 10.00,
            'rate_nid_color' => 20.00,
            'has_photocopier' => '1',
            'has_color_print' => '1',
            'has_duplex_print' => '1',
        ]);

        $response->assertRedirect();
        $shop->refresh();

        $this->assertTrue((bool) $shop->has_photocopier);
        $this->assertEquals(5.00, (float) $shop->rate_bw_single);
        $this->assertEquals(2.50, (float) $shop->rate_photocopy_single);
        $this->assertEquals(4.00, (float) $shop->rate_photocopy_double);
    }

    public function test_office_mode_can_enable_photocopier_with_zero_rates(): void
    {
        [$user, $shop] = $this->createShop(true);

        $response = $this->actingAs($user)->post(route('shop.rates.update'), [
            'name' => 'Apex HQ Updated',
            'phone' => '01799887766',
            'has_photocopier' => '1',
            'has_color_print' => '1',
            'has_duplex_print' => '1',
        ]);

        $response->assertRedirect();
        $shop->refresh();

        $this->assertTrue((bool) $shop->has_photocopier);
        $this->assertEquals(0.00, (float) $shop->rate_photocopy_single);
        $this->assertEquals(0.00, (float) $shop->rate_photocopy_double);
    }

    public function test_customer_bill_calculated_with_cheaper_photocopier_rate_when_selected(): void
    {
        [$user, $shop] = $this->createShop(false);
        // rate_photocopy_single is 2.00, rate_bw_single is 4.00

        $file = UploadedFile::fake()->create('report.pdf', 100, 'application/pdf');

        $response = $this->post(route('customer.submit', $shop->slug), [
            'document' => $file,
            'color_mode' => 'photocopy',
            'copies' => 2,
            'total_pages' => 5,
            'payment_method' => 'cash',
        ]);

        $this->assertDatabaseHas('print_jobs', [
            'shop_id' => $shop->id,
            'color_mode' => 'photocopy',
            'copies' => 2,
            'total_pages' => 5,
            'total_amount' => 20.00, // 5 pages * 2.00 * 2 copies = 20.00
            'payment_method' => 'cash',
        ]);
    }

    public function test_customer_bill_calculated_with_laser_bw_rate_when_bw_selected(): void
    {
        [$user, $shop] = $this->createShop(false);
        // rate_bw_single is 4.00

        $file = UploadedFile::fake()->create('contract.pdf', 100, 'application/pdf');

        $response = $this->post(route('customer.submit', $shop->slug), [
            'document' => $file,
            'color_mode' => 'bw',
            'copies' => 2,
            'total_pages' => 5,
            'payment_method' => 'cash',
        ]);

        $this->assertDatabaseHas('print_jobs', [
            'shop_id' => $shop->id,
            'color_mode' => 'bw',
            'copies' => 2,
            'total_pages' => 5,
            'total_amount' => 40.00, // 5 pages * 4.00 * 2 copies = 40.00
            'payment_method' => 'cash',
        ]);
    }

    public function test_agent_heartbeat_syncs_photocopier_printer_and_enables_service(): void
    {
        [$user, $shop] = $this->createShop(false);
        $shop->update(['has_photocopier' => false]);

        // 1. Agent reports Toshiba Photocopier machine
        $response = $this->withHeaders([
            'X-Agent-Token' => $shop->api_token,
        ])->postJson('/api/agent/heartbeat', [
            'bw_printer' => 'HP LaserJet Pro M404dn',
            'color_printer' => 'Epson L805',
            'photocopy_printer' => 'Toshiba e-Studio 282',
            'printers' => [
                'HP LaserJet Pro M404dn',
                'Epson L805',
                'Toshiba e-Studio 282',
            ],
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('printers', [
            'shop_id' => $shop->id,
            'system_printer_name' => 'Toshiba e-Studio 282',
            'type' => 'photocopy',
            'status' => 'online',
        ]);

        $shop->refresh();
        $this->assertTrue((bool) $shop->has_photocopier);

        // 2. Agent reports no photocopier machine
        $response2 = $this->withHeaders([
            'X-Agent-Token' => $shop->api_token,
        ])->postJson('/api/agent/heartbeat', [
            'bw_printer' => 'HP LaserJet Pro M404dn',
            'color_printer' => 'Epson L805',
            'photocopy_printer' => '-- কোনো ফটোকপি মেশিন নেই --',
            'printers' => [
                'HP LaserJet Pro M404dn',
                'Epson L805',
            ],
        ]);

        $response2->assertStatus(200);

        $this->assertDatabaseHas('printers', [
            'shop_id' => $shop->id,
            'system_printer_name' => 'Toshiba e-Studio 282',
            'type' => 'photocopy',
            'status' => 'offline',
        ]);

        $shop->refresh();
        $this->assertFalse((bool) $shop->has_photocopier);
    }

    public function test_office_employee_can_submit_print_job_with_photocopier_without_payment(): void
    {
        [$user, $shop] = $this->createShop(true);

        $file = UploadedFile::fake()->create('office_memo.pdf', 100, 'application/pdf');

        $response = $this->post(route('customer.office_submit', $shop->slug), [
            'document' => $file,
            'color_mode' => 'photocopy',
            'copies' => 3,
            'total_pages' => 4,
            'employee_name' => 'Md. Farhan (Admin)',
            'pickup_reference' => 'Room 402',
        ]);

        $this->assertDatabaseHas('print_jobs', [
            'shop_id' => $shop->id,
            'color_mode' => 'photocopy',
            'copies' => 3,
            'total_pages' => 4,
            'total_amount' => 0.00,
            'payment_status' => 'paid',
            'is_office_job' => true,
            'customer_name' => 'Md. Farhan (Admin)',
            'pickup_reference' => 'Room 402',
        ]);
    }
}

