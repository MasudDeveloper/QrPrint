<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('print:cleanup-orphaned', function () {
    $cutoff = now()->subHours(24);
    $orphanedJobs = \App\Models\PrintJob::where('payment_status', 'pending')
        ->where('created_at', '<', $cutoff)
        ->get();

    $count = 0;
    foreach ($orphanedJobs as $job) {
        if ($job->file_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($job->file_path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($job->file_path);
        }
        $job->update([
            'is_deleted_from_disk' => true,
            'print_status' => 'cancelled',
            'cancel_reason' => 'পেমেন্ট না করায় ২৪ ঘণ্টা পর স্বয়ংক্রিয়ভাবে মুছে ফেলা হয়েছে',
        ]);
        $count++;
    }
    $this->info("Cleaned up {$count} orphaned print job(s).");
})->purpose('Purge expired unpaid customer files older than 24 hours to free server disk space');
