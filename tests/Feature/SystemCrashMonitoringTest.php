<?php

namespace Tests\Feature;

use App\Models\PrintJob;
use App\Models\Shop;
use App\Models\SystemErrorLog;
use App\Models\User;
use App\Services\SystemCrashLogger;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SystemCrashMonitoringTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;
    protected Shop $shop;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::factory()->create([
            'role' => 'super_admin',
        ]);

        $this->shop = Shop::create([
            'user_id' => $this->adminUser->id,
            'name' => 'Crash Monitoring Shop',
            'slug' => 'crash-shop-' . uniqid(),
            'phone' => '01700000000',
            'api_token' => 'crash_test_token_abc',
            'is_active' => true,
            'is_lifetime' => true,
        ]);
    }

    public function test_exception_is_automatically_captured_by_logger()
    {
        $exception = new \RuntimeException("Critical system database failure for testing");
        $log = SystemCrashLogger::logException($exception);

        $this->assertNotNull($log);
        $this->assertDatabaseHas('system_error_logs', [
            'id' => $log->id,
            'message' => 'Critical system database failure for testing',
            'error_type' => 'RuntimeException',
            'status' => 'unresolved',
            'occurrences_count' => 1,
        ]);
    }

    public function test_recurring_errors_are_deduplicated_and_count_incremented()
    {
        $exception = new \InvalidArgumentException("Duplicate payment payload warning");

        $first = SystemCrashLogger::logException($exception);
        $second = SystemCrashLogger::logException($exception);

        $this->assertEquals($first->id, $second->id);
        $this->assertEquals(2, $second->fresh()->occurrences_count);
        $this->assertEquals(1, SystemErrorLog::where('error_type', 'InvalidArgumentException')->count());
    }

    public function test_print_spooler_failure_is_captured_in_error_logs()
    {
        $job = PrintJob::create([
            'shop_id' => $this->shop->id,
            'job_token' => 'CRASH_JOB_001',
            'original_name' => 'blueprint.pdf',
            'file_name' => 'blueprint.pdf',
            'file_path' => 'print_uploads/blueprint.pdf',
            'file_type' => 'pdf',
            'total_pages' => 3,
            'total_amount' => 15,
            'color_mode' => 'bw',
            'paper_size' => 'A4',
            'payment_status' => 'paid',
            'print_status' => 'spooling',
        ]);

        $response = $this->withHeaders([
            'X-Agent-Token' => 'crash_test_token_abc',
        ])->postJson('/api/agent/jobs/' . $job->job_token . '/failed', [
            'error' => 'Windows Spooler service stopped unexpectedly',
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('system_error_logs', [
            'error_type' => 'PrintSpoolerError',
            'shop_id' => $this->shop->id,
            'status' => 'unresolved',
        ]);
    }

    public function test_admin_can_view_logs_dashboard_and_filters()
    {
        SystemCrashLogger::logException(new \Exception("Test dashboard error appearance"));

        $response = $this->actingAs($this->adminUser)->get(route('admin.logs'));

        $response->assertStatus(200);
        $response->assertSee('এরর ও ক্র্যাশ ডিটেকশন সেন্টার');
        $response->assertSee('Test dashboard error appearance');
    }

    public function test_admin_can_resolve_and_unresolve_log()
    {
        $log = SystemCrashLogger::logException(new \Exception("Issue to resolve"));

        $resolveRes = $this->actingAs($this->adminUser)
            ->post(route('admin.logs.resolve', $log->id));

        $resolveRes->assertRedirect();
        $this->assertEquals('resolved', $log->fresh()->status);
        $this->assertEquals($this->adminUser->id, $log->fresh()->resolved_by);
        $this->assertNotNull($log->fresh()->resolved_at);

        // Unresolve
        $unresolveRes = $this->actingAs($this->adminUser)
            ->post(route('admin.logs.unresolve', $log->id));

        $unresolveRes->assertRedirect();
        $this->assertEquals('unresolved', $log->fresh()->status);
        $this->assertNull($log->fresh()->resolved_by);
    }

    public function test_admin_can_delete_and_clear_logs()
    {
        $log1 = SystemCrashLogger::logException(new \Exception("Error 1"));
        $log2 = SystemCrashLogger::logException(new \Exception("Error 2"));

        // Delete single
        $delRes = $this->actingAs($this->adminUser)
            ->delete(route('admin.logs.delete', $log1->id));

        $delRes->assertRedirect();
        $this->assertDatabaseMissing('system_error_logs', ['id' => $log1->id]);

        // Resolve log2 and clear resolved
        $log2->update(['status' => 'resolved']);
        $clearRes = $this->actingAs($this->adminUser)
            ->post(route('admin.logs.clear'), ['scope' => 'resolved']);

        $clearRes->assertRedirect();
        $this->assertEquals(0, SystemErrorLog::count());
    }

    public function test_admin_can_trigger_test_crash()
    {
        $res = $this->actingAs($this->adminUser)
            ->post(route('admin.logs.test_crash'));

        $res->assertRedirect(route('admin.logs'));
        $this->assertDatabaseHas('system_error_logs', [
            'level' => 'critical',
            'status' => 'unresolved',
        ]);
    }
}
