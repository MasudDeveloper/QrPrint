<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Shop;
use App\Models\PrintJob;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class QuickPrintTest extends TestCase
{
    use RefreshDatabase;
    public function test_pwa_manifest_exists_and_has_share_target()
    {
        $manifestPath = public_path('manifest.json');
        $this->assertFileExists($manifestPath);

        $data = json_decode(file_get_contents($manifestPath), true);
        $this->assertIsArray($data);
        $this->assertEquals('/shop/share-target', $data['share_target']['action']);
        $this->assertEquals('POST', $data['share_target']['method']);
        $this->assertEquals('multipart/form-data', $data['share_target']['enctype']);
    }

    public function test_pwa_service_worker_file_exists()
    {
        $swPath = public_path('sw.js');
        $this->assertFileExists($swPath);
        $content = file_get_contents($swPath);
        $this->assertStringContainsString('printsheba-', $content);
    }

    public function test_share_target_receives_file_and_redirects_303()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('sample_doc.pdf', 120, 'application/pdf');

        $response = $this->post('/shop/share-target', [
            'shared_files' => [$file],
        ]);

        $response->assertStatus(303);
        $this->assertTrue(str_contains($response->headers->get('Location'), '/shop/quick-print'));
    }

    public function test_shopkeeper_can_view_quick_print_preview_of_shared_file()
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $shop = Shop::create([
            'user_id' => $user->id,
            'name' => 'Preview Test Shop',
            'slug' => 'test-prev-' . time(),
            'phone' => '01711111111',
            'is_active' => true,
            'is_lifetime' => true,
            'api_token' => \Illuminate\Support\Str::random(40),
        ]);

        $file = UploadedFile::fake()->create('nid_scan.pdf', 300, 'application/pdf');

        $shareResponse = $this->post('/shop/share-target', [
            'shared_files' => [$file],
        ]);

        $shareResponse->assertStatus(303);
        $redirectUrl = $shareResponse->headers->get('Location');

        // Follow the redirect as the authenticated shopkeeper
        $previewResponse = $this->actingAs($user)->get($redirectUrl);
        $previewResponse->assertStatus(200);
        $previewResponse->assertSee('nid_scan.pdf');
        $previewResponse->assertSee('সরাসরি দোকানে প্রিন্ট করুন');
    }

    public function test_shopkeeper_can_submit_quick_print()
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $shop = Shop::create([
            'user_id' => $user->id,
            'name' => 'Test Quick Print Shop',
            'slug' => 'test-quick-' . time(),
            'phone' => '01700000000',
            'is_active' => true,
            'is_lifetime' => true,
            'api_token' => \Illuminate\Support\Str::random(40),
        ]);

        $file = UploadedFile::fake()->create('contract.pdf', 250, 'application/pdf');

        $response = $this->actingAs($user)->post(route('shop.quick_print.submit'), [
            'document' => $file,
            'color_mode' => 'bw',
            'copies' => 2,
            'paper_size' => 'A4',
            'is_duplex' => 0,
            'customer_label' => 'WhatsApp Customer',
        ]);

        $response->assertRedirect(route('shop.dashboard'));

        $this->assertDatabaseHas('print_jobs', [
            'shop_id' => $shop->id,
            'customer_name' => 'WhatsApp Customer',
            'copies' => 2,
            'color_mode' => 'bw',
            'payment_status' => 'paid',
            'print_status' => 'queued',
        ]);
    }
}
