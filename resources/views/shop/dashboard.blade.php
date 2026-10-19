@extends('layouts.shop')

@section('title', 'শপ ড্যাশবোর্ড – ' . $shop->name)

@section('content')
<div class="space-y-8">
    
    <!-- Top Welcome Banner with Live Hardware Visualization -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 bg-gradient-to-r from-slate-900 via-slate-950 to-slate-900 border border-slate-800/80 p-6 sm:p-8 rounded-3xl text-white shadow-2xl relative overflow-hidden">
        
        <!-- Background Ambient Glow -->
        <div class="absolute -top-24 -left-24 w-72 h-72 bg-rose-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-72 h-72 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>

        @php
            $isAgentOnline = $shop && $shop->last_agent_heartbeat_at && $shop->last_agent_heartbeat_at->gte(now()->subMinutes(3));
        @endphp

        <!-- Left: Shop Info -->
        <div class="space-y-2.5 z-10">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full {{ $isAgentOnline ? 'bg-emerald-500/15 text-emerald-400 border-emerald-500/30' : 'bg-amber-500/15 text-amber-400 border-amber-500/30' }} text-xs font-bold border">
                <span class="w-2 h-2 rounded-full {{ $isAgentOnline ? 'bg-emerald-400 animate-ping' : 'bg-amber-400' }}"></span>
                <span>{{ $isAgentOnline ? 'লাইভ অটো-প্রিন্ট ক্লাউড লিংক সক্রিয়' : 'উইন্ডোজ এজেন্ট ডিসকানেক্টেড (অফলাইন)' }}</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white flex items-center gap-2">
                <span>{{ $shop->name }}</span>
            </h1>
            <p class="text-xs sm:text-sm text-slate-400 max-w-xl">কাস্টমার সেলফ-সার্ভিস প্রিন্ট কিউ, ডিজিটাল পেমেন্ট ও লাইভ আয় বিবরণী ড্যাশবোর্ড</p>
            
            <div class="flex flex-wrap items-center gap-3 pt-2">
                <a href="{{ route('shop.qr_poster') }}" class="px-4 py-2.5 rounded-2xl bg-gradient-to-r from-rose-500 to-rose-600 hover:from-rose-600 hover:to-rose-700 text-white font-bold text-xs shadow-lg shadow-rose-500/25 transition-all flex items-center gap-2">
                    <span>🖨️ কাউন্টার QR পোস্টার</span>
                </a>
                <a href="{{ route('shop.agent_setup') }}" class="px-4 py-2.5 rounded-2xl bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 font-bold text-xs transition-all flex items-center gap-1.5">
                    <span>💻 উইন্ডোজ এজেন্ট (.EXE)</span>
                </a>
                <a href="{{ route('customer.upload', $shop->slug) }}" target="_blank" class="px-4 py-2.5 rounded-2xl bg-emerald-500/15 hover:bg-emerald-500/25 text-emerald-300 font-bold text-xs border border-emerald-500/30 transition-all flex items-center gap-1.5">
                    <span>কাস্টমার স্ক্রিন দেখুন</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                </a>
            </div>
        </div>

        <!-- Right: Animated Compact Hardware Visualizer -->
        <div class="w-64 bg-slate-900/90 rounded-2xl p-4 border border-slate-800 shadow-xl flex flex-col items-center relative overflow-hidden shrink-0 z-10">
            <div class="flex items-center justify-between w-full text-[10px] font-mono text-slate-400 pb-2 border-b border-slate-800">
                <span class="{{ $isAgentOnline ? 'text-emerald-400' : 'text-amber-400' }} flex items-center gap-1.5 font-bold">
                    <span class="w-1.5 h-1.5 rounded-full {{ $isAgentOnline ? 'bg-emerald-400 animate-pulse' : 'bg-amber-400' }}"></span>
                    {{ $isAgentOnline ? 'RELAY ACTIVE' : 'RELAY STANDBY' }}
                </span>
                <span class="text-slate-500 font-bold">PORT #{{ $shop->id }}</span>
            </div>
            <!-- Printer Body -->
            <div class="w-44 bg-slate-950 rounded-xl p-3 border border-slate-800 text-center relative mt-2 shadow-inner">
                <div class="w-24 h-1.5 bg-slate-800 rounded mx-auto mb-2"></div>
                <!-- Continuous spooling sheet -->
                <div class="w-32 h-7 {{ $isAgentOnline ? 'bg-white text-slate-900 animate-pulse' : 'bg-slate-800 text-slate-400' }} rounded-md shadow-md text-[9px] font-mono font-bold flex items-center justify-center mx-auto">
                    {{ $isAgentOnline ? '📄 PrintSheba Spool' : '⏳ Agent Waiting' }}
                </div>
            </div>
            <div class="text-[10px] {{ $isAgentOnline ? 'text-slate-500' : 'text-amber-400 font-semibold' }} mt-2 font-mono">
                {{ $isAgentOnline ? 'Status: Ready for Prints' : 'Status: Windows Agent Offline' }}
            </div>
        </div>

    </div>

    <!-- 3 Metrics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        
        <!-- Today's Earnings -->
        <div class="bg-slate-900/80 p-6 rounded-3xl border border-slate-800 shadow-sm flex flex-col justify-between hover:border-slate-700 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400">আজকের মোট আয় (Today)</span>
                <span class="w-9 h-9 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center font-bold text-sm">৳</span>
            </div>
            <div class="mt-4">
                <span id="metricTodayEarnings" class="text-3xl font-black text-emerald-400 font-en">৳ {{ number_format($todayEarnings, 2) }}</span>
                <span class="text-[11px] text-slate-400 block font-bold mt-1">১০০% আপনার নিজস্ব বিকাশ/নগদে</span>
            </div>
        </div>

        <!-- Today's Prints -->
        <div class="bg-slate-900/80 p-6 rounded-3xl border border-slate-800 shadow-sm flex flex-col justify-between hover:border-slate-700 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400">আজকের প্রিন্ট টাস্ক</span>
                <span class="w-9 h-9 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-rose-400 flex items-center justify-center font-bold text-sm">📄</span>
            </div>
            <div class="mt-4">
                <span id="metricTodayPrints" class="text-3xl font-black text-white font-en">{{ $todayPrintsCount }} টি</span>
                <span class="text-[11px] text-slate-400 block font-bold mt-1">স্বয়ংক্রিয় প্রিন্ট সম্পন্ন</span>
            </div>
        </div>

        <!-- Connected Printers -->
        <div class="bg-slate-900/80 p-6 rounded-3xl border border-slate-800 shadow-sm flex flex-col justify-between hover:border-slate-700 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400">কানেক্টেড প্রিন্টার</span>
                <span class="w-9 h-9 rounded-2xl bg-purple-500/10 border border-purple-500/20 text-purple-400 flex items-center justify-center font-bold text-sm">🖨️</span>
            </div>
            <div class="mt-4">
                <span class="text-3xl font-black text-white font-en">{{ $printers->count() }} টি</span>
                <a href="{{ route('shop.printers') }}" class="text-[11px] text-rose-400 hover:text-rose-300 hover:underline block font-bold mt-1">প্রিন্টার লিস্ট ও সেটিংস →</a>
            </div>
        </div>

    </div>

    <!-- Live Pending Queue -->
    <div class="bg-slate-900/80 rounded-3xl border border-slate-800 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-800 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-rose-500 animate-pulse"></span>
                <h3 class="text-base font-bold text-white">লাইভ প্রিন্ট কিউ (Pending / Printing Queue)</h3>
            </div>
            <span id="pendingJobsBadge" class="text-xs font-bold px-3 py-1 rounded-full bg-slate-800 text-slate-300 border border-slate-700">
                {{ $pendingJobs->count() }} টি পেইন্ডিং
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-950/70 text-slate-400 font-bold border-b border-slate-800 uppercase text-[10px] tracking-wider">
                    <tr>
                        <th class="px-6 py-4">টোকেন</th>
                        <th class="px-6 py-4">কাস্টমার</th>
                        <th class="px-6 py-4">ডকুমেন্ট</th>
                        <th class="px-6 py-4">কালার মোড</th>
                        <th class="px-6 py-4">বিল</th>
                        <th class="px-6 py-4">পেমেন্ট</th>
                        <th class="px-6 py-4">স্ট্যাটাস</th>
                        <th class="px-6 py-4 text-right">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody id="pendingJobsTbody" class="divide-y divide-slate-800/80">
                    @forelse($pendingJobs as $job)
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            <td class="px-6 py-4 font-mono font-bold text-white">{{ $job->job_token }}</td>
                            <td class="px-6 py-4 font-medium text-slate-300">
                                <div>{{ $job->customer_name }}</div>
                                @if($job->customer_phone)
                                    <span class="text-[10px] text-slate-500 font-mono">{{ $job->customer_phone }}</span>
                                @endif
                                @if($job->pickup_reference)
                                    <div class="mt-1">
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-amber-500/20 text-amber-300 font-bold text-[10px] border border-amber-500/30">
                                            🏷️ খাম: {{ $job->pickup_reference }}
                                        </span>
                                    </div>
                                @endif
                                @if($job->is_office_job)
                                    <div class="mt-1">
                                        <span class="inline-flex items-center gap-1 px-2 py-0.2 rounded-md bg-indigo-500/20 text-indigo-300 font-bold text-[9px] border border-indigo-500/30">
                                            🏢 অফিস
                                        </span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-slate-400 max-w-xs truncate">{{ $job->original_name }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $job->color_mode === 'color' ? 'bg-pink-500/10 text-pink-400 border border-pink-500/20' : 'bg-slate-800 text-slate-300' }}">
                                    {{ $job->color_mode === 'color' ? 'কালার' : 'সাদাকালো' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-mono font-bold text-emerald-400">
                                @if($job->is_office_job)
                                    <span class="text-indigo-400 text-xs">ফ্রি (অফিস)</span>
                                @else
                                    ৳{{ $job->total_amount }}
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $job->payment_status === 'paid' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-amber-500/10 text-amber-400 border border-amber-500/20' }}">
                                    {{ $job->payment_status === 'paid' ? 'পরিশোধিত' : 'ক্যাশ/পেন্ডিং' }}
                                </span>
                                @if($job->payment_trx_id)
                                    <span class="text-[9px] text-slate-500 font-mono block mt-0.5">{{ $job->payment_trx_id }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $job->print_status === 'spooling' ? 'bg-rose-500/20 text-rose-400 animate-pulse border border-rose-500/30' : 'bg-blue-500/15 text-blue-400 border border-blue-500/20' }}">
                                    {{ $job->print_status === 'spooling' ? 'স্পুল হচ্ছে...' : 'কিউতে আছে' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('shop.jobs.slip', $job) }}" target="_blank" class="px-2.5 py-1.5 rounded-xl bg-amber-500/10 hover:bg-amber-500/20 text-amber-300 border border-amber-500/30 font-bold text-xs transition-colors flex items-center gap-1" title="খামের স্লিপ প্রিন্ট করুন">
                                        <span>🏷️ স্লিপ</span>
                                    </a>

                                    @if($job->payment_status !== 'paid')
                                        <form action="{{ route('shop.jobs.approve', $job) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white font-bold text-xs shadow-md shadow-emerald-500/20 transition-all cursor-pointer flex items-center gap-1">
                                                <span>✓</span>
                                                <span>ক্যাশ পেলাম / প্রিন্ট</span>
                                            </button>
                                        </form>

                                        <button type="button" onclick="openCancelModal({{ $job->id }}, '{{ $job->job_token }}', '{{ addslashes($job->original_name) }}', '{{ route('shop.jobs.cancel', $job) }}')" class="px-2.5 py-1.5 rounded-xl bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/30 text-xs font-bold transition-all flex items-center gap-1 cursor-pointer" title="অর্ডার বাতিল করুন">
                                            <span>✕</span>
                                            <span>বাতিল</span>
                                        </button>
                                    @else
                                        <form action="{{ route('shop.jobs.reprint', $job) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 font-bold text-xs transition-colors cursor-pointer">
                                                🔄 ফোর্স প্রিন্ট
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="pendingJobsEmptyRow">
                            <td colspan="8" class="px-6 py-8 text-center text-slate-500">
                                বর্তমানে কোনো প্রিন্ট কিউতে নেই। কাস্টমার QR স্ক্যান করলেই এখানে স্বয়ংক্রিয়ভাবে ভেসে উঠবে।
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Completed Prints -->
    <div class="bg-slate-900/80 rounded-3xl border border-slate-800 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-800 flex items-center justify-between">
            <h3 class="text-base font-bold text-white">সর্বশেষ সম্পন্ন হওয়া প্রিন্ট (Completed Prints)</h3>
            <span class="text-xs text-slate-400">কাস্টমার চাইলে এখান থেকে পুনরায় কপি বের করা যাবে</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-950/70 text-slate-400 font-bold border-b border-slate-800 uppercase text-[10px] tracking-wider">
                    <tr>
                        <th class="px-6 py-4">টোকেন</th>
                        <th class="px-6 py-4">ফাইল</th>
                        <th class="px-6 py-4">পেজ ও সাইজ</th>
                        <th class="px-6 py-4">টাকা</th>
                        <th class="px-6 py-4">পেমেন্ট মেথড</th>
                        <th class="px-6 py-4">প্রিন্টের সময়</th>
                        <th class="px-6 py-4 text-right">রিপ্রিন্ট</th>
                    </tr>
                </thead>
                <tbody id="completedJobsTbody" class="divide-y divide-slate-800/80">
                    @forelse($completedJobs as $job)
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            <td class="px-6 py-4 font-mono font-bold text-white">
                                <div>{{ $job->job_token }}</div>
                                @if($job->pickup_reference)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-amber-500/20 text-amber-300 font-bold text-[10px] border border-amber-500/30 mt-1">
                                        🏷️ খাম: {{ $job->pickup_reference }}
                                    </span>
                                @endif
                                @if($job->is_office_job)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.2 rounded-md bg-indigo-500/20 text-indigo-300 font-bold text-[9px] border border-indigo-500/30 mt-1">
                                        🏢 অফিস
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-slate-400 max-w-xs truncate">{{ $job->original_name }}</td>
                            <td class="px-6 py-4 font-bold text-slate-200">{{ $job->total_pages }} পেজ ({{ $job->paper_size }})</td>
                            <td class="px-6 py-4 font-mono font-bold text-emerald-400">
                                @if($job->is_office_job)
                                    <span class="text-indigo-400 text-xs">ফ্রি (অফিস)</span>
                                @else
                                    ৳{{ $job->total_amount }}
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $job->payment_method === 'bkash' ? 'bg-pink-500/10 text-pink-400 border border-pink-500/20' : ($job->payment_method === 'nagad' ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20' : 'bg-slate-800 text-slate-300') }}">
                                    {{ $job->payment_method }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-500 text-[11px]">
                                {{ $job->printed_at ? $job->printed_at->format('h:i A') : $job->created_at->format('h:i A') }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('shop.jobs.slip', $job) }}" target="_blank" class="px-2.5 py-1.5 rounded-xl bg-amber-500/10 hover:bg-amber-500/20 text-amber-300 border border-amber-500/30 font-bold text-xs transition-colors flex items-center gap-1" title="খামের স্লিপ প্রিন্ট করুন">
                                        <span>🏷️ স্লিপ</span>
                                    </a>
                                    <form action="{{ route('shop.jobs.reprint', $job) }}" method="POST" class="inline" onsubmit="return confirm('আপনি কি এই ডকুমেন্টটি পুনরায় প্রিন্ট করতে চান?')">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 rounded-xl bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/30 font-bold text-xs transition-colors cursor-pointer flex items-center gap-1">
                                            <span>🖨️</span>
                                            <span>রি-প্রিন্ট</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="completedJobsEmptyRow">
                            <td colspan="7" class="px-6 py-8 text-center text-slate-500">এখনো কোনো প্রিন্ট সম্পন্ন হয়নি</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Cancelled Prints -->
    @if(isset($cancelledJobs) && $cancelledJobs->count() > 0)
    <div class="bg-slate-900/80 rounded-3xl border border-rose-500/20 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-800 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                <h3 class="text-base font-bold text-white">বাতিলকৃত অর্ডারসমূহ (Cancelled Orders)</h3>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-500/10 text-rose-400 border border-rose-500/20">
                    {{ $cancelledJobs->count() }} টি
                </span>
            </div>
            <span class="text-xs text-slate-400">কাস্টমার না এলে বা ভুল অর্ডারে বাতিল হওয়া লিস্ট</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-950/70 text-slate-400 font-bold border-b border-slate-800 uppercase text-[10px] tracking-wider">
                    <tr>
                        <th class="px-6 py-4">টোকেন</th>
                        <th class="px-6 py-4">গ্রাহক</th>
                        <th class="px-6 py-4">ফাইল</th>
                        <th class="px-6 py-4">টাকা</th>
                        <th class="px-6 py-4">বাতিলের কারণ</th>
                        <th class="px-6 py-4 text-right">বাতিলের সময়</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    @foreach($cancelledJobs as $cJob)
                        <tr class="hover:bg-slate-800/40 transition-colors opacity-75 hover:opacity-100">
                            <td class="px-6 py-4 font-mono font-bold text-white">
                                <div>{{ $cJob->job_token }}</div>
                                @if($cJob->pickup_reference)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-amber-500/20 text-amber-300 font-bold text-[10px] border border-amber-500/30 mt-1">
                                        🏷️ খাম: {{ $cJob->pickup_reference }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-medium text-slate-300">
                                <div>{{ $cJob->customer_name }}</div>
                                @if($cJob->customer_phone)
                                    <span class="text-[10px] text-slate-500 font-mono">{{ $cJob->customer_phone }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-slate-400 max-w-xs truncate">{{ $cJob->original_name }}</td>
                            <td class="px-6 py-4 font-mono font-bold text-slate-400">৳{{ $cJob->total_amount }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-rose-500/10 text-rose-300 border border-rose-500/20 inline-block">
                                    {{ $cJob->cancel_reason ?: 'কাস্টমার দোকানে আসেননি / ক্যাশ দেননি' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right text-slate-500 font-mono text-[11px]">
                                {{ $cJob->cancelled_at ? $cJob->cancelled_at->format('h:i A, d M') : $cJob->updated_at->format('h:i A, d M') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Live bKash/Nagad Payments & SMS Listener Log -->
    <div class="bg-slate-900/80 rounded-3xl border border-slate-800 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-800 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                <h3 class="text-base font-bold text-white">📱 লাইভ বিকাশ ও নগদ পেমেন্ট লগ (SMS Received)</h3>
            </div>
            <span class="text-xs text-slate-400">অ্যান্ড্রয়েড লিসেনার থেকে রিসিভ হওয়া সকল ট্রানজেকশন</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-950/70 text-slate-400 font-bold border-b border-slate-800 uppercase text-[10px] tracking-wider">
                    <tr>
                        <th class="px-6 py-4">পেমেন্ট মেথড</th>
                        <th class="px-6 py-4">টাকার পরিমাণ</th>
                        <th class="px-6 py-4">ট্রানজেকশন আইডি (TrxID)</th>
                        <th class="px-6 py-4">প্রেরকের নম্বর (Sender)</th>
                        <th class="px-6 py-4">স্ট্যাটাস</th>
                        <th class="px-6 py-4 text-right">সময়</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    @forelse($recentTransactions as $tx)
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-lg text-xs font-bold uppercase {{ $tx->payment_method === 'bkash' ? 'bg-pink-500/10 text-pink-400 border border-pink-500/20' : 'bg-amber-500/10 text-amber-400 border border-amber-500/20' }}">
                                    {{ $tx->payment_method }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-en font-black text-emerald-400 text-sm">৳ {{ number_format($tx->amount, 2) }}</td>
                            <td class="px-6 py-4 font-mono font-bold text-white select-all">{{ $tx->trx_id }}</td>
                            <td class="px-6 py-4 font-mono text-slate-300">{{ $tx->sender_number ?: 'N/A' }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $tx->print_job_id ? 'bg-emerald-500/20 text-emerald-300' : 'bg-slate-800 text-slate-300' }}">
                                    {{ $tx->print_job_id ? '✓ প্রিন্টে ব্যবহৃত' : 'স্বতন্ত্র পেমেন্ট' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right text-slate-400 font-mono text-[11px]">
                                {{ $tx->created_at->format('h:i:s A, d M') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-slate-500">এখনো কোনো বিকাশ বা নগদ এসএমএস পেমেন্ট রিসিভ হয়নি</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Cancel Job Modal -->
    <div id="cancelJobModal" class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-sm hidden flex items-center justify-center p-4">
        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 w-full max-w-md shadow-2xl space-y-4">
            <div class="flex items-start justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-rose-400 flex items-center justify-center text-xl font-bold">
                        ✕
                    </div>
                    <div>
                        <h3 class="text-base font-black text-white">প্রিন্ট অর্ডার বাতিল করুন</h3>
                        <span class="text-xs text-slate-400 font-mono" id="cancelModalToken">#PS-XXXXXXXX</span>
                    </div>
                </div>
                <button type="button" onclick="closeCancelModal()" class="text-slate-400 hover:text-white p-1 rounded-lg">✕</button>
            </div>

            <div class="p-3 rounded-2xl bg-slate-950/60 border border-slate-800/80 text-xs text-slate-300">
                <span class="text-slate-500 block text-[10px] uppercase font-bold">ফাইল নাম:</span>
                <span class="font-medium text-white truncate block" id="cancelModalFileName">Document.pdf</span>
            </div>

            <form id="cancelJobForm" method="POST" onsubmit="submitCancelJob(event)" class="space-y-3">
                @csrf
                <label class="block text-xs font-bold text-slate-300">বাতিলের কারণ নির্বাচন করুন (শর্টকাট):</label>
                
                <div class="space-y-2">
                    <label class="flex items-center gap-2.5 p-3 rounded-xl border border-slate-800 bg-slate-950/40 hover:bg-slate-800/50 cursor-pointer has-[:checked]:border-rose-500/50 has-[:checked]:bg-rose-500/10 transition-all">
                        <input type="radio" name="cancel_reason" value="কাস্টমার দোকানে আসেননি / ক্যাশ দেননি" checked class="accent-rose-500" onchange="toggleCustomReason(false)">
                        <div class="text-xs">
                            <span class="font-bold text-white block">🏃‍♂️ কাস্টমার দোকানে আসেননি / ক্যাশ দেননি</span>
                            <span class="text-[10px] text-slate-400">ক্যাশ সিলেক্ট করে কাউন্টারে আসেনি বা কনফার্ম করেনি</span>
                        </div>
                    </label>

                    <label class="flex items-center gap-2.5 p-3 rounded-xl border border-slate-800 bg-slate-950/40 hover:bg-slate-800/50 cursor-pointer has-[:checked]:border-rose-500/50 has-[:checked]:bg-rose-500/10 transition-all">
                        <input type="radio" name="cancel_reason" value="কাস্টমারের অনুরোধে বাতিল" class="accent-rose-500" onchange="toggleCustomReason(false)">
                        <div class="text-xs">
                            <span class="font-bold text-white block">👤 কাস্টমারের অনুরোধে বাতিল</span>
                            <span class="text-[10px] text-slate-400">গ্রাহক নিজে অর্ডার বাতিল করতে অনুরোধ করেছেন</span>
                        </div>
                    </label>

                    <label class="flex items-center gap-2.5 p-3 rounded-xl border border-slate-800 bg-slate-950/40 hover:bg-slate-800/50 cursor-pointer has-[:checked]:border-rose-500/50 has-[:checked]:bg-rose-500/10 transition-all">
                        <input type="radio" name="cancel_reason" value="ভুল বা ডুপ্লিকেট অর্ডার" class="accent-rose-500" onchange="toggleCustomReason(false)">
                        <div class="text-xs">
                            <span class="font-bold text-white block">📄 ভুল বা ডুপ্লিকেট অর্ডার</span>
                            <span class="text-[10px] text-slate-400">একই ফাইল ভুলবশত একাধিকবার পাঠানো হয়েছে</span>
                        </div>
                    </label>

                    <label class="flex items-center gap-2.5 p-3 rounded-xl border border-slate-800 bg-slate-950/40 hover:bg-slate-800/50 cursor-pointer has-[:checked]:border-rose-500/50 has-[:checked]:bg-rose-500/10 transition-all">
                        <input type="radio" name="cancel_reason" value="অস্পষ্ট বা ত্রুটিপূর্ণ ফাইল" class="accent-rose-500" onchange="toggleCustomReason(false)">
                        <div class="text-xs">
                            <span class="font-bold text-white block">⚠️ অস্পষ্ট বা ত্রুটিপূর্ণ ফাইল</span>
                            <span class="text-[10px] text-slate-400">ছবি বোঝা যাচ্ছে না বা ফাইল পড়া সম্ভব হচ্ছে না</span>
                        </div>
                    </label>

                    <label class="flex items-center gap-2.5 p-3 rounded-xl border border-slate-800 bg-slate-950/40 hover:bg-slate-800/50 cursor-pointer has-[:checked]:border-rose-500/50 has-[:checked]:bg-rose-500/10 transition-all">
                        <input type="radio" name="cancel_reason" value="other" class="accent-rose-500" onchange="toggleCustomReason(true)">
                        <div class="text-xs">
                            <span class="font-bold text-white block">✏️ অন্যান্য কারণ</span>
                            <span class="text-[10px] text-slate-400">নিজে নির্দিষ্ট কারণ লিখে দিতে পারেন</span>
                        </div>
                    </label>
                </div>

                <div id="customReasonContainer" class="hidden pt-1">
                    <input type="text" name="custom_reason" id="customReasonInput" placeholder="বাতিলের কারণ লিখুন..." class="w-full px-3 py-2 rounded-xl bg-slate-950 border border-slate-700 text-xs text-white focus:outline-none focus:border-rose-500">
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-800">
                    <button type="button" onclick="closeCancelModal()" class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-bold transition-all cursor-pointer">
                        না, ফিরে যান
                    </button>
                    <button type="submit" id="cancelConfirmBtn" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-rose-600 to-rose-700 hover:from-rose-500 hover:to-rose-600 text-white text-xs font-bold shadow-lg shadow-rose-600/30 transition-all cursor-pointer flex items-center gap-1.5">
                        <span>🗑️ হ্যাঁ, নিশ্চিত বাতিল করুন</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const csrfToken = '{{ csrf_token() }}';

    function escapeHtml(str) {
        if (!str) return '';
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }

    function renderPendingRows(jobs) {
        const tbody = document.getElementById('pendingJobsTbody');
        if (!tbody) return;

        if (!jobs || jobs.length === 0) {
            tbody.innerHTML = `
                <tr id="pendingJobsEmptyRow">
                    <td colspan="8" class="px-6 py-8 text-center text-slate-500">
                        বর্তমানে কোনো প্রিন্ট কিউতে নেই। কাস্টমার QR স্ক্যান করলেই এখানে স্বয়ংক্রিয়ভাবে ভেসে উঠবে।
                    </td>
                </tr>
            `;
            return;
        }

        let html = '';
        jobs.forEach(job => {
            const isSpooling = job.print_status === 'spooling';
            const isPaid = job.payment_status === 'paid';
            const isColor = job.color_mode === 'color';

            const phoneHtml = job.customer_phone ? `<span class="text-[10px] text-slate-500 font-mono">${escapeHtml(job.customer_phone)}</span>` : '';
            const trxHtml = job.payment_trx_id ? `<span class="text-[9px] text-slate-500 font-mono block mt-0.5">${escapeHtml(job.payment_trx_id)}</span>` : '';

            let actionHtml = `
                <div class="flex items-center justify-end gap-1.5">
            `;
            if (job.slip_url) {
                actionHtml += `
                    <a href="${job.slip_url}" target="_blank" class="px-2.5 py-1.5 rounded-xl bg-amber-500/10 hover:bg-amber-500/20 text-amber-300 border border-amber-500/30 font-bold text-xs transition-colors flex items-center gap-1" title="খামের স্লিপ প্রিন্ট করুন">
                        <span>🏷️ স্লিপ</span>
                    </a>
                `;
            }
            if (!isPaid) {
                actionHtml += `
                    <form action="${job.approve_url}" method="POST" class="inline">
                        <input type="hidden" name="_token" value="${csrfToken}">
                        <button type="submit" class="px-3 py-1.5 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white font-bold text-xs shadow-md shadow-emerald-500/20 transition-all cursor-pointer flex items-center gap-1">
                            <span>✓</span>
                            <span>ক্যাশ পেলাম / প্রিন্ট</span>
                        </button>
                    </form>
                    <button type="button" onclick="openCancelModal(${job.id}, '${escapeHtml(job.job_token)}', '${escapeHtml(job.original_name)}', '${job.cancel_url}')" class="px-2.5 py-1.5 rounded-xl bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/30 text-xs font-bold transition-all flex items-center gap-1 cursor-pointer" title="অর্ডার বাতিল করুন">
                        <span>✕</span>
                        <span>বাতিল</span>
                    </button>
                `;
            } else {
                actionHtml += `
                    <form action="${job.reprint_url}" method="POST" class="inline">
                        <input type="hidden" name="_token" value="${csrfToken}">
                        <button type="submit" class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 font-bold text-xs transition-colors cursor-pointer">
                            🔄 ফোর্স প্রিন্ট
                        </button>
                    </form>
                `;
            }
            actionHtml += `</div>`;

            html += `
                <tr class="hover:bg-slate-800/40 transition-colors">
                    <td class="px-6 py-4 font-mono font-bold text-white">${escapeHtml(job.job_token)}</td>
                    <td class="px-6 py-4 font-medium text-slate-300">
                        <div>${escapeHtml(job.customer_name || 'কাস্টমার')}</div>
                        ${phoneHtml}
                    </td>
                    <td class="px-6 py-4 text-slate-400 max-w-xs truncate">${escapeHtml(job.original_name)}</td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold ${isColor ? 'bg-pink-500/10 text-pink-400 border border-pink-500/20' : 'bg-slate-800 text-slate-300'}">
                            ${isColor ? 'কালার' : 'সাদাকালো'}
                        </span>
                    </td>
                    <td class="px-6 py-4 font-mono font-bold text-emerald-400">৳${job.total_amount}</td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold ${isPaid ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-amber-500/10 text-amber-400 border border-amber-500/20'}">
                            ${isPaid ? 'পরিশোধিত' : 'ক্যাশ/পেন্ডিং'}
                        </span>
                        ${trxHtml}
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold ${isSpooling ? 'bg-rose-500/20 text-rose-400 animate-pulse border border-rose-500/30' : 'bg-blue-500/15 text-blue-400 border border-blue-500/20'}">
                            ${isSpooling ? 'স্পুল হচ্ছে...' : 'কিউতে আছে'}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        ${actionHtml}
                    </td>
                </tr>
            `;
        });

        tbody.innerHTML = html;
    }

    function renderCompletedRows(jobs) {
        const tbody = document.getElementById('completedJobsTbody');
        if (!tbody) return;

        if (!jobs || jobs.length === 0) {
            tbody.innerHTML = `
                <tr id="completedJobsEmptyRow">
                    <td colspan="7" class="px-6 py-8 text-center text-slate-500">এখনো কোনো প্রিন্ট সম্পন্ন হয়নি</td>
                </tr>
            `;
            return;
        }

        let html = '';
        jobs.forEach(job => {
            const method = (job.payment_method || 'cash').toLowerCase();
            let methodClass = 'bg-slate-800 text-slate-300';
            if (method === 'bkash') methodClass = 'bg-pink-500/10 text-pink-400 border border-pink-500/20';
            else if (method === 'nagad') methodClass = 'bg-amber-500/10 text-amber-400 border border-amber-500/20';

            html += `
                <tr class="hover:bg-slate-800/40 transition-colors">
                    <td class="px-6 py-4 font-mono font-bold text-white">${escapeHtml(job.job_token)}</td>
                    <td class="px-6 py-4 text-slate-400 max-w-xs truncate">${escapeHtml(job.original_name)}</td>
                    <td class="px-6 py-4 font-bold text-slate-200">${job.total_pages} পেজ (${escapeHtml(job.paper_size)})</td>
                    <td class="px-6 py-4 font-mono font-bold text-emerald-400">৳${job.total_amount}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase ${methodClass}">
                            ${escapeHtml(job.payment_method || 'CASH')}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-slate-500 text-[11px]">
                        ${escapeHtml(job.time_str)}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <form action="${job.reprint_url}" method="POST" class="inline" onsubmit="return confirm('আপনি কি এই ডকুমেন্টটি পুনরায় প্রিন্ট করতে চান?')">
                            <input type="hidden" name="_token" value="${csrfToken}">
                            <button type="submit" class="px-3 py-1.5 rounded-xl bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/30 font-bold text-xs transition-colors cursor-pointer flex items-center gap-1 ml-auto">
                                <span>🖨️</span>
                                <span>রি-প্রিন্ট করুন</span>
                            </button>
                        </form>
                    </td>
                </tr>
            `;
        });

        tbody.innerHTML = html;
    }

    function refreshShopDashboardLive() {
        fetch('{{ route("shop.live_status") }}')
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    // 1. Update Metrics Cards
                    const earningsEl = document.getElementById('metricTodayEarnings');
                    const printsEl = document.getElementById('metricTodayPrints');
                    const badgeEl = document.getElementById('pendingJobsBadge');

                    if (earningsEl) earningsEl.textContent = '৳ ' + data.today_earnings;
                    if (printsEl) printsEl.textContent = data.today_prints_count + ' টি';
                    if (badgeEl) badgeEl.textContent = data.pending_count + ' টি পেইন্ডিং';

                    // 2. Update Queue & Completed tables
                    renderPendingRows(data.pending_jobs);
                    renderCompletedRows(data.completed_jobs);
                }
            })
            .catch(() => {});
    }

    // Live Auto-Refresh every 3 seconds (Zero manual browser refresh needed)
    setInterval(refreshShopDashboardLive, 3000);

    // Cancel Job Modal Functions
    let currentCancelActionUrl = '';

    function openCancelModal(jobId, jobToken, fileName, cancelUrl) {
        currentCancelActionUrl = cancelUrl;
        document.getElementById('cancelJobForm').action = cancelUrl;
        document.getElementById('cancelModalToken').innerText = '#' + jobToken;
        document.getElementById('cancelModalFileName').innerText = fileName;
        
        // Reset radio to default
        const defaultRadio = document.querySelector('input[name="cancel_reason"][value="কাস্টমার দোকানে আসেননি / ক্যাশ দেননি"]');
        if (defaultRadio) defaultRadio.checked = true;
        toggleCustomReason(false);
        document.getElementById('customReasonInput').value = '';

        document.getElementById('cancelJobModal').classList.remove('hidden');
    }

    function closeCancelModal() {
        document.getElementById('cancelJobModal').classList.add('hidden');
    }

    function toggleCustomReason(isOther) {
        const container = document.getElementById('customReasonContainer');
        if (isOther) {
            container.classList.remove('hidden');
            document.getElementById('customReasonInput').focus();
        } else {
            container.classList.add('hidden');
        }
    }

    async function submitCancelJob(event) {
        event.preventDefault();
        const form = document.getElementById('cancelJobForm');
        const formData = new FormData(form);
        const confirmBtn = document.getElementById('cancelConfirmBtn');

        confirmBtn.disabled = true;
        confirmBtn.innerHTML = '<span>বাতিল করা হচ্ছে...</span>';

        try {
            const response = await fetch(currentCancelActionUrl, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            const result = await response.json();
            if (response.ok && result.success) {
                closeCancelModal();
                if (window.Swal) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: result.message,
                        showConfirmButton: false,
                        timer: 2500
                    });
                }
                refreshShopDashboardLive();
            } else {
                alert(result.message || 'অর্ডার বাতিল করতে সমস্যা হয়েছে।');
            }
        } catch (err) {
            console.error(err);
            form.submit(); // fallback to normal POST
        } finally {
            confirmBtn.disabled = false;
            confirmBtn.innerHTML = '<span>🗑️ হ্যাঁ, নিশ্চিত বাতিল করুন</span>';
        }
    }
</script>
@endsection
