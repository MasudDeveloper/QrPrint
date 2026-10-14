<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\User;
use App\Models\PrintJob;
use App\Models\Setting;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalShops = Shop::count();
        $activeShops = Shop::where('is_active', true)->count();
        $totalJobs = PrintJob::count();
        $completedJobs = PrintJob::where('print_status', 'printed')->count();
        $totalRevenue = PrintJob::where('payment_status', 'paid')->sum('total_amount');
        
        $recentJobs = PrintJob::with('shop')->latest()->take(10)->get();
        $videoUrl = Setting::get('homepage_video_url', '');
        return view('admin.dashboard', compact(
            'totalShops',
            'activeShops',
            'totalJobs',
            'completedJobs',
            'totalRevenue',
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

        return view('admin.settings', compact('videoUrl', 'videoTitle', 'hotline', 'email', 'developerUrl'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'homepage_video_url' => 'nullable|string',
            'homepage_video_title' => 'nullable|string|max:255',
            'hotline_number' => 'nullable|string|max:30',
            'support_email' => 'nullable|string|email|max:100',
        ]);

        Setting::set('homepage_video_url', $request->homepage_video_url ?? '');
        Setting::set('homepage_video_title', $request->homepage_video_title ?? '');
        Setting::set('hotline_number', $request->hotline_number ?? '01644416378');
        Setting::set('support_email', $request->support_email ?? 'masud.info@gmail.com');

        return back()->with('success', 'হোমপেজ ভিডিও ও গ্লোবাল সেটিংস সফলভাবে আপডেট করা হয়েছে!');
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
}
