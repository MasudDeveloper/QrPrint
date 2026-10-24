<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Shop;
use App\Models\PrintJob;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PrintSpoolingAndCancelTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $shop;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->shop = Shop::create([
            'user_id' => $this->user->id,
            'name' => 'Spool Test Shop',
            'slug' => 'spool-shop-' . uniqid(),
            'phone' => '01811111111',
            'api_token' => 'test_api_token_123',
            'is_active' => true,
            'is_lifetime' => true,
        ]);
    }

    public function test_agent_can_mark_job_failed_when_error_occurs()
    {
        $job = PrintJob::create([
            'shop_id' => $this->shop->id,
            'job_token' => 'TOK_FAIL_001',
            'original_name' => 'corrupt.pdf',
            'file_name' => 'corrupt.pdf',
            'file_path' => 'print_uploads/corrupt.pdf',
            'file_type' => 'pdf',
            'total_pages' => 1,
            'total_amount' => 10,
            'color_mode' => 'bw',
            'paper_size' => 'A4',
            'payment_status' => 'paid',
            'print_status' => 'spooling',
        ]);

        $response = $this->withHeaders([
            'X-Agent-Token' => 'test_api_token_123',
        ])->postJson('/api/agent/jobs/' . $job->job_token . '/failed', [
            'error' => 'Printer out of paper / spooler stuck',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['status' => 'success']);

        $this->assertDatabaseHas('print_jobs', [
            'id' => $job->id,
            'print_status' => 'failed',
            'cancel_reason' => 'Printer out of paper / spooler stuck',
        ]);
    }

    public function test_poll_jobs_marks_missing_files_as_failed_instead_of_stuck_spooling()
    {
        // Job with a non-existent file path
        $job = PrintJob::create([
            'shop_id' => $this->shop->id,
            'job_token' => 'TOK_MISSING_002',
            'original_name' => 'missing.pdf',
            'file_name' => 'missing.pdf',
            'file_path' => 'print_uploads/does_not_exist_' . uniqid() . '.pdf',
            'file_type' => 'pdf',
            'total_pages' => 1,
            'total_amount' => 10,
            'color_mode' => 'bw',
            'paper_size' => 'A4',
            'payment_status' => 'paid',
            'print_status' => 'queued',
        ]);

        $response = $this->withHeaders([
            'X-Agent-Token' => 'test_api_token_123',
        ])->getJson('/api/agent/jobs');

        $response->assertStatus(200);
        // The job should NOT be delivered to the agent
        $response->assertJsonCount(0, 'jobs');

        // Instead, it should be marked as failed directly in the database
        $this->assertDatabaseHas('print_jobs', [
            'id' => $job->id,
            'print_status' => 'failed',
            'is_deleted_from_disk' => true,
        ]);
    }

    public function test_reprint_fails_cleanly_if_file_already_deleted()
    {
        $job = PrintJob::create([
            'shop_id' => $this->shop->id,
            'job_token' => 'TOK_REPRINT_003',
            'original_name' => 'already_printed.pdf',
            'file_name' => 'already_printed.pdf',
            'file_path' => 'print_uploads/already_deleted.pdf',
            'file_type' => 'pdf',
            'total_pages' => 1,
            'total_amount' => 10,
            'color_mode' => 'bw',
            'paper_size' => 'A4',
            'payment_status' => 'paid',
            'print_status' => 'printed',
            'is_deleted_from_disk' => true,
        ]);

        $response = $this->actingAs($this->user)
            ->post('/shop/jobs/' . $job->id . '/reprint');

        $response->assertSessionHas('error');
        // Job must NOT be set back to queued
        $this->assertDatabaseMissing('print_jobs', [
            'id' => $job->id,
            'print_status' => 'queued',
        ]);
    }

    public function test_shopkeeper_can_cancel_paid_or_spooling_pending_job()
    {
        $job = PrintJob::create([
            'shop_id' => $this->shop->id,
            'job_token' => 'TOK_PAID_SPOOL_004',
            'original_name' => 'stuck.pdf',
            'file_name' => 'stuck.pdf',
            'file_path' => 'print_uploads/stuck.pdf',
            'file_type' => 'pdf',
            'total_pages' => 2,
            'total_amount' => 20,
            'color_mode' => 'bw',
            'paper_size' => 'A4',
            'payment_status' => 'paid',
            'print_status' => 'spooling',
        ]);

        $response = $this->actingAs($this->user)
            ->postJson('/shop/jobs/' . $job->id . '/cancel', [
                'cancel_reason' => 'প্রিন্টার নেটওয়ার্ক সমস্যা বা স্পুল আটকে গেছে',
            ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('print_jobs', [
            'id' => $job->id,
            'print_status' => 'cancelled',
            'cancel_reason' => 'প্রিন্টার নেটওয়ার্ক সমস্যা বা স্পুল আটকে গেছে',
        ]);
    }

    public function test_quick_print_preview_stream_route_returns_file()
    {
        Storage::fake('public');
        $filename = 'share_test_' . uniqid() . '.pdf';
        Storage::disk('public')->put('share_temp/' . $filename, '%PDF-1.4 test content');

        $response = $this->actingAs($this->user)
            ->get('/shop/quick-print/preview/' . $filename);

        $response->assertStatus(200);
        $response->assertHeader('Content-Disposition', 'inline; filename="' . $filename . '"');
    }
}
