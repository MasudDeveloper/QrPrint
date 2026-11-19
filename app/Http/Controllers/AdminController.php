<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\User;
use App\Models\PrintJob;
use App\Models\Setting;
use App\Models\ShopSubscription;
use App\Models\SystemErrorLog;
use App\Services\SystemCrashLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalShops = Shop::count();
        $activeShops = Shop::where('is_active', true)->count();
        $totalJobs = PrintJob::count();
        $completedJobs = PrintJob::where('print_status', 'printed')->count();
        $totalRevenue = PrintJob::where('payment_status', 'paid')->sum('total_amount');
        $pendingSubscriptionsCount = ShopSubscription::where('status', 'pending')->count();
        
        $recentJobs = PrintJob::with('shop')->latest()->take(10)->get();
        $videoUrl = Setting::get('homepage_video_url', '');
        $videoTitle = Setting::get('homepage_video_title', 'কীভাবে ২ মিনিটে কাস্টমার নিজে নিজে প্রিন্ট করবে — লাইভ ভিডিও ডেমো');

        return view('admin.dashboard', compact(
            'totalShops',
            'activeShops',
            'totalJobs',
            'completedJobs',
            'totalRevenue',
            'pendingSubscriptionsCount',
            'recentJobs',
            'videoUrl',
            'videoTitle'
        ));
    }

    public function liveStatus()
    {
        $totalShops = Shop::count();
        $activeShops = Shop::where('is_active', true)->count();
        $totalJobs = PrintJob::count();
        $completedJobs = PrintJob::where('print_status', 'printed')->count();
        $totalRevenue = PrintJob::where('payment_status', 'paid')->sum('total_amount');
        $pendingSubscriptionsCount = ShopSubscription::where('status', 'pending')->count();
        
        $recentJobs = PrintJob::with('shop')->latest()->take(10)->get()->map(function ($job) {
            return [
                'job_token' => $job->job_token,
                'shop_name' => $job->shop->name ?? 'N/A',
                'original_name' => $job->original_name,
                'color_mode' => $job->color_mode,
                'total_amount' => $job->total_amount,
                'payment_status' => $job->payment_status,
                'print_status' => $job->print_status,
            ];
        });

        return response()->json([
            'status' => 'success',
            'total_shops' => $totalShops,
            'active_shops' => $activeShops,
            'total_jobs' => $totalJobs,
            'completed_jobs' => $completedJobs,
            'total_revenue' => number_format($totalRevenue, 2),
            'pending_subscriptions_count' => $pendingSubscriptionsCount,
            'recent_jobs' => $recentJobs,
        ]);
    }

    public function settings()
    {
        $videoUrl = Setting::get('homepage_video_url', '');
        $videoTitle = Setting::get('homepage_video_title', '');
        $hotline = Setting::get('hotline_number', '01644416378');
        $email = Setting::get('support_email', 'masud.info@gmail.com');
        $developerUrl = Setting::get('developer_url', 'https://mrdeveloper.xyz');
        $adminBkash = Setting::get('admin_bkash_number', '01644416378');
        $adminNagad = Setting::get('admin_nagad_number', '01644416378');
        $paymentInstructions = Setting::get('admin_payment_instructions', 'বিকাশ বা নগদ সেন্ড মানি করে নিচের বক্সে আপনার Transaction ID ও সেন্ডার নাম্বারটি দিন।');

        // bKash Merchant PGW Settings
        $bkashEnabled = (bool) Setting::get('bkash_pgw_enabled', false);
        $bkashSandbox = (bool) Setting::get('bkash_pgw_sandbox', true);
        $bkashAppKey = Setting::get('bkash_pgw_app_key', '');
        $bkashAppSecret = Setting::get('bkash_pgw_app_secret', '');
        $bkashUsername = Setting::get('bkash_pgw_username', '');
        $bkashPassword = Setting::get('bkash_pgw_password', '');

        // SMTP Mail Settings
        $mailHost = Setting::get('mail_host', env('MAIL_HOST', 'smtp.gmail.com'));
        $mailPort = Setting::get('mail_port', env('MAIL_PORT', '587'));
        $mailUsername = Setting::get('mail_username', env('MAIL_USERNAME', ''));
        $mailPassword = Setting::get('mail_password', env('MAIL_PASSWORD', ''));
        $mailEncryption = Setting::get('mail_encryption', env('MAIL_ENCRYPTION', 'tls'));
        $mailFromAddress = Setting::get('mail_from_address', env('MAIL_FROM_ADDRESS', 'noreply@printsheba.com'));
        $mailFromName = Setting::get('mail_from_name', env('MAIL_FROM_NAME', 'PrintSheba'));

        return view('admin.settings', compact(
            'videoUrl', 'videoTitle', 'hotline', 'email', 'developerUrl', 'adminBkash', 'adminNagad', 'paymentInstructions',
            'bkashEnabled', 'bkashSandbox', 'bkashAppKey', 'bkashAppSecret', 'bkashUsername', 'bkashPassword',
            'mailHost', 'mailPort', 'mailUsername', 'mailPassword', 'mailEncryption', 'mailFromAddress', 'mailFromName'
        ));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'homepage_video_url' => 'nullable|string',
            'homepage_video_title' => 'nullable|string|max:255',
            'hotline_number' => 'nullable|string|max:30',
            'support_email' => 'nullable|string|email|max:100',
            'admin_bkash_number' => 'nullable|string|max:30',
            'admin_nagad_number' => 'nullable|string|max:30',
            'admin_payment_instructions' => 'nullable|string|max:500',
            'bkash_pgw_app_key' => 'nullable|string|max:100',
            'bkash_pgw_app_secret' => 'nullable|string|max:100',
            'bkash_pgw_username' => 'nullable|string|max:100',
            'bkash_pgw_password' => 'nullable|string|max:100',
            'mail_host' => 'nullable|string|max:100',
            'mail_port' => 'nullable|string|max:10',
            'mail_username' => 'nullable|string|max:100',
            'mail_password' => 'nullable|string|max:100',
            'mail_encryption' => 'nullable|string|max:10',
            'mail_from_address' => 'nullable|string|email|max:100',
            'mail_from_name' => 'nullable|string|max:100',
        ]);

        Setting::set('homepage_video_url', $request->homepage_video_url ?? '');
        Setting::set('homepage_video_title', $request->homepage_video_title ?? '');
        Setting::set('hotline_number', $request->hotline_number ?? '01644416378');
        Setting::set('support_email', $request->support_email ?? 'masud.info@gmail.com');
        Setting::set('admin_bkash_number', $request->admin_bkash_number ?? '01644416378');
        Setting::set('admin_nagad_number', $request->admin_nagad_number ?? '01644416378');
        Setting::set('admin_payment_instructions', $request->admin_payment_instructions ?? '');

        // bKash Merchant Settings
        Setting::set('bkash_pgw_enabled', $request->boolean('bkash_pgw_enabled'));
        Setting::set('bkash_pgw_sandbox', $request->boolean('bkash_pgw_sandbox'));
        Setting::set('bkash_pgw_app_key', $request->bkash_pgw_app_key ?? '');
        Setting::set('bkash_pgw_app_secret', $request->bkash_pgw_app_secret ?? '');
        Setting::set('bkash_pgw_username', $request->bkash_pgw_username ?? '');
        Setting::set('bkash_pgw_password', $request->bkash_pgw_password ?? '');

        // SMTP Settings
        Setting::set('mail_host', $request->mail_host ?? '');
        Setting::set('mail_port', $request->mail_port ?? '587');
        Setting::set('mail_username', $request->mail_username ?? '');
        Setting::set('mail_password', $request->mail_password ?? '');
        Setting::set('mail_encryption', $request->mail_encryption ?? 'tls');
        Setting::set('mail_from_address', $request->mail_from_address ?? 'noreply@printsheba.com');
        Setting::set('mail_from_name', $request->mail_from_name ?? 'PrintSheba');

        return back()->with('success', 'সকল গ্লোবাল সেটিংস (ভিডিও, পেমেন্ট, বিকাশ গেটওয়ে ও ইমেইল SMTP) সফলভাবে আপডেট করা হয়েছে!');
    }

    public function sendTestEmail(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email|max:100',
        ]);

        $targetEmail = $request->input('test_email');

        $mailHost = Setting::get('mail_host', env('MAIL_HOST'));
        $mailPort = Setting::get('mail_port', env('MAIL_PORT'));
        $mailUsername = Setting::get('mail_username', env('MAIL_USERNAME'));
        $mailPassword = Setting::get('mail_password', env('MAIL_PASSWORD'));
        $mailEncryption = Setting::get('mail_encryption', env('MAIL_ENCRYPTION', 'tls'));
        $fromAddress = Setting::get('mail_from_address', env('MAIL_FROM_ADDRESS', 'noreply@printsheba.com'));
        $fromName = Setting::get('mail_from_name', env('MAIL_FROM_NAME', 'PrintSheba'));

        if (empty($mailHost) || empty($mailUsername)) {
            return back()->with('error', 'প্রথমে SMTP হোস্ট এবং ইউজারনেম সংরক্ষণ করুন।');
        }

        // Dynamically apply SMTP runtime configuration
        config([
            'mail.default' => 'smtp',
            'mail.mailers.smtp.host' => $mailHost,
            'mail.mailers.smtp.port' => (int) $mailPort,
            'mail.mailers.smtp.username' => $mailUsername,
            'mail.mailers.smtp.password' => $mailPassword,
            'mail.mailers.smtp.encryption' => $mailEncryption,
            'mail.from.address' => $fromAddress,
            'mail.from.name' => $fromName,
        ]);

        try {
            \Illuminate\Support\Facades\Mail::raw("অভিনন্দন! আপনার PrintSheba SMTP ইমেইল কনফিগারেশন সফলভাবে কাজ করছে।\n\nতারিখ: " . now()->format('d M, Y h:i A') . "\nসার্ভার: " . url('/'), function ($message) use ($targetEmail, $fromAddress, $fromName) {
                $message->to($targetEmail)
                    ->from($fromAddress, $fromName)
                    ->subject('PrintSheba – SMTP টেস্ট ইমেইল ভেরিফিকেশন');
            });

            return back()->with('success', "✅ টেস্ট ইমেইল সফলভাবে পাঠানো হয়েছে: {$targetEmail}");
        } catch (\Throwable $e) {
            return back()->with('error', '❌ ইমেইল পাঠাতে ব্যর্থ: ' . $e->getMessage());
        }
    }

    public function shops()
    {
        $shops = Shop::with(['user', 'printers'])->withCount('printJobs')->latest()->paginate(15);
        return view('admin.shops', compact('shops'));
    }

    public function toggleShopStatus(Shop $shop)
    {
        $shop->is_active = !$shop->is_active;
        $shop->save();

        return back()->with('success', 'দোকানের স্ট্যাটাস পরিবর্তন করা হয়েছে: ' . ($shop->is_active ? 'সক্রিয় (Active)' : 'নিষ্ক্রিয় (Inactive)'));
    }

    /**
     * Admin modifies plan/validity or grants gift/free extension
     */
    public function updateShopPlan(Request $request, Shop $shop)
    {
        $request->validate([
            'action_type' => 'required|string', // set_plan, add_days, make_lifetime
            'plan' => 'nullable|string',
            'days' => 'nullable|integer|min:1',
        ]);

        if ($request->action_type === 'make_lifetime') {
            $shop->update([
                'subscription_plan' => 'lifetime',
                'is_lifetime' => true,
                'subscription_expires_at' => null,
                'is_active' => true,
            ]);
            return back()->with('success', "{$shop->name} দোকানকে আজীবন (Lifetime Unlimited) ফ্রি লাইসেন্স দেওয়া হয়েছে!");
        }

        if ($request->action_type === 'add_days') {
            $days = (int) $request->days;
            $currentExpire = ($shop->subscription_expires_at && $shop->subscription_expires_at->isFuture()) 
                ? $shop->subscription_expires_at 
                : now();
            
            $newExpire = $currentExpire->addDays($days);
            $shop->update([
                'subscription_expires_at' => $newExpire,
                'is_lifetime' => false,
                'is_active' => true,
            ]);

            return back()->with('success', "{$shop->name} এর মেয়াদ সফলভাবে +{$days} দিন বৃদ্ধি করা হয়েছে! নতুন মেয়াদ: {$newExpire->format('d M, Y')}");
        }

        if ($request->action_type === 'set_plan') {
            $plan = $request->plan;
            $days = match($plan) {
                'monthly_starter' => 30,
                'yearly_pro' => 365,
                default => 30,
            };

            $currentExpire = ($shop->subscription_expires_at && $shop->subscription_expires_at->isFuture()) 
                ? $shop->subscription_expires_at 
                : now();

            $shop->update([
                'subscription_plan' => $plan,
                'subscription_expires_at' => $currentExpire->addDays($days),
                'is_lifetime' => false,
                'is_active' => true,
            ]);

            return back()->with('success', "{$shop->name} এর প্ল্যান আপডেট করা হয়েছে: {$plan} (+{$days} দিন)");
        }

        return back()->with('error', 'অকার্যকর অনুরোধ।');
    }

    /**
     * Super Admin login directly into Shop dashboard
     */
    public function loginAsShop(Shop $shop)
    {
        if (!$shop->user) {
            return back()->with('error', 'এই দোকানের সাথে যুক্ত কোনো ইউজার অ্যাকাউন্ট পাওয়া যায়নি।');
        }

        // Store original superadmin ID and target shop ID in session so they can switch back anytime
        session([
            'impersonator_id' => Auth::id(),
            'impersonated_shop_id' => $shop->id,
        ]);
        
        Auth::login($shop->user);

        return redirect()->route('shop.dashboard')->with('success', "সুপার এডমিন হিসেবে আপনি '{$shop->name}' এর ড্যাশবোর্ডে প্রবেশ করেছেন।");
    }

    /**
     * Revert impersonation back to Super Admin
     */
    public function revertImpersonation()
    {
        $impersonatorId = session('impersonator_id');
        if ($impersonatorId) {
            $adminUser = User::find($impersonatorId);
            if ($adminUser && $adminUser->isSuperAdmin()) {
                session()->forget(['impersonator_id', 'impersonated_shop_id']);
                Auth::login($adminUser);
                return redirect()->route('admin.shops')->with('success', 'সফলভাবে সুপার এডমিন ড্যাশবোর্ডে ফিরে এসেছেন।');
            }
        }
        return redirect()->route('home');
    }

    /**
     * Subscriptions Management List
     */
    public function subscriptions()
    {
        $subscriptions = ShopSubscription::with('shop')->latest()->paginate(20);
        $pendingCount = ShopSubscription::where('status', 'pending')->count();
        return view('admin.subscriptions', compact('subscriptions', 'pendingCount'));
    }

    /**
     * Approve a shop subscription payment request
     */
    public function approveSubscription(Request $request, ShopSubscription $subscription)
    {
        $shop = $subscription->shop;
        if (!$shop) {
            return back()->with('error', 'দোকান পাওয়া যায়নি।');
        }

        // Apply duration based on requested plan
        if ($subscription->plan === 'lifetime') {
            $shop->update([
                'subscription_plan' => 'lifetime',
                'is_lifetime' => true,
                'subscription_expires_at' => null,
                'is_active' => true,
            ]);
        } else {
            $days = match($subscription->plan) {
                'monthly_starter' => 30,
                'yearly_pro' => 365,
                default => 30,
            };

            $currentExpire = ($shop->subscription_expires_at && $shop->subscription_expires_at->isFuture()) 
                ? $shop->subscription_expires_at 
                : now();

            $shop->update([
                'subscription_plan' => $subscription->plan,
                'subscription_expires_at' => $currentExpire->addDays($days),
                'is_lifetime' => false,
                'is_active' => true,
            ]);
        }

        $subscription->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => Auth::id(),
            'notes' => $request->notes ?? 'সুপার এডমিন কর্তৃক অনুমোদিত',
        ]);

        return back()->with('success', "পেমেন্ট সফলভাবে অনুমোদিত হয়েছে এবং {$shop->name} এর প্যাকেজ সক্রিয় করা হয়েছে!");
    }

    /**
     * Reject a shop subscription payment request
     */
    public function rejectSubscription(Request $request, ShopSubscription $subscription)
    {
        $subscription->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'notes' => $request->notes ?? 'পেমেন্ট ভেরিফাই করা সম্ভব হয়নি',
        ]);

        return back()->with('success', "সাবস্ক্রিপশন আবেদনটি বাতিল (Rejected) করা হয়েছে।");
    }

    public function printJobs()
    {
        $jobs = PrintJob::with('shop')->latest()->paginate(25);
        return view('admin.print_jobs', compact('jobs'));
    }

    public function approvePayment(PrintJob $job)
    {
        $job->update([
            'payment_status' => 'paid',
            'print_status' => 'queued',
        ]);

        return back()->with('success', "টোকেন #{$job->job_token} এর পেমেন্ট অ্যাডমিন কর্তৃক অনুমোদিত হয়েছে এবং প্রিন্ট কিউতে পাঠানো হয়েছে!");
    }

    public function reprintJob(PrintJob $job)
    {
        $job->update([
            'payment_status' => 'paid',
            'print_status' => 'queued',
        ]);

        return back()->with('success', "টোকেন #{$job->job_token} পুনরায় প্রিন্ট করার জন্য কিউতে পাঠানো হয়েছে!");
    }

    public function cancelJob(Request $request, PrintJob $job)
    {
        $reason = trim($request->input('cancel_reason', ''));
        if (empty($reason) || $reason === 'other') {
            $reason = trim($request->input('custom_reason', '')) ?: 'অ্যাডমিন কর্তৃক বাতিল';
        }

        $job->update([
            'payment_status' => 'cancelled',
            'print_status'   => 'cancelled',
            'cancel_reason'  => $reason,
            'cancelled_at'   => now(),
        ]);

        return back()->with('success', "টোকেন #{$job->job_token} এর অর্ডারটি অ্যাডমিন কর্তৃক বাতিল করা হয়েছে।");
    }

    public function systemLogs(Request $request)
    {
        $status = $request->query('status', 'unresolved');
        $level = $request->query('level', 'all');
        $search = $request->query('search', '');
        $type = $request->query('type', 'all');
        $tab = $request->query('tab', 'database');

        $query = SystemErrorLog::with(['shop', 'user', 'resolver']);

        if ($status !== 'all' && in_array($status, ['unresolved', 'resolved'])) {
            $query->where('status', $status);
        }

        if ($level !== 'all' && !empty($level)) {
            $query->where('level', $level);
        }

        if ($type === 'spooler') {
            $query->where(function ($q) {
                $q->where('error_type', 'like', '%Spooler%')
                  ->orWhere('error_type', 'like', '%Agent%')
                  ->orWhere('error_type', 'like', '%Print%');
            });
        } elseif ($type !== 'all' && !empty($type)) {
            $query->where('error_type', $type);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('message', 'like', "%{$search}%")
                  ->orWhere('error_type', 'like', "%{$search}%")
                  ->orWhere('file', 'like', "%{$search}%")
                  ->orWhere('url', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        $logs = $query->orderByDesc('last_seen_at')->paginate(25)->withQueryString();

        // Metrics
        $totalUnresolved = SystemErrorLog::where('status', 'unresolved')->count();
        $criticalCount = SystemErrorLog::where('status', 'unresolved')->whereIn('level', ['critical', 'emergency'])->count();
        $spoolerErrorsCount = SystemErrorLog::where('status', 'unresolved')
            ->where(function ($q) {
                $q->where('error_type', 'like', '%Spooler%')
                  ->orWhere('error_type', 'like', '%Agent%')
                  ->orWhere('error_type', 'like', '%Print%');
            })->count();
        $todayErrorsCount = SystemErrorLog::whereDate('created_at', today())->count();

        $rawLogs = $tab === 'raw' ? SystemCrashLogger::getRawLogs(300) : [];

        return view('admin.system_logs', compact(
            'logs',
            'totalUnresolved',
            'criticalCount',
            'spoolerErrorsCount',
            'todayErrorsCount',
            'status',
            'level',
            'search',
            'type',
            'tab',
            'rawLogs'
        ));
    }

    public function systemLogDetails(SystemErrorLog $log)
    {
        $log->load(['shop', 'user', 'resolver']);
        return response()->json($log);
    }

    public function resolveSystemLog(SystemErrorLog $log)
    {
        $log->update([
            'status' => 'resolved',
            'resolved_at' => now(),
            'resolved_by' => auth()->id(),
        ]);

        return back()->with('success', "লগ #{$log->id} সমাধান চিহ্নিত করা হয়েছে!");
    }

    public function unresolveSystemLog(SystemErrorLog $log)
    {
        $log->update([
            'status' => 'unresolved',
            'resolved_at' => null,
            'resolved_by' => null,
        ]);

        return back()->with('success', "লগ #{$log->id} পুনরায় অমীমাংসিত হিসেবে চিহ্নিত করা হয়েছে।");
    }

    public function deleteSystemLog(SystemErrorLog $log)
    {
        $log->delete();
        return back()->with('success', "লগ #{$log->id} সফলভাবে মুছে ফেলা হয়েছে।");
    }

    public function clearSystemLogs(Request $request)
    {
        $scope = $request->input('scope', 'resolved');
        if ($scope === 'all') {
            SystemErrorLog::truncate();
            return back()->with('success', "সকল সিস্টেম এরর লগ সম্পূর্ণ মুছে ফেলা হয়েছে!");
        }

        SystemErrorLog::where('status', 'resolved')->delete();
        return back()->with('success', "সকল সমাধানকৃত এরর লগ সফলভাবে মুছে ফেলা হয়েছে!");
    }

    public function clearRawLog()
    {
        SystemCrashLogger::clearRawLogs();
        return back()->with('success', "Laravel র (Raw) ফাইল লগ সফলভাবে খালি করা হয়েছে!");
    }

    public function testCrash()
    {
        try {
            throw new \Exception("টেস্ট ক্র্যাশ রিপোর্ট — সুপার এডমিন টেস্ট " . now()->format('Y-m-d H:i:s'));
        } catch (\Throwable $e) {
            SystemCrashLogger::logException($e, 'critical');
        }

        return redirect()->route('admin.logs')->with('success', "টেস্ট ক্র্যাশ সফলভাবে সিস্টেমে রেকর্ড করা হয়েছে! নিচে দেখুন।");
    }
}
