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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

        $cancelledJobs = PrintJob::where('shop_id', $shop->id)
            ->where('print_status', 'cancelled')
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
            'cancelledJobs',
            'printers',
            'recentTransactions'
        ));
    }

    public function qrPoster()
    {
        $shop = $this->getShop();
        $printUrl = $shop->is_office_mode ? url('/office/' . $shop->slug) : url('/print/' . $shop->slug);
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
        $isOffice = $shop->is_office_mode;

        $rules = [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ];

        if (!$isOffice) {
            $rules['bkash_number'] = 'nullable|string|max:20';
            $rules['nagad_number'] = 'nullable|string|max:20';
            $rules['rate_bw_single'] = 'required|numeric|min:0.5';
            $rules['rate_bw_double'] = 'required|numeric|min:0.5';
            $rules['rate_photocopy_single'] = 'nullable|numeric|min:0.5';
            $rules['rate_photocopy_double'] = 'nullable|numeric|min:0.5';
            $rules['rate_color_single'] = 'required|numeric|min:1';
            $rules['rate_color_double'] = 'required|numeric|min:1';
            $rules['rate_photo_passport'] = 'nullable|numeric|min:0';
            $rules['rate_nid_bw'] = 'nullable|numeric|min:1';
            $rules['rate_nid_color'] = 'nullable|numeric|min:1';
        }

        $request->validate($rules);

        if ($isOffice) {
            $data = $request->only(['name', 'phone']);
            $data['has_bw_print'] = $request->boolean('has_bw_print');
            $data['has_color_print'] = $request->boolean('has_color_print');
            $data['has_duplex_print'] = $request->boolean('has_duplex_print');
            $data['has_photocopier'] = $request->boolean('has_photocopier');
            $data['has_nid_print'] = $request->boolean('has_nid_print');
            // Ensure rates stay zero for office mode
            $data['rate_bw_single'] = 0.00;
            $data['rate_bw_double'] = 0.00;
            $data['rate_photocopy_single'] = 0.00;
            $data['rate_photocopy_double'] = 0.00;
            $data['rate_color_single'] = 0.00;
            $data['rate_color_double'] = 0.00;
        } else {
            $data = $request->only([
                'name',
                'phone',
                'bkash_number',
                'nagad_number',
                'rate_bw_single',
                'rate_bw_double',
                'rate_photocopy_single',
                'rate_photocopy_double',
                'rate_color_single',
                'rate_color_double',
                'rate_photo_passport',
                'rate_nid_bw',
                'rate_nid_color',
            ]);
            $data['has_bw_print'] = $request->boolean('has_bw_print');
            $data['has_color_print'] = $request->boolean('has_color_print');
            $data['has_duplex_print'] = $request->boolean('has_duplex_print');
            $data['has_photocopier'] = $request->boolean('has_photocopier');
            $data['has_passport_photo'] = $request->boolean('has_passport_photo');
            $data['has_nid_print'] = $request->boolean('has_nid_print');
        }

        $shop->update($data);

        return back()->with('success', $isOffice ? 'অফিসের প্রিন্ট সার্ভিস সেটিংস সফলভাবে সংরক্ষিত হয়েছে!' : 'দোকানের প্রিন্ট সার্ভিস সেটিংস ও রেট সফলভাবে সংরক্ষিত হয়েছে!');
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
            'type' => 'required|in:all,bw,color,photo,photocopy',
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

        // Add application icon
        $iconPath = base_path('agent/app_icon.ico');
        if (file_exists($iconPath)) {
            $zip->addFile($iconPath, 'PrintSheba/app_icon.ico');
        }

        // 3. Add Shop-specific config.json (Pre-configured with shop API token)
        $config = [
            'server_url' => url('/'),
            'api_token' => $shop->api_token,
            'bw_printer' => '',
            'color_printer' => '',
            'photocopy_printer' => '',
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

        // Add application icon
        $iconPath = base_path('agent/app_icon.ico');
        if (file_exists($iconPath)) {
            $zip->addFile($iconPath, 'PrintSheba/app_icon.ico');
        }

        // 3. Add Shop-specific config.json
        $config = [
            'server_url' => url('/'),
            'api_token' => $shop->api_token,
            'bw_printer' => '',
            'color_printer' => '',
            'photocopy_printer' => '',
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
            'photocopy_printer' => '',
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

        if (!$shop->is_active) {
            return back()->with('error', 'আপনার প্রতিষ্ঠানটি বর্তমানে স্থগিত (Suspended) রয়েছে। কোনো প্রিন্ট প্রসেস করা সম্ভব নয়।');
        }

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

        if (!$shop->is_active) {
            return back()->with('error', 'আপনার প্রতিষ্ঠানটি বর্তমানে স্থগিত (Suspended) রয়েছে। কোনো প্রিন্ট প্রসেস করা সম্ভব নয়।');
        }

        // Check ownership: current active shop OR user's own shop OR super admin
        $isOwner = ((int) $job->shop_id === (int) $shop->id) || 
                   ($user && Shop::where('id', $job->shop_id)->where('user_id', $user->id)->exists()) ||
                   ($user && $user->shop && (int) $user->shop->id === (int) $job->shop_id) || 
                   ($user && $user->isSuperAdmin()) ||
                   (session('impersonator_id') !== null);

        if (!$isOwner) {
            return back()->with('error', 'এই প্রিন্ট জবটি আপনার দোকানের নয়।');
        }

        // Verify that the file still exists on server disk before attempting to reprint
        $storagePath = storage_path('app/public/' . $job->file_path);
        if (!file_exists($storagePath)) {
            $storagePath = storage_path('app/' . $job->file_path);
        }

        if ($job->is_deleted_from_disk || !file_exists($storagePath)) {
            $job->update([
                'is_deleted_from_disk' => true,
                'print_status' => 'failed',
                'cancel_reason' => 'ফাইলটি সার্ভার ডিস্কে পাওয়া যায়নি (404 / আগেই ডিলিট হয়েছে)',
            ]);
            return back()->with('error', 'ফাইলটি সার্ভারে নেই (নিরাপত্তার স্বার্থে প্রিন্ট শেষে আগেই মুছে ফেলা হয়েছে)। এটি রিপ্রিন্ট করা সম্ভব নয়। অনুগ্রহ করে ফাইলটি নতুন করে শেয়ার বা আপলোড করুন।');
        }

        $job->update([
            'payment_status' => 'paid',
            'print_status' => 'queued',
        ]);

        return back()->with('success', "টোকেন #{$job->job_token} পুনরায় প্রিন্ট করার জন্য কিউতে পাঠানো হয়েছে!");
    }

    public function cancelJob(Request $request, PrintJob $job)
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
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'এই প্রিন্ট জবটি আপনার দোকানের নয়।'], 403);
            }
            return back()->with('error', 'এই প্রিন্ট জবটি আপনার দোকানের নয়।');
        }

        $reason = trim($request->input('cancel_reason', ''));
        if (empty($reason) || $reason === 'other') {
            $reason = trim($request->input('custom_reason', '')) ?: 'কাস্টমার দোকানে আসেননি / ক্যাশ দেননি';
        }

        $job->update([
            'payment_status' => 'cancelled',
            'print_status'   => 'cancelled',
            'cancel_reason'  => $reason,
            'cancelled_at'   => now(),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "টোকেন #{$job->job_token} এর অর্ডারটি বাতিল করা হয়েছে।",
                'job_token' => $job->job_token,
            ]);
        }

        return back()->with('success', "টোকেন #{$job->job_token} এর অর্ডারটি সফলভাবে বাতিল করা হয়েছে।");
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
                    'copies' => $job->copies,
                    'total_pages' => $job->total_pages,
                    'total_amount' => $job->total_amount,
                    'payment_status' => $job->payment_status,
                    'payment_method' => $job->payment_method,
                    'payment_trx_id' => $job->payment_trx_id,
                    'print_status' => $job->print_status,
                    'pickup_reference' => $job->pickup_reference,
                    'is_office_job' => (bool) $job->is_office_job,
                    'approve_url' => route('shop.jobs.approve', $job),
                    'reprint_url' => route('shop.jobs.reprint', $job),
                    'cancel_url' => route('shop.jobs.cancel', $job),
                    'slip_url' => route('shop.jobs.slip', $job),
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
                    'customer_name' => $job->customer_name,
                    'pickup_reference' => $job->pickup_reference,
                    'color_mode' => $job->color_mode,
                    'copies' => $job->copies,
                    'total_pages' => $job->total_pages,
                    'paper_size' => $job->paper_size,
                    'total_amount' => $job->total_amount,
                    'payment_method' => $job->payment_method,
                    'is_office_job' => (bool) $job->is_office_job,
                    'time_str' => $job->printed_at ? $job->printed_at->format('h:i A') : $job->created_at->format('h:i A'),
                    'reprint_url' => route('shop.jobs.reprint', $job),
                ];
            });

        return response()->json([
            'status' => 'success',
            'is_active' => (bool) $shop->is_active,
            'is_subscription_active' => (bool) $shop->isSubscriptionActive(),
            'is_office_mode' => (bool) $shop->is_office_mode,
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
        $defaultPlan = $shop->is_office_mode ? 'office_basic_yearly' : 'yearly_pro';
        $preselectedPlan = $request->query('plan', $defaultPlan);
        $adminBkash = Setting::get('admin_bkash_number', '01644416378');
        $adminNagad = Setting::get('admin_nagad_number', '01644416378');
        $adminRocket = Setting::get('admin_rocket_number', '01644416378');
        $paymentInstructions = Setting::get('admin_payment_instructions', 'বিকাশ, নগদ বা রকেটে সেন্ড মানি করে নিচে আপনার Transaction ID ও সেন্ডার নাম্বার দিন।');
        
        $subscriptionsHistory = ShopSubscription::where('shop_id', $shop->id)->latest()->get();
        $pendingSubscription = ShopSubscription::where('shop_id', $shop->id)->where('status', 'pending')->latest()->first();
        $latestSubscription = ShopSubscription::where('shop_id', $shop->id)->latest()->first();

        return view('shop.subscription', compact(
            'shop',
            'preselectedPlan',
            'adminBkash',
            'adminNagad',
            'adminRocket',
            'paymentInstructions',
            'subscriptionsHistory',
            'pendingSubscription',
            'latestSubscription'
        ));
    }

    /**
     * Submit subscription request with bKash/Nagad/Rocket TrxID
     */
    public function purchaseSubscription(Request $request)
    {
        $shop = $this->getShop();

        $request->validate([
            'plan' => 'required|string|in:monthly_starter,yearly_pro,lifetime,office_basic_monthly,office_basic_yearly,office_corporate_yearly',
            'payment_method' => 'required|string|in:bkash,nagad,rocket',
            'sender_number' => ['required', 'string', 'regex:/^01[3-9]\d{8}$/'],
            'transaction_id' => 'required|string|min:6|max:50',
        ], [
            'sender_number.regex' => '১১ ডিজিটের সঠিক মোবাইল নম্বর প্রদান করুন (যেমন: 01712345678)।',
            'transaction_id.required' => 'পেমেন্ট ট্রানজেকশন আইডি (TrxID) দিন।',
            'transaction_id.min' => 'সঠিক ট্রানজেকশন আইডি (কমপক্ষে ৬ ডিজিট/অক্ষর) দিন।',
        ]);

        $plans = [
            'monthly_starter' => ['name' => 'মান্থলি স্টার্টার (দোকান)', 'amount' => 99.00],
            'yearly_pro' => ['name' => 'ইয়ারলি প্রো (১ বছর - দোকান)', 'amount' => 999.00],
            'lifetime' => ['name' => 'লাইফটাইম আনলিমিটেড (প্রথম ৫০ শপ)', 'amount' => 3999.00],
            'office_basic_monthly' => ['name' => 'অফিস বেসিক (১ মাস)', 'amount' => 499.00],
            'office_basic_yearly' => ['name' => 'অফিস বেসিক (১ বছর)', 'amount' => 4500.00],
            'office_corporate_yearly' => ['name' => 'কর্পোরেট প্রো (১ বছর - মাল্টি-প্রিন্টার)', 'amount' => 8999.00],
        ];

        $selectedPlan = $plans[$request->plan];

        $cleanTrxId = strtoupper(str_replace(' ', '', trim($request->transaction_id)));
        $cleanSenderNumber = trim($request->sender_number);

        $subscription = ShopSubscription::create([
            'shop_id' => $shop->id,
            'plan' => $request->plan,
            'plan_name' => $selectedPlan['name'],
            'amount' => $selectedPlan['amount'],
            'payment_method' => $request->payment_method,
            'transaction_id' => $cleanTrxId,
            'sender_number' => $cleanSenderNumber,
            'status' => 'pending',
        ]);

        return back()
            ->with('success', 'আপনার সাবস্ক্রিপশন ও পেমেন্টের আবেদনটি সফলভাবে জমা হয়েছে! এডমিন পেমেন্ট ভেরিফাই করার সাথে সাথেই আপনার প্যাকেজটি সক্রিয় হয়ে যাবে।')
            ->with('submitted_subscription_id', $subscription->id);
    }

    /**
     * Real-time polling endpoint for subscription verification status
     */
    public function subscriptionStatus()
    {
        $shop = $this->getShop();
        $latest = ShopSubscription::where('shop_id', $shop->id)->latest()->first();
        $pending = ShopSubscription::where('shop_id', $shop->id)->where('status', 'pending')->latest()->first();

        return response()->json([
            'has_pending' => (bool)$pending,
            'status' => $latest ? $latest->status : 'none',
            'subscription' => $latest ? [
                'id' => $latest->id,
                'invoice_no' => 'PS-INV-' . str_pad($latest->id, 5, '0', STR_PAD_LEFT),
                'plan' => $latest->plan,
                'plan_name' => $latest->plan_name,
                'amount' => (float)$latest->amount,
                'payment_method' => $latest->payment_method,
                'transaction_id' => $latest->transaction_id,
                'sender_number' => $latest->sender_number,
                'created_at' => $latest->created_at ? $latest->created_at->format('d M, Y h:i A') : null,
                'approved_at' => $latest->approved_at ? $latest->approved_at->format('d M, Y h:i A') : null,
                'notes' => $latest->notes,
            ] : null,
            'is_active' => (bool)($shop->isSubscriptionActive() || $shop->is_lifetime),
            'plan_label' => $shop->plan_label,
            'formatted_expires_at' => $shop->formatted_expires_at,
            'time_remaining_label' => $shop->time_remaining_label,
        ]);
    }

    /**
     * Render printable envelope / pickup tag slip for shopkeeper
     */
    public function printJobSlip(PrintJob $job)
    {
        $shop = $this->getShop();
        $user = Auth::user();

        $isOwner = ((int) $job->shop_id === (int) $shop->id) || 
                   ($user && Shop::where('id', $job->shop_id)->where('user_id', $user->id)->exists()) ||
                   ($user && $user->shop && (int) $user->shop->id === (int) $job->shop_id) || 
                   ($user && $user->isSuperAdmin()) ||
                   (session('impersonator_id') !== null);

        if (!$isOwner) {
            abort(403, 'অননুমোদিত অ্যাক্সেস');
        }

        return view('shop.job_slip', compact('shop', 'job'));
    }

    /**
     * PWA Web Share Target Receiver
     * Accepts shared file from WhatsApp, Gallery, File Manager etc.
     * Exempt from CSRF. Saves temporary file and redirects (HTTP 303) to Quick Print preview.
     */
    public function handleShareTarget(Request $request)
    {
        if ($request->isMethod('GET')) {
            return redirect()->route('shop.quick_print');
        }

        // Locate uploaded file from any parameter name or nested array structure
        $file = null;
        if ($request->hasFile('shared_files')) {
            $f = $request->file('shared_files');
            $file = is_array($f) ? \Illuminate\Support\Arr::first($f) : $f;
        } elseif ($request->hasFile('document')) {
            $file = $request->file('document');
        } elseif ($request->hasFile('file')) {
            $file = $request->file('file');
        } else {
            $allFiles = \Illuminate\Support\Arr::flatten($request->allFiles());
            if (!empty($allFiles)) {
                $file = $allFiles[0];
            }
        }

        if (!$file || !$file->isValid()) {
            return redirect()->route('shop.quick_print')
                ->with('warning_alert', 'কোনো ফাইল শেয়ার করা হয়নি বা ফাইলটি লোড হতে সমস্যা হয়েছে। অনুগ্রহ করে সরাসরি ফাইল নির্বাচন করুন।');
        }

        // Broad list of allowed document and image extensions
        $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png', 'webp', 'bmp', 'gif', 'jfif', 'heic', 'heif', 'doc', 'docx', 'txt'];
        $ext = strtolower($file->getClientOriginalExtension());

        // WhatsApp / Android Gallery fallback: if original filename lacked extension, detect via MIME type
        if (empty($ext) || !in_array($ext, $allowedExtensions)) {
            $mime = strtolower($file->getMimeType() ?: '');
            $mimeMap = [
                'application/pdf' => 'pdf',
                'image/jpeg' => 'jpg',
                'image/jpg' => 'jpg',
                'image/png' => 'png',
                'image/webp' => 'webp',
                'image/bmp' => 'bmp',
                'image/x-ms-bmp' => 'bmp',
                'image/gif' => 'gif',
                'image/jfif' => 'jpg',
                'image/heic' => 'heic',
                'image/heif' => 'heif',
                'application/msword' => 'doc',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
                'text/plain' => 'txt',
            ];
            if (isset($mimeMap[$mime])) {
                $ext = $mimeMap[$mime];
            } else {
                $guessed = strtolower($file->guessExtension() ?: '');
                if ($guessed) {
                    $ext = $guessed;
                }
            }
        }

        if (!in_array($ext, $allowedExtensions)) {
            return redirect()->route('shop.quick_print')
                ->with('error_alert', 'অসমর্থিত ফাইল ফরম্যাট (.' . ($ext ?: 'unknown') . ')! শুধুমাত্র PDF, ছবি (JPG, PNG, WEBP) ও Word ফাইল প্রিন্ট করা যায়।');
        }

        $tempDir = storage_path('app/public/share_temp');
        if (!file_exists($tempDir)) {
            @mkdir($tempDir, 0777, true);
        }

        $tempFileName = 'share_' . time() . '_' . Str::random(10) . '.' . $ext;
        $file->storeAs('share_temp', $tempFileName, 'public');

        $originalName = $file->getClientOriginalName() ?: ('শেয়ারকৃত_ডকুমেন্ট_' . date('His'));
        if (!str_contains($originalName, '.')) {
            $originalName .= '.' . $ext;
        }

        session([
            'quick_print_temp_file' => $tempFileName,
            'quick_print_original_name' => $originalName,
        ]);

        return redirect()->route('shop.quick_print', [
            'file' => $tempFileName,
            'name' => $originalName,
        ])->setStatusCode(303);
    }

    /**
     * Dedicated stream route for temporary shared file preview
     * Ensures correct inline Content-Type header on all mobile & desktop browsers
     */
    public function previewTempFile($filename)
    {
        $cleanName = basename($filename);
        $disk = Storage::disk('public');
        $relativePath = 'share_temp/' . $cleanName;

        if (!$disk->exists($relativePath)) {
            abort(404, 'ফাইল পাওয়া যায়নি বা মেয়াদ শেষ হয়ে গেছে।');
        }

        $filePath = $disk->path($relativePath);
        $mime = $disk->mimeType($relativePath) ?: 'application/octet-stream';

        return response()->file($filePath, [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline; filename="' . $cleanName . '"',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
        ]);
    }

    /**
     * Show Quick Print page with live preview & shop settings
     */
    public function quickPrint(Request $request)
    {
        $shop = $this->getShop();
        $printers = Printer::where('shop_id', $shop->id)->get();

        // Check if a direct file was dropped/uploaded to this page
        if ($request->isMethod('POST') && $request->hasFile('document')) {
            $file = $request->file('document');
            $ext = strtolower($file->getClientOriginalExtension());
            $tempDir = storage_path('app/public/share_temp');
            if (!file_exists($tempDir)) {
                @mkdir($tempDir, 0777, true);
            }
            $tempFileName = 'share_' . time() . '_' . Str::random(10) . '.' . ($ext ?: 'pdf');
            $file->storeAs('share_temp', $tempFileName, 'public');
            $originalName = $file->getClientOriginalName();

            return redirect()->route('shop.quick_print', [
                'file' => $tempFileName,
                'name' => $originalName,
            ]);
        }

        $tempFileName = $request->query('file', session('quick_print_temp_file'));
        $originalName = $request->query('name', session('quick_print_original_name', 'শেয়ারকৃত_ডকুমেন্ট'));

        $fileData = null;
        if ($tempFileName) {
            $disk = Storage::disk('public');
            $relativeTempPath = 'share_temp/' . $tempFileName;
            if ($disk->exists($relativeTempPath)) {
                $filePath = $disk->path($relativeTempPath);
                $ext = strtolower(pathinfo($tempFileName, PATHINFO_EXTENSION));
                $sizeBytes = $disk->size($relativeTempPath);
                $sizeFormatted = $sizeBytes >= 1048576 
                    ? round($sizeBytes / 1048576, 2) . ' MB' 
                    : round($sizeBytes / 1024, 1) . ' KB';

                $totalPages = 1;
                if ($ext === 'pdf') {
                    try {
                        $content = $disk->get($relativeTempPath);
                        if (preg_match_all("/\/Type\s*\/Page\b/", $content, $matches)) {
                            $detected = count($matches[0]);
                            if ($detected > 0) $totalPages = $detected;
                        }
                    } catch (\Exception $e) {}
                }

                $fileData = [
                    'temp_file' => $tempFileName,
                    'original_name' => $originalName ?: $tempFileName,
                    'extension' => $ext,
                    'size_formatted' => $sizeFormatted,
                    'is_pdf' => ($ext === 'pdf'),
                    'is_image' => in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'bmp', 'gif', 'jfif', 'heic', 'heif']),
                    'total_pages' => $totalPages,
                    'url' => route('shop.quick_print.preview', ['filename' => $tempFileName]),
                ];
            }
        }

        return view('shop.quick_print', compact('shop', 'printers', 'fileData'));
    }

    /**
     * Submit Quick Print directly to shop's active printer agent
     */
    public function submitQuickPrint(Request $request)
    {
        $shop = $this->getShop();

        if (!$shop->is_active) {
            return back()->with('error_alert', 'আপনার প্রতিষ্ঠানটি বর্তমানে স্থগিত (Suspended) রয়েছে। নতুন কোনো প্রিন্ট অর্ডার গ্রহণ করা সম্ভব নয়। সুপার এডমিনের সাথে যোগাযোগ করুন।');
        }

        if (!$shop->isSubscriptionActive() && !$shop->is_lifetime) {
            return back()->with('error_alert', 'আপনার সাবস্ক্রিপশন মেয়াদোত্তীর্ণ। সরাসরি প্রিন্ট করতে প্যাকেজ রিনিউ করুন।');
        }

        $request->validate([
            'temp_file' => 'nullable|string',
            'scanned_image_data' => 'nullable|string',
            'document' => 'nullable|file|max:51200|mimes:pdf,doc,docx,jpg,jpeg,png,webp,bmp,gif',
            'color_mode' => 'required|in:bw,color,photocopy',
            'copies' => 'required|integer|min:1|max:100',
            'paper_size' => 'nullable|string',
            'is_duplex' => 'nullable',
            'page_range' => 'nullable|string|max:50',
            'printer_id' => 'nullable|exists:printers,id',
            'customer_label' => 'nullable|string|max:100',
        ]);

        $finalFileName = null;
        $originalName = null;
        $extension = null;
        $totalPages = 1;

        if ($request->filled('scanned_image_data')) {
            $base64 = $request->input('scanned_image_data');
            if (preg_match('/^data:image\/(\w+);base64,/', $base64)) {
                $base64 = substr($base64, strpos($base64, ',') + 1);
            }
            $imageData = base64_decode($base64);
            if ($imageData !== false) {
                $extension = 'jpg';
                $originalName = 'Scanned_Doc_' . date('Ymd_His') . '.jpg';
                $finalFileName = 'shop_quick_' . time() . '_' . Str::random(8) . '.jpg';
                Storage::disk('public')->put('print_uploads/' . $finalFileName, $imageData);
                $totalPages = 1;
                session()->forget(['quick_print_temp_file', 'quick_print_original_name']);
            }
        } elseif ($request->hasFile('document')) {
            $file = $request->file('document');
            $extension = strtolower($file->getClientOriginalExtension());
            $originalName = $file->getClientOriginalName();
            $finalFileName = 'shop_quick_' . time() . '_' . Str::random(8) . '.' . $extension;
            $file->storeAs('print_uploads', $finalFileName, 'public');

            if ($extension === 'pdf') {
                try {
                    $content = file_get_contents($file->getRealPath());
                    if (preg_match_all("/\/Type\s*\/Page\b/", $content, $matches)) {
                        $detected = count($matches[0]);
                        if ($detected > 0) $totalPages = $detected;
                    }
                } catch (\Exception $e) {}
            }
        } elseif ($request->filled('temp_file')) {
            $tempFile = basename($request->temp_file);
            $disk = Storage::disk('public');
            $relativeSource = 'share_temp/' . $tempFile;

            if (!$disk->exists($relativeSource)) {
                return back()->with('error_alert', 'শেয়ারকৃত ফাইলটির মেয়াদ শেষ হয়ে গেছে বা পাওয়া যায়নি। অনুগ্রহ করে ফাইলটি পুনরায় নির্বাচন করুন।');
            }

            $extension = strtolower(pathinfo($tempFile, PATHINFO_EXTENSION));
            $originalName = $request->input('original_name', 'Quick_Print_' . date('Ymd_His') . '.' . $extension);
            $finalFileName = 'shop_quick_' . time() . '_' . Str::random(8) . '.' . $extension;
            $relativeDest = 'print_uploads/' . $finalFileName;

            $disk->move($relativeSource, $relativeDest);

            $totalPages = (int) $request->input('detected_pages', 1);
            if ($totalPages < 1) {
                if ($extension === 'pdf' && $disk->exists($relativeDest)) {
                    try {
                        $content = $disk->get($relativeDest);
                        if (preg_match_all("/\/Type\s*\/Page\b/", $content, $matches)) {
                            $detected = count($matches[0]);
                            if ($detected > 0) $totalPages = $detected;
                        }
                    } catch (\Exception $e) {}
                }
            }
            if ($totalPages < 1) $totalPages = 1;

            session()->forget(['quick_print_temp_file', 'quick_print_original_name']);
        } else {
            return back()->with('error_alert', 'অনুগ্রহ করে প্রিন্ট করার জন্য একটি ফাইল দিন।');
        }

        $copies = (int) $request->copies;
        $isDuplex = $request->boolean('is_duplex');
        $colorMode = $request->color_mode;
        $paperSize = $request->input('paper_size', 'A4');
        $customerLabel = $request->input('customer_label') ?: 'হোয়াটসঅ্যাপ / সরাসরি প্রিন্ট';

        // Select printer (or fallback to primary/first)
        $printerId = $request->printer_id;
        if (!$printerId) {
            $defaultPrinter = Printer::where('shop_id', $shop->id)->first();
            $printerId = $defaultPrinter ? $defaultPrinter->id : null;
        }

        $jobToken = 'PS-SP-' . strtoupper(Str::random(6));

        $job = PrintJob::create([
            'shop_id' => $shop->id,
            'printer_id' => $printerId,
            'job_token' => $jobToken,
            'customer_name' => $customerLabel,
            'customer_phone' => null,
            'pickup_reference' => 'দোকানদার সরাসরি প্রিন্ট',
            'file_name' => $finalFileName,
            'original_name' => $originalName,
            'file_path' => 'print_uploads/' . $finalFileName,
            'file_type' => $extension,
            'total_pages' => $totalPages,
            'copies' => $copies,
            'color_mode' => $colorMode,
            'is_duplex' => $isDuplex,
            'paper_size' => $paperSize,
            'page_range' => $request->input('page_range'),
            'total_amount' => 0.00,
            'payment_method' => 'cash',
            'payment_status' => 'paid',
            'print_status' => 'queued',
        ]);

        return redirect()->route('shop.dashboard')->with('success', "✅ প্রিন্ট অর্ডার (#{$jobToken}) সফলভাবে পাঠানো হয়েছে! উইন্ডোজ এজেন্ট সরাসরি প্রিন্ট শুরু করছে...");
    }

    /**
     * Cancel / Delete temporary share file
     */
    public function deleteTempShareFile(Request $request)
    {
        $tempFile = $request->input('temp_file');
        if ($tempFile) {
            $cleanName = basename($tempFile);
            Storage::disk('public')->delete('share_temp/' . $cleanName);
        }
        session()->forget(['quick_print_temp_file', 'quick_print_original_name']);

        return redirect()->route('shop.quick_print')->with('info', 'ফাইলটি মুছে ফেলা হয়েছে। নতুন ফাইল বেছে নিন।');
    }

    /**
     * Reports View (Daily, Weekly, Monthly, Custom)
     */
    public function reports(Request $request)
    {
        $shop = $this->getShop();
        $period = $request->input('period', 'today');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = PrintJob::where('shop_id', $shop->id);

        if ($period === 'today') {
            $query->whereDate('created_at', today());
            $periodLabel = 'আজকের রিপোর্ট (' . today()->format('d M, Y') . ')';
        } elseif ($period === 'yesterday') {
            $query->whereDate('created_at', today()->subDay());
            $periodLabel = 'গতকালের রিপোর্ট (' . today()->subDay()->format('d M, Y') . ')';
        } elseif ($period === 'this_week') {
            $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
            $periodLabel = 'এই সপ্তাহের রিপোর্ট';
        } elseif ($period === 'this_month') {
            $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
            $periodLabel = 'এই মাসের রিপোর্ট (' . now()->format('F Y') . ')';
        } elseif ($period === 'last_month') {
            $query->whereMonth('created_at', now()->subMonth()->month)->whereYear('created_at', now()->subMonth()->year);
            $periodLabel = 'গত মাসের রিপোর্ট (' . now()->subMonth()->format('F Y') . ')';
        } elseif ($period === 'custom' && $startDate && $endDate) {
            $query->whereDate('created_at', '>=', $startDate)->whereDate('created_at', '<=', $endDate);
            $periodLabel = 'তারিখ: ' . $startDate . ' থেকে ' . $endDate;
        } else {
            $query->whereDate('created_at', today());
            $periodLabel = 'আজকের রিপোর্ট';
        }

        $allPeriodJobs = (clone $query)->get();

        $metrics = [
            'total_jobs' => $allPeriodJobs->count(),
            'total_revenue' => $allPeriodJobs->where('payment_status', 'paid')->sum('total_amount'),
            'cash_revenue' => $allPeriodJobs->where('payment_status', 'paid')->where('payment_method', 'cash')->sum('total_amount'),
            'digital_revenue' => $allPeriodJobs->where('payment_status', 'paid')->whereIn('payment_method', ['bkash', 'nagad'])->sum('total_amount'),
            'total_pages_printed' => $allPeriodJobs->where('print_status', 'printed')->sum(fn($j) => $j->total_pages * $j->copies),
            'printed_jobs' => $allPeriodJobs->where('print_status', 'printed')->count(),
            'cancelled_jobs' => $allPeriodJobs->where('print_status', 'cancelled')->count(),
            'bw_jobs' => $allPeriodJobs->where('color_mode', 'bw')->count(),
            'color_jobs' => $allPeriodJobs->where('color_mode', 'color')->count(),
        ];

        $jobs = $query->latest()->paginate(25)->withQueryString();

        return view('shop.reports', compact('shop', 'jobs', 'metrics', 'period', 'startDate', 'endDate', 'periodLabel'));
    }

    /**
     * Export Report to Excel/CSV with UTF-8 BOM
     */
    public function exportReportCsv(Request $request)
    {
        $shop = $this->getShop();
        $period = $request->input('period', 'today');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = PrintJob::where('shop_id', $shop->id);

        if ($period === 'today') {
            $query->whereDate('created_at', today());
        } elseif ($period === 'yesterday') {
            $query->whereDate('created_at', today()->subDay());
        } elseif ($period === 'this_week') {
            $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($period === 'this_month') {
            $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
        } elseif ($period === 'last_month') {
            $query->whereMonth('created_at', now()->subMonth()->month)->whereYear('created_at', now()->subMonth()->year);
        } elseif ($period === 'custom' && $startDate && $endDate) {
            $query->whereDate('created_at', '>=', $startDate)->whereDate('created_at', '<=', $endDate);
        }

        $jobs = $query->latest()->get();
        $fileName = 'PrintSheba-Report-' . Str::slug($shop->name) . '-' . date('Y-m-d') . '.csv';

        $callback = function () use ($jobs, $shop) {
            $file = fopen('php://output', 'w');
            // Write UTF-8 BOM so Microsoft Excel renders Bengali characters correctly
            fputs($file, "\xEF\xBB\xBF");

            // Header Row
            if ($shop->is_office_mode) {
                fputcsv($file, [
                    'টোকেন নং (Token)',
                    'তারিখ ও সময় (Date & Time)',
                    'কর্মকর্তা / বিভাগ (Employee / Dept)',
                    'রেফারেন্স / পিসি (Reference / PC)',
                    'ফাইল নাম (File Name)',
                    'পেজ সংখ্যা (Pages)',
                    'কপি (Copies)',
                    'মোট পেজ শিট (Total Sheets)',
                    'মোড (Color/BW)',
                    'ডুপ্লেক্স (Duplex)',
                    'পেপার সাইজ (Paper Size)',
                    'প্রিন্ট অবস্থা (Print Status)'
                ]);
            } else {
                fputcsv($file, [
                    'টোকেন নং (Token)',
                    'তারিখ ও সময় (Date & Time)',
                    'গ্রাহক / রেফারেন্স (Customer / Ref)',
                    'ফাইল নাম (File Name)',
                    'পেজ সংখ্যা (Pages)',
                    'কপি (Copies)',
                    'মোট পেজ (Total Sheets)',
                    'মোড (Color/BW)',
                    'ডুপ্লেক্স (Duplex)',
                    'পেমেন্ট মেথড (Method)',
                    'ট্রানজেকশন আইডি (TrxID)',
                    'টাকার পরিমাণ (Amount Tk)',
                    'পেমেন্ট অবস্থা (Payment Status)',
                    'প্রিন্ট অবস্থা (Print Status)'
                ]);
            }

            foreach ($jobs as $job) {
                if ($shop->is_office_mode) {
                    fputcsv($file, [
                        $job->job_token,
                        $job->created_at->format('d/m/Y h:i A'),
                        $job->customer_name ?: 'অফিস কর্মী',
                        $job->pickup_reference ?: '-',
                        $job->original_name ?: $job->file_name,
                        $job->total_pages,
                        $job->copies,
                        $job->total_pages * $job->copies,
                        $job->color_mode === 'color' ? 'কালার' : 'সাদাকালো',
                        $job->is_duplex ? 'উভয় পাশ (Duplex)' : 'এক পাশ',
                        $job->paper_size ?: 'A4',
                        $job->print_status === 'printed' ? 'প্রিন্ট সম্পন্ন' : ($job->print_status === 'cancelled' ? 'বাতিল' : 'কিউতে আছে')
                    ]);
                } else {
                    fputcsv($file, [
                        $job->job_token,
                        $job->created_at->format('d/m/Y h:i A'),
                        $job->pickup_reference ?: ($job->customer_name ?: 'কাস্টমার'),
                        $job->original_name ?: $job->file_name,
                        $job->total_pages,
                        $job->copies,
                        $job->total_pages * $job->copies,
                        $job->color_mode === 'color' ? 'কালার' : 'সাদাকালো',
                        $job->is_duplex ? 'উভয় পাশ (Duplex)' : 'এক পাশ',
                        strtoupper($job->payment_method),
                        $job->payment_trx_id ?: '-',
                        number_format($job->total_amount, 2),
                        $job->payment_status === 'paid' ? 'পরিশোধিত (PAID)' : 'অপেক্ষমান (PENDING)',
                        $job->print_status === 'printed' ? 'প্রিন্ট সম্পন্ন' : ($job->print_status === 'cancelled' ? 'বাতিল' : 'কিউতে আছে')
                    ]);
                }
            }
            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ]);
    }

    /**
     * Print-friendly Printable Summary Report
     */
    public function printReport(Request $request)
    {
        $shop = $this->getShop();
        $period = $request->input('period', 'today');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = PrintJob::where('shop_id', $shop->id);

        if ($period === 'today') {
            $query->whereDate('created_at', today());
            $periodLabel = 'আজকের অডিট রিপোর্ট (' . today()->format('d M, Y') . ')';
        } elseif ($period === 'yesterday') {
            $query->whereDate('created_at', today()->subDay());
            $periodLabel = 'গতকালের অডিট রিপোর্ট (' . today()->subDay()->format('d M, Y') . ')';
        } elseif ($period === 'this_week') {
            $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
            $periodLabel = 'এই সপ্তাহের অডিট রিপোর্ট';
        } elseif ($period === 'this_month') {
            $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
            $periodLabel = 'এই মাসের অডিট রিপোর্ট (' . now()->format('F Y') . ')';
        } elseif ($period === 'last_month') {
            $query->whereMonth('created_at', now()->subMonth()->month)->whereYear('created_at', now()->subMonth()->year);
            $periodLabel = 'গত মাসের অডিট রিপোর্ট (' . now()->subMonth()->format('F Y') . ')';
        } elseif ($period === 'custom' && $startDate && $endDate) {
            $query->whereDate('created_at', '>=', $startDate)->whereDate('created_at', '<=', $endDate);
            $periodLabel = 'তারিখ: ' . $startDate . ' থেকে ' . $endDate;
        } else {
            $query->whereDate('created_at', today());
            $periodLabel = 'আজকের অডিট রিপোর্ট';
        }

        $jobs = $query->latest()->get();

        $metrics = [
            'total_jobs' => $jobs->count(),
            'total_revenue' => $jobs->where('payment_status', 'paid')->sum('total_amount'),
            'cash_revenue' => $jobs->where('payment_status', 'paid')->where('payment_method', 'cash')->sum('total_amount'),
            'digital_revenue' => $jobs->where('payment_status', 'paid')->whereIn('payment_method', ['bkash', 'nagad'])->sum('total_amount'),
            'total_pages_printed' => $jobs->where('print_status', 'printed')->sum(fn($j) => $j->total_pages * $j->copies),
            'printed_jobs' => $jobs->where('print_status', 'printed')->count(),
            'cancelled_jobs' => $jobs->where('print_status', 'cancelled')->count(),
        ];

        return view('shop.report_print', compact('shop', 'jobs', 'metrics', 'periodLabel'));
    }
}
