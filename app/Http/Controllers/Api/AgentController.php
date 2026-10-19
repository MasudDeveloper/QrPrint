<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\Printer;
use App\Models\PrintJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AgentController extends Controller
{
    protected function authenticateShop(Request $request): ?Shop
    {
        $token = $request->bearerToken() ?: $request->header('X-Agent-Token');
        if (!$token) {
            return null;
        }
        return Shop::where('api_token', $token)->first();
    }

    public function pollJobs(Request $request)
    {
        $shop = $this->authenticateShop($request);
        if (!$shop) {
            return response()->json(['error' => 'Unauthorized shop token'], 401);
        }

        $shop->update(['last_agent_heartbeat_at' => now()]);

        $jobs = PrintJob::where('shop_id', $shop->id)
            ->where('payment_status', 'paid')
            ->where('print_status', 'queued')
            ->get();

        foreach ($jobs as $job) {
            $job->update(['print_status' => 'spooling']);
        }

        $data = $jobs->map(function ($job) {
            return [
                'job_token' => $job->job_token,
                'file_url' => url('/api/agent/jobs/' . $job->job_token . '/file'),
                'file_name' => $job->file_name,
                'original_name' => $job->original_name,
                'file_type' => $job->file_type,
                'color_mode' => $job->color_mode, // bw or color (for printer routing)
                'is_duplex' => $job->is_duplex,
                'copies' => $job->copies,
                'paper_size' => $job->paper_size,
                'customer_name' => $job->customer_name,
                'pickup_reference' => $job->pickup_reference,
                'is_office_job' => (bool) $job->is_office_job,
            ];
        });

        return response()->json([
            'status' => 'ok',
            'shop_name' => $shop->name,
            'jobs' => $data,
        ]);
    }

    public function markJobPrinted(Request $request, $jobToken)
    {
        $shop = $this->authenticateShop($request);
        if (!$shop) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $job = PrintJob::where('shop_id', $shop->id)
            ->where('job_token', $jobToken)
            ->firstOrFail();

        $job->update([
            'print_status' => 'printed',
            'printed_at' => now(),
        ]);

        // Zero retention policy: Clean up temporary file
        if ($job->file_path && Storage::disk('public')->exists($job->file_path)) {
            Storage::disk('public')->delete($job->file_path);
            $job->update(['is_deleted_from_disk' => true]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Job marked as printed and temporary file purged for privacy.',
        ]);
    }

    public function heartbeat(Request $request)
    {
        $shop = $this->authenticateShop($request);
        if (!$shop) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $shop->update(['last_agent_heartbeat_at' => now()]);

        $printersList = $request->input('printers', []);
        $bwPrinter = $request->input('bw_printer');
        $colorPrinter = $request->input('color_printer');

        // 1. Process B&W selected printer from Windows Agent
        if (!empty($bwPrinter) && $bwPrinter !== '-- উইন্ডোজ ডিফল্ট প্রিন্টার --') {
            Printer::where('shop_id', $shop->id)
                ->where('type', 'bw')
                ->where('system_printer_name', '!=', $bwPrinter)
                ->update(['is_default' => false]);

            Printer::updateOrCreate(
                ['shop_id' => $shop->id, 'system_printer_name' => $bwPrinter],
                [
                    'name' => $bwPrinter,
                    'type' => 'bw',
                    'is_default' => true,
                    'status' => 'online',
                    'last_heartbeat_at' => now(),
                ]
            );
        }

        // 2. Process Color selected printer from Windows Agent
        if (!empty($colorPrinter) && $colorPrinter !== '-- উইন্ডোজ ডিফল্ট প্রিন্টার --' && $colorPrinter !== '-- কোনো কালার প্রিন্টার নেই --') {
            Printer::updateOrCreate(
                ['shop_id' => $shop->id, 'system_printer_name' => $colorPrinter],
                [
                    'name' => $colorPrinter,
                    'type' => 'color',
                    'status' => 'online',
                    'last_heartbeat_at' => now(),
                ]
            );
            $shop->update(['has_color_print' => true]);
        } elseif ($colorPrinter === '-- কোনো কালার প্রিন্টার নেই --') {
            Printer::where('shop_id', $shop->id)->where('type', 'color')->update(['status' => 'offline']);
            $shop->update(['has_color_print' => false]);
        }

        // 3. Keep all installed printers reported by agent online (excluding virtual drivers)
        $virtualKeywords = ['onenote', 'microsoft print to pdf', 'xps document writer', 'fax', 'root print queue'];
        $cleanPrintersList = array_values(array_filter($printersList, function ($p) use ($virtualKeywords) {
            $pLow = strtolower($p);
            foreach ($virtualKeywords as $kw) {
                if (str_contains($pLow, $kw)) return false;
            }
            return true;
        }));

        if (!empty($cleanPrintersList)) {
            Printer::where('shop_id', $shop->id)
                ->whereIn('system_printer_name', $cleanPrintersList)
                ->update([
                    'status' => 'online',
                    'last_heartbeat_at' => now(),
                ]);

            // Prune seeded dummy printers that are not physically installed
            if (!empty($bwPrinter) && $bwPrinter !== '-- উইন্ডোজ ডিফল্ট প্রিন্টার --') {
                Printer::where('shop_id', $shop->id)
                    ->whereIn('system_printer_name', ['HP LaserJet Pro M404dn', 'EPSON L3210 Series'])
                    ->whereNotIn('system_printer_name', $printersList)
                    ->delete();
            }

            // Mark any remaining shop printers not in installed list as offline
            Printer::where('shop_id', $shop->id)
                ->whereNotIn('system_printer_name', $printersList)
                ->update([
                    'status' => 'offline',
                    'is_default' => false,
                ]);
        }

        return response()->json([
            'status' => 'ok',
            'message' => 'Heartbeat received and printers synced successfully',
            'printers' => Printer::where('shop_id', $shop->id)->get(),
        ]);
    }

    public function downloadJobFile(Request $request, $jobToken)
    {
        $shop = $this->authenticateShop($request);
        if (!$shop) {
            return response()->json(['error' => 'Unauthorized shop token'], 401);
        }

        $job = PrintJob::where('shop_id', $shop->id)
            ->where('job_token', $jobToken)
            ->firstOrFail();

        $storagePath = storage_path('app/public/' . $job->file_path);
        if (!file_exists($storagePath)) {
            $storagePath = storage_path('app/' . $job->file_path);
        }

        if (!file_exists($storagePath)) {
            return response()->json(['error' => 'File not found on server disk'], 404);
        }

        return response()->file($storagePath, [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="' . basename($job->file_name) . '"'
        ]);
    }
}
