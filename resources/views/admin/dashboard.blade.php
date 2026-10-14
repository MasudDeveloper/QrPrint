@extends('layouts.admin')

@section('title', 'ড্যাশবোর্ড – সুপার এডমিন')

@section('content')
<div class="space-y-8">
    
    <!-- Top Welcome Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-gradient-to-r from-slate-900 via-slate-950 to-slate-900 p-6 sm:p-8 rounded-3xl text-white border border-slate-800 shadow-2xl relative overflow-hidden">
        <div class="absolute -top-24 -left-24 w-72 h-72 bg-rose-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="space-y-1.5 z-10">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-rose-500/15 text-rose-400 text-xs font-bold border border-rose-500/30">
                <span class="w-2 h-2 rounded-full bg-rose-400 animate-ping"></span>
                <span>সুপার এডমিন কন্ট্রোল সেন্টার</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black">স্বাগতম, MR Developer!</h1>
            <p class="text-xs sm:text-sm text-slate-400">এখান থেকে সারা দেশের সকল সাইবার ক্যাফে ও প্রিন্টিং কার্যক্রম রিয়েল-টাইম পর্যবেক্ষণ করুন</p>
        </div>
        <div class="flex items-center gap-3 z-10">
            <a href="{{ route('admin.settings') }}" class="px-5 py-3 rounded-2xl bg-gradient-to-r from-rose-600 to-rose-700 hover:from-rose-500 hover:to-rose-600 text-white font-bold text-xs shadow-lg shadow-rose-600/30 transition-all flex items-center gap-2">
                <span>🎬 হোমপেজ ভিডিও ও সেটিংস</span>
            </a>
        </div>
    </div>

    <!-- 4 Key Metrics Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        
        <!-- Total Shops -->
        <div class="bg-slate-900/80 p-6 rounded-3xl border border-slate-800 shadow-sm flex flex-col justify-between hover:border-slate-700 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400">মোট দোকান (Shops)</span>
                <span class="w-9 h-9 rounded-2xl bg-purple-500/10 border border-purple-500/20 text-purple-400 flex items-center justify-center text-sm font-bold">🏪</span>
            </div>
            <div class="mt-4">
                <span class="text-3xl font-black text-white font-en">{{ $totalShops }}</span>
                <span class="text-[11px] text-emerald-400 block font-bold mt-1">● {{ $activeShops }} টি সক্রিয়</span>
            </div>
        </div>

        <!-- Total Prints -->
        <div class="bg-slate-900/80 p-6 rounded-3xl border border-slate-800 shadow-sm flex flex-col justify-between hover:border-slate-700 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400">মোট প্রিন্ট সম্পন্ন</span>
                <span class="w-9 h-9 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center text-sm font-bold">🖨️</span>
            </div>
            <div class="mt-4">
                <span class="text-3xl font-black text-white font-en">{{ $completedJobs }}</span>
                <span class="text-[11px] text-slate-400 block font-bold mt-1">সর্বমোট {{ $totalJobs }} টি টাস্ক</span>
            </div>
        </div>

        <!-- Total Revenue Processed -->
        <div class="bg-slate-900/80 p-6 rounded-3xl border border-slate-800 shadow-sm flex flex-col justify-between hover:border-slate-700 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400">মোট প্রিন্ট লেনদেন</span>
                <span class="w-9 h-9 rounded-2xl bg-pink-500/10 border border-pink-500/20 text-pink-400 flex items-center justify-center text-sm font-bold">৳</span>
            </div>
            <div class="mt-4">
                <span class="text-3xl font-black text-emerald-400 font-en">৳ {{ number_format($totalRevenue, 2) }}</span>
                <span class="text-[11px] text-slate-400 block font-bold mt-1">দোকানদারদের মোট সেলস</span>
            </div>
        </div>

        <!-- System Uptime -->
        <div class="bg-slate-900/80 p-6 rounded-3xl border border-slate-800 shadow-sm flex flex-col justify-between hover:border-slate-700 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400">সার্ভার স্ট্যাটাস</span>
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
            </div>
            <div class="mt-4">
                <span class="text-2xl font-black text-emerald-400 font-en">Active & Fast</span>
                <span class="text-[11px] text-slate-400 block font-bold mt-1">০% ফেইল রেট</span>
            </div>
        </div>

    </div>

    <!-- Active Video Widget Preview -->
    <div class="bg-slate-900/80 p-6 rounded-3xl border border-slate-800 shadow-sm space-y-4">
        <div class="flex items-center justify-between border-b border-slate-800 pb-4">
            <div>
                <h3 class="text-base font-bold text-white flex items-center gap-2">
                    <span>🎬 বর্তমানে হোমপেজে সক্রিয় ভিডিও</span>
                </h3>
                <p class="text-xs text-slate-400">হোমপেজে এই ভিডিও লিংকটি প্রদর্শন করা হচ্ছে</p>
            </div>
            <a href="{{ route('admin.settings') }}" class="text-xs font-bold text-rose-400 hover:text-rose-300 hover:underline">
                ভিডিও পরিবর্তন করুন →
            </a>
        </div>

        @if(!empty($videoUrl))
            <div class="p-4 rounded-2xl bg-slate-950 border border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="space-y-1">
                    <span class="text-xs font-bold text-white">{{ $videoTitle ?: 'হোমপেজ ভিডিও' }}</span>
                    <p class="text-xs font-mono text-slate-400 truncate max-w-xl">{{ $videoUrl }}</p>
                </div>
                <span class="px-3 py-1 rounded-full bg-emerald-500/15 border border-emerald-500/30 text-emerald-400 text-[11px] font-bold shrink-0">
                    ✓ হোমপেজে সক্রিয়
                </span>
            </div>
        @else
            <div class="p-6 rounded-2xl bg-slate-950 border border-dashed border-slate-800 text-center text-xs text-slate-500">
                কোনো ভিডিও লিংক যুক্ত করা হয়নি। <a href="{{ route('admin.settings') }}" class="text-rose-400 font-bold underline">এখানে ক্লিক করে YouTube লিংক যুক্ত করুন</a>।
            </div>
        @endif
    </div>

    <!-- Recent Print Jobs Table -->
    <div class="bg-slate-900/80 rounded-3xl border border-slate-800 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-800 flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-white">সাম্প্রতিক প্রিন্ট অ্যাক্টিভিটি (Live Audit)</h3>
                <p class="text-xs text-slate-400">দেশজুড়ে হওয়া সর্বশেষ প্রিন্ট জবসমূহ</p>
            </div>
            <a href="{{ route('admin.jobs') }}" class="text-xs font-bold text-rose-400 hover:text-rose-300 hover:underline">সবগুলো দেখুন →</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-950/70 text-slate-400 font-bold border-b border-slate-800 uppercase text-[10px] tracking-wider">
                    <tr>
                        <th class="px-6 py-3.5">টোকেন নং</th>
                        <th class="px-6 py-3.5">দোকান</th>
                        <th class="px-6 py-3.5">ফাইল</th>
                        <th class="px-6 py-3.5">মোড</th>
                        <th class="px-6 py-3.5">বিল</th>
                        <th class="px-6 py-3.5">পেমেন্ট</th>
                        <th class="px-6 py-3.5">প্রিন্ট স্ট্যাটাস</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    @forelse($recentJobs as $job)
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            <td class="px-6 py-3.5 font-mono font-bold text-white">{{ $job->job_token }}</td>
                            <td class="px-6 py-3.5 font-medium text-slate-300">{{ $job->shop->name ?? 'N/A' }}</td>
                            <td class="px-6 py-3.5 text-slate-400 max-w-xs truncate">{{ $job->original_name }}</td>
                            <td class="px-6 py-3.5">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $job->color_mode === 'color' ? 'bg-pink-500/10 text-pink-400 border border-pink-500/20' : 'bg-slate-800 text-slate-300' }}">
                                    {{ $job->color_mode === 'color' ? 'Color' : 'B&W' }}
                                </span>
                            </td>
                            <td class="px-6 py-3.5 font-mono font-bold text-emerald-400">৳{{ $job->total_amount }}</td>
                            <td class="px-6 py-3.5">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $job->payment_status === 'paid' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-amber-500/10 text-amber-400 border border-amber-500/20' }}">
                                    {{ strtoupper($job->payment_status) }}
                                </span>
                            </td>
                            <td class="px-6 py-3.5">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $job->print_status === 'printed' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-blue-500/15 text-blue-400 border border-blue-500/20' }}">
                                    {{ strtoupper($job->print_status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-slate-500">কোনো প্রিন্ট জব পাওয়া যায়নি</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
