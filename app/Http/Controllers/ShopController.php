<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Printer;
use App\Models\PrintJob;
use App\Models\Transaction;
use App\Models\Setting;
use App\Models\ShopSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    protected function getShop(): Shop
    {
        $user = Auth::user();

        // 1. If currently impersonating a shop, check session or fallback
        if (session('impersonated_shop_id')) {
            $impersonatedShop = Shop::find(session('impersonated_shop_id'));
            if ($impersonatedShop) return $impersonatedShop;
        }

        // 2. Direct user shop
        if ($user) {
            if (session('active_shop_id')) {
                $activeShop = Shop::where('id', session('active_shop_id'))->where('user_id', $user->id)->first();
                if ($activeShop) return $activeShop;
            }
            if ($user->shop) {
                return $user->shop;
            }
            $userShop = Shop::where('user_id', $user->id)->first();
            if ($userShop) {
                return $userShop;
            }
        }

        // 3. If Super Admin is accessing without an explicit shop relation, return the first active shop or create one
        if ($user && $user->isSuperAdmin()) {
            $firstShop = Shop::first();
            if ($firstShop) return $firstShop;
        }

        // 4. Default fallback: create shop for user
        $shop = Shop::firstOrCreate(
            ['user_id' => $user->id],
            [
                'name' => ($user->name ?? 'My') . ' Print Shop',
                'phone' => $user->phone ?? '',
                'bkash_number' => $user->phone ?? '',
                'nagad_number' => $user->phone ?? '',
                'api_token' => \Illuminate\Support\Str::random(40),
            ]
        );

        return $shop;
    }

    public function dashboard()
    {
        $shop = $this->getShop();
        $todayJobs = PrintJob::where('shop_id', $shop->id)->whereDate('created_at', today())->get();
        $todayJobEarnings = $todayJobs->where('payment_status', 'paid')->sum('total_amount');
        $todayStandaloneEarnings = Transaction::where('shop_id', $shop->id)
            ->whereNull('print_job_id')
            ->where('status', 'completed')
            ->whereDate('created_at', today())
            ->sum('amount');
        $todayEarnings = $todayJobEarnings + $todayStandaloneEarnings;
        $todayPrintsCount = $todayJobs->where('print_status', 'printed')->count();

        $recentTransactions = Transaction::where('shop_id', $shop->id)
            ->latest()
            ->take(10)
            ->get();

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
            'printers',
            'recentTransactions'
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
        $user = Auth::user();

        $canDelete = ((int) $printer->shop_id === (int) $shop->id) ||
                     ($user && Shop::where('id', $printer->shop_id)->where('user_id', $user->id)->exists()) ||
                     ($user && $user->isSuperAdmin());

        if ($canDelete) {
            $printer->delete();
            return back()->with('success', 'প্রিন্টার মুছে ফেলা হয়েছে!');
        }

        return back()->with('error', 'এই প্রিন্টারটি মুছে ফেলার অনুমতি নেই।');
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

    public function downloadAgentZip()
    {
        $shop = $this->getShop();
        $zipFileName = 'PrintSheba-Windows-Package-' . \Illuminate\Support\Str::slug($shop->name) . '.zip';
        $tempZipPath = storage_path('app/' . uniqid('ps_pkg_') . '.zip');

        $zip = new \ZipArchive();
        if ($zip->open($tempZipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            return back()->with('error', 'জিপ প্যাকেজ তৈরি করতে সমস্যা হয়েছে।');
        }

        // 1. Add PrintShebaAgent.exe
        $exePath = public_path('downloads/PrintShebaAgent.exe');
        if (!file_exists($exePath)) {
            $exePath = base_path('agent/PrintShebaAgent.exe');
        }
        if (file_exists($exePath)) {
            $zip->addFile($exePath, 'PrintSheba/PrintShebaAgent.exe');
        }

        // 2. Add SumatraPDF.exe (Silent Printing Engine)
        $sumatraPath = public_path('downloads/SumatraPDF.exe');
        if (!file_exists($sumatraPath)) {
            $sumatraPath = base_path('agent/SumatraPDF.exe');
        }
        if (file_exists($sumatraPath)) {
            $zip->addFile($sumatraPath, 'PrintSheba/SumatraPDF.exe');
        }

        // 3. Add Shop-specific config.json (Pre-configured with shop API token)
        $config = [
            'server_url' => url('/'),
            'api_token' => $shop->api_token,
            'bw_printer' => '',
            'color_printer' => '',
            'poll_interval_sec' => 3,
            'auto_start' => true,
            'minimize_to_tray' => true,
        ];
        $zip->addFromString('PrintSheba/config.json', json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        // 4. Add Step-by-Step Instruction README
        $readme = "========================================================\r\n" .
                  "  PrintSheba (প্রিন্ট সেবা) - উইন্ডোজ এজেন্ট ইনস্টলেশন গাইড\r\n" .
                  "  দোকানের নাম: " . $shop->name . "\r\n" .
                  "========================================================\r\n\r\n" .
                  "ধাপ ১: এই জিপ ফাইলটি আনজিপ (Extract) করুন।\r\n" .
                  "ধাপ ২: ফোল্ডারের ভেতর থাকা 'PrintShebaAgent.exe' ফাইলে ডাবল ক্লিক করে ওপেন করুন।\r\n" .
                  "       (যদি Windows SmartScreen 'Windows protected your PC' মেসেজ দেয়, তবে 'More info' -> 'Run anyway' বাটনে চাপ দিন)\r\n" .
                  "ধাপ ৩: সফটওয়্যারটিতে আপনার সাদাকালো এবং রঙিন প্রিন্টার সিলেক্ট করে 'সেটিংস সেভ ও ক্লাউডে সিঙ্ক' বাটনে ক্লিক করুন।\r\n\r\n" .
                  "অভিনন্দন! আপনার কম্পিউটার এখন ক্লাউডের সাথে সংযুক্ত। কাস্টমার QR কোড স্ক্যান করে প্রিন্ট অর্ডার দিলে কোনো ক্লিক ছাড়াই স্বয়ংক্রিয়ভাবে পেজ বের হবে।\r\n" .
                  "সফটওয়্যারটি মিনিমাইজ করে রাখুন, এটি ব্যাকগ্রাউন্ডে ঘড়ির পাশে ট্রে আইকনে নীরবে কাজ করবে।\r\n\r\n" .
                  "দোকানের API Token: " . $shop->api_token . "\r\n" .
                  "সার্ভার URL: " . url('/') . "\r\n";
        $zip->addFromString('PrintSheba/ব্যবহার_নির্দেশিকা.txt', $readme);

        $zip->close();

        return response()->download($tempZipPath, $zipFileName, [
            'Content-Type' => 'application/zip',
        ])->deleteFileAfterSend(true);
    }

    public function downloadAgentZipPublic($token)
    {
        $shop = Shop::where('api_token', $token)->firstOrFail();
        $zipFileName = 'PrintSheba-Windows-Package-' . \Illuminate\Support\Str::slug($shop->name) . '.zip';
        $tempZipPath = storage_path('app/' . uniqid('ps_pkg_') . '.zip');

        $zip = new \ZipArchive();
        if ($zip->open($tempZipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            return response()->json(['error' => 'Failed to create zip package'], 500);
        }

        // 1. Add PrintShebaAgent.exe
        $exePath = public_path('downloads/PrintShebaAgent.exe');
        if (!file_exists($exePath)) {
            $exePath = base_path('agent/PrintShebaAgent.exe');
        }
        if (file_exists($exePath)) {
            $zip->addFile($exePath, 'PrintSheba/PrintShebaAgent.exe');
        }

        // 2. Add SumatraPDF.exe
        $sumatraPath = public_path('downloads/SumatraPDF.exe');
        if (!file_exists($sumatraPath)) {
            $sumatraPath = base_path('agent/SumatraPDF.exe');
        }
        if (file_exists($sumatraPath)) {
            $zip->addFile($sumatraPath, 'PrintSheba/SumatraPDF.exe');
        }

        // 3. Add Shop-specific config.json
        $config = [
            'server_url' => url('/'),
            'api_token' => $shop->api_token,
            'bw_printer' => '',
            'color_printer' => '',
            'poll_interval_sec' => 3,
            'auto_start' => true,
            'minimize_to_tray' => true,
        ];
        $zip->addFromString('PrintSheba/config.json', json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        // 4. Add Step-by-Step Instruction README
        $readme = "========================================================\r\n" .
                  "  PrintSheba (প্রিন্ট সেবা) - উইন্ডোজ এজেন্ট ইনস্টলেশন গাইড\r\n" .
                  "  দোকানের নাম: " . $shop->name . "\r\n" .
                  "========================================================\r\n\r\n" .
                  "ধাপ ১: এই জিপ ফাইলটি আনজিপ (Extract) করুন।\r\n" .
                  "ধাপ ২: ফোল্ডারের ভেতর থাকা 'PrintShebaAgent.exe' ফাইলে ডাবল ক্লিক করে ওপেন করুন।\r\n" .
                  "       (যদি Windows SmartScreen 'Windows protected your PC' মেসেজ দেয়, তবে 'More info' -> 'Run anyway' বাটনে চাপ দিন)\r\n" .
                  "ধাপ ৩: সফটওয়্যারটিতে আপনার সাদাকালো এবং রঙিন প্রিন্টার সিলেক্ট করে 'সেটিংস সেভ ও ক্লাউডে সিঙ্ক' বাটনে ক্লিক করুন।\r\n\r\n" .
                  "অভিনন্দন! আপনার কম্পিউটার এখন ক্লাউডের সাথে সংযুক্ত। কাস্টমার QR কোড স্ক্যান করে প্রিন্ট অর্ডার দিলে কোনো ক্লিক ছাড়াই স্বয়ংক্রিয়ভাবে পেজ বের হবে।\r\n" .
                  "সফটওয়্যারটি মিনিমাইজ করে রাখুন, এটি ব্যাকগ্রাউন্ডে ঘড়ির পাশে ট্রে আইকনে নীরবে কাজ করবে।\r\n\r\n" .
                  "দোকানের API Token: " . $shop->api_token . "\r\n" .
                  "সার্ভার URL: " . url('/') . "\r\n";
        $zip->addFromString('PrintSheba/ব্যবহার_নির্দেশিকা.txt', $readme);

        $zip->close();

        return response()->download($tempZipPath, $zipFileName, [
            'Content-Type' => 'application/zip',
        ])->deleteFileAfterSend(true);
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
        $user = Auth::user();

        // Check ownership: current active shop OR user's own shop OR super admin
        $isOwner = ((int) $job->shop_id === (int) $shop->id) || 
                   ($user && Shop::where('id', $job->shop_id)->where('user_id', $user->id)->exists()) ||
                   ($user && $user->shop && (int) $user->shop->id === (int) $job->shop_id) || 
                   ($user && $user->isSuperAdmin()) ||
                   (session('impersonator_id') !== null);

        if (!$isOwner) {
            return back()->with('error', 'এই প্রিন্ট জবটি আপনার দোকানের নয়।');
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
        $user = Auth::user();

        // Check ownership: current active shop OR user's own shop OR super admin
        $isOwner = ((int) $job->shop_id === (int) $shop->id) || 
                   ($user && Shop::where('id', $job->shop_id)->where('user_id', $user->id)->exists()) ||
                   ($user && $user->shop && (int) $user->shop->id === (int) $job->shop_id) || 
                   ($user && $user->isSuperAdmin()) ||
                   (session('impersonator_id') !== null);

        if (!$isOwner) {
            return back()->with('error', 'এই প্রিন্ট জবটি আপনার দোকানের নয়।');
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

    /**
     * Shop subscription details and purchase view
     */
    public function subscription(Request $request)
    {
        $shop = $this->getShop();
        $preselectedPlan = $request->query('plan', 'yearly_pro');
        $adminBkash = Setting::get('admin_bkash_number', '01644416378');
        $adminNagad = Setting::get('admin_nagad_number', '01644416378');
        $paymentInstructions = Setting::get('admin_payment_instructions', 'বিকাশ বা নগদ সেন্ড মানি করে নিচে আপনার Transaction ID ও সেন্ডার নাম্বার দিন।');
        
        $subscriptionsHistory = ShopSubscription::where('shop_id', $shop->id)->latest()->get();

        return view('shop.subscription', compact(
            'shop',
            'preselectedPlan',
            'adminBkash',
            'adminNagad',
            'paymentInstructions',
            'subscriptionsHistory'
        ));
    }

    /**
     * Submit subscription request with bKash/Nagad TrxID
     */
    public function purchaseSubscription(Request $request)
    {
        $shop = $this->getShop();

        $request->validate([
            'plan' => 'required|string|in:monthly_starter,yearly_pro,lifetime',
            'payment_method' => 'required|string|in:bkash,nagad',
            'sender_number' => 'required|string|max:20',
            'transaction_id' => 'required|string|max:50',
        ]);

        $plans = [
            'monthly_starter' => ['name' => 'মান্থলি স্টার্টার', 'amount' => 99.00],
            'yearly_pro' => ['name' => 'ইয়ারলি প্রো (১ বছর)', 'amount' => 999.00],
            'lifetime' => ['name' => 'লাইফটাইম আনলিমিটেড (প্রথম ৫০ শপ)', 'amount' => 3999.00],
        ];

        $selectedPlan = $plans[$request->plan];

        ShopSubscription::create([
            'shop_id' => $shop->id,
            'plan' => $request->plan,
            'plan_name' => $selectedPlan['name'],
            'amount' => $selectedPlan['amount'],
            'payment_method' => $request->payment_method,
            'transaction_id' => strtoupper(trim($request->transaction_id)),
            'sender_number' => trim($request->sender_number),
            'status' => 'pending',
        ]);

        return back()->with('success', 'আপনার সাবস্ক্রিপশন ও পেমেন্টের আবেদনটি সফলভাবে জমা হয়েছে! সুপার এডমিন পেমেন্ট ভেরিফাই করার সাথে সাথেই আপনার প্যাকেজটি সক্রিয় হয়ে যাবে।');
    }
}
