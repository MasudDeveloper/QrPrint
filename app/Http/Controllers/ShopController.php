<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Printer;
use App\Models\PrintJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    protected function getShop(): Shop
    {
        $shop = Auth::user()->shop;
        if (!$shop) {
            $shop = Shop::create([
                'user_id' => Auth::id(),
                'name' => Auth::user()->name . ' Print Shop',
                'phone' => Auth::user()->phone ?? '',
                'bkash_number' => Auth::user()->phone ?? '',
                'nagad_number' => Auth::user()->phone ?? '',
                'api_token' => \Illuminate\Support\Str::random(40),
            ]);
        }
        return $shop;
    }

    public function dashboard()
    {
        $shop = $this->getShop();
        $todayJobs = PrintJob::where('shop_id', $shop->id)->whereDate('created_at', today())->get();
        $todayEarnings = $todayJobs->where('payment_status', 'paid')->sum('total_amount');
        $todayPrintsCount = $todayJobs->where('print_status', 'printed')->count();

        $pendingJobs = PrintJob::where('shop_id', $shop->id)
            ->whereIn('print_status', ['queued', 'spooling'])
            ->latest()
            ->take(15)
            ->get();

        $completedJobs = PrintJob::where('shop_id', $shop->id)
            ->where('print_status', 'printed')
            ->latest()
            ->take(15)
            ->get();

        $printers = Printer::where('shop_id', $shop->id)->get();

        return view('shop.dashboard', compact(
            'shop',
            'todayEarnings',
            'todayPrintsCount',
            'pendingJobs',
            'completedJobs',
            'printers'
        ));
    }

    public function qrPoster()
    {
        $shop = $this->getShop();
        $printUrl = url('/print/' . $shop->slug);
        return view('shop.qr_poster', compact('shop', 'printUrl'));
    }

    public function rates()
    {
        $shop = $this->getShop();
        return view('shop.rates', compact('shop'));
    }

    public function updateRates(Request $request)
    {
        $shop = $this->getShop();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'bkash_number' => 'nullable|string|max:20',
            'nagad_number' => 'nullable|string|max:20',
            'rate_bw_single' => 'required|numeric|min:0.5',
            'rate_bw_double' => 'required|numeric|min:0.5',
            'rate_color_single' => 'required|numeric|min:1',
            'rate_color_double' => 'required|numeric|min:1',
            'rate_photo_passport' => 'required|numeric|min:5',
        ]);

        $data = $request->only([
            'name',
            'phone',
            'bkash_number',
            'nagad_number',
            'rate_bw_single',
            'rate_bw_double',
            'rate_color_single',
            'rate_color_double',
            'rate_photo_passport',
        ]);
        $data['has_color_print'] = $request->boolean('has_color_print');
        $data['has_duplex_print'] = $request->boolean('has_duplex_print');
        $data['has_passport_photo'] = $request->boolean('has_passport_photo');

        $shop->update($data);

        return back()->with('success', 'দোকানের প্রিন্ট সার্ভিস সেটিংস ও রেট সফলভাবে সংরক্ষিত হয়েছে!');
    }

    public function printers()
    {
        $shop = $this->getShop();
        $printers = Printer::where('shop_id', $shop->id)->get();
        return view('shop.printers', compact('shop', 'printers'));
    }

    public function addPrinter(Request $request)
    {
        $shop = $this->getShop();

        $request->validate([
            'name' => 'required|string|max:100',
            'type' => 'required|in:all,bw,color,photo',
            'system_printer_name' => 'nullable|string|max:150',
        ]);

        Printer::create([
            'shop_id' => $shop->id,
            'name' => $request->name,
            'type' => $request->type,
            'system_printer_name' => $request->system_printer_name ?? $request->name,
            'is_default' => $request->boolean('is_default'),
            'status' => 'online',
            'last_heartbeat_at' => now(),
        ]);

        return back()->with('success', 'নতুন প্রিন্টার সফলভাবে যোগ করা হয়েছে!');
    }

    public function deletePrinter(Printer $printer)
    {
        $shop = $this->getShop();
        if ($printer->shop_id === $shop->id) {
            $printer->delete();
        }
        return back()->with('success', 'প্রিন্টার মুছে ফেলা হয়েছে!');
    }

    public function agentSetup()
    {
        $shop = $this->getShop();
        return view('shop.agent_setup', compact('shop'));
    }

    public function downloadAgentExe()
    {
        $path = public_path('downloads/PrintShebaAgent.exe');
        if (!file_exists($path)) {
            $agentPath = base_path('agent/PrintShebaAgent.exe');
            if (file_exists($agentPath)) {
                @copy($agentPath, $path);
            }
        }

        if (!file_exists($path)) {
            return back()->with('error', 'এজেন্ট ফাইলটি পাওয়া যায়নি। অনুগ্রহ করে ডেভেলপার টিমের সাথে যোগাযোগ করুন।');
        }

        return response()->download($path, 'PrintShebaAgent.exe', [
            'Content-Type' => 'application/octet-stream',
        ]);
    }

    public function downloadAgentConfig()
    {
        $shop = $this->getShop();
        $config = [
            'server_url' => url('/'),
            'api_token' => $shop->api_token,
            'bw_printer' => '',
            'color_printer' => '',
            'poll_interval_sec' => 3,
            'auto_start' => true,
            'minimize_to_tray' => true,
        ];

        return response()->streamDownload(function () use ($config) {
            echo json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        }, 'config.json', [
            'Content-Type' => 'application/json',
        ]);
    }

    public function downloadAndroidApk()
    {
        $path = public_path('downloads/PrintSheba-Payment.apk');
        if (!file_exists($path)) {
            $gradleApk = base_path('android/app/build/outputs/apk/debug/app-debug.apk');
            if (file_exists($gradleApk)) {
                @copy($gradleApk, $path);
            }
        }

        if (!file_exists($path)) {
            return back()->with('error', 'অ্যান্ড্রয়েড APK ফাইলটি প্রস্তুত হচ্ছে। অনুগ্রহ করে কিছুক্ষণ পর আবার চেষ্টা করুন।');
        }

        return response()->download($path, 'PrintSheba-Payment.apk', [
            'Content-Type' => 'application/vnd.android.package-archive',
        ]);
    }

    public function approvePayment(PrintJob $job)
    {
        $shop = $this->getShop();
        if ($job->shop_id !== $shop->id) {
            abort(403);
        }

        $job->update([
            'payment_status' => 'paid',
            'print_status' => 'queued',
        ]);

        return back()->with('success', "টোকেন #{$job->job_token} এর ক্যাশ পেমেন্ট কনফার্ম হয়েছে এবং স্বয়ংক্রিয় প্রিন্ট কিউতে পাঠানো হয়েছে!");
    }

    public function reprintJob(PrintJob $job)
    {
        $shop = $this->getShop();
        if ($job->shop_id !== $shop->id) {
            abort(403);
        }

        $job->update([
            'payment_status' => 'paid',
            'print_status' => 'queued',
        ]);

        return back()->with('success', "টোকেন #{$job->job_token} পুনরায় প্রিন্ট করার জন্য কিউতে পাঠানো হয়েছে!");
    }

    public function syncStatus()
    {
        $shop = $this->getShop();
        $isAgentOnline = $shop->last_agent_heartbeat_at && $shop->last_agent_heartbeat_at->gte(now()->subMinutes(3));
        $isAppOnline = $shop->last_app_heartbeat_at && $shop->last_app_heartbeat_at->gte(now()->subMinutes(3));

        return response()->json([
            'agent_online' => (bool) $isAgentOnline,
            'agent_last_seen' => $shop->last_agent_heartbeat_at ? $shop->last_agent_heartbeat_at->diffForHumans() : 'কখনও কানেক্ট হয়নি',
            'app_online' => (bool) $isAppOnline,
            'app_last_seen' => $shop->last_app_heartbeat_at ? $shop->last_app_heartbeat_at->diffForHumans() : 'কখনও কানেক্ট হয়নি',
        ]);
    }

    public function liveStatus()
    {
        $shop = $this->getShop();
        $todayJobs = PrintJob::where('shop_id', $shop->id)->whereDate('created_at', today())->get();
        $todayEarnings = $todayJobs->where('payment_status', 'paid')->sum('total_amount');
        $todayPrintsCount = $todayJobs->where('print_status', 'printed')->count();

        $pendingJobs = PrintJob::where('shop_id', $shop->id)
            ->whereIn('print_status', ['queued', 'spooling'])
            ->latest()
            ->take(15)
            ->get()
            ->map(function ($job) {
                return [
                    'id' => $job->id,
                    'job_token' => $job->job_token,
                    'customer_name' => $job->customer_name,
                    'customer_phone' => $job->customer_phone,
                    'original_name' => $job->original_name,
                    'color_mode' => $job->color_mode,
                    'total_amount' => $job->total_amount,
                    'payment_status' => $job->payment_status,
                    'payment_trx_id' => $job->payment_trx_id,
                    'print_status' => $job->print_status,
                    'approve_url' => route('shop.jobs.approve', $job),
                    'reprint_url' => route('shop.jobs.reprint', $job),
                ];
            });

        $completedJobs = PrintJob::where('shop_id', $shop->id)
            ->where('print_status', 'printed')
            ->latest()
            ->take(15)
            ->get()
            ->map(function ($job) {
                return [
                    'id' => $job->id,
                    'job_token' => $job->job_token,
                    'original_name' => $job->original_name,
                    'total_pages' => $job->total_pages,
                    'paper_size' => $job->paper_size,
                    'total_amount' => $job->total_amount,
                    'payment_method' => $job->payment_method,
                    'time_str' => $job->printed_at ? $job->printed_at->format('h:i A') : $job->created_at->format('h:i A'),
                    'reprint_url' => route('shop.jobs.reprint', $job),
                ];
            });

        return response()->json([
            'status' => 'success',
            'today_earnings' => number_format($todayEarnings, 2),
            'today_prints_count' => $todayPrintsCount,
            'pending_count' => $pendingJobs->count(),
            'pending_jobs' => $pendingJobs,
            'completed_jobs' => $completedJobs,
        ]);
    }
}
