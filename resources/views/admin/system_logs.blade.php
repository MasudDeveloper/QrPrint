@extends('layouts.admin')

@section('title', 'সিস্টেম এরর ও ক্র্যাশ লগ – সুপার এডমিন')

@section('content')
<div class="space-y-6">

    <!-- Header & Action Center -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-gradient-to-r from-slate-900 via-slate-950 to-slate-900 p-6 sm:p-7 rounded-3xl text-white border border-slate-800 shadow-2xl relative overflow-hidden">
        <div class="absolute -top-24 -left-24 w-72 h-72 bg-rose-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="space-y-1.5 z-10">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-rose-500/15 text-rose-400 text-xs font-bold border border-rose-500/30">
                <span class="w-2 h-2 rounded-full bg-rose-400 animate-ping"></span>
                <span>লাইভ সিস্টেম ক্র্যাশ ও এরর মনিটরিং</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black">এরর ও ক্র্যাশ ডিটেকশন সেন্টার</h1>
            <p class="text-xs sm:text-sm text-slate-400">সাইটের যেকোনো এক্সেপশন, ৫০০ ক্র্যাশ, প্রিন্ট স্পুলার ব্যর্থতা এবং সিস্টেমে ঘটা সব ত্রুটি শনাক্ত করুন</p>
        </div>
        <div class="flex flex-wrap items-center gap-2.5 z-10">
            <!-- Test Crash Trigger -->
            <form action="{{ route('admin.logs.test_crash') }}" method="POST" onsubmit="return confirm('আপনি কি টেস্ট ক্র্যাশ রিপোর্ট ট্রিগার করতে চান?')">
                @csrf
                <button type="submit" class="px-4 py-2.5 rounded-2xl bg-amber-500/15 hover:bg-amber-500/25 border border-amber-500/30 text-amber-300 font-bold text-xs transition-all flex items-center gap-1.5 shadow-sm">
                    <span>⚡ টেস্ট ক্র্যাশ তৈরি করুন</span>
                </button>
            </form>

            <!-- Purge Resolved Logs -->
            <form action="{{ route('admin.logs.clear') }}" method="POST" onsubmit="return confirm('আপনি কি সকল সমাধানকৃত (Resolved) লগ মুছে ফেলতে চান?')">
                @csrf
                <input type="hidden" name="scope" value="resolved">
                <button type="submit" class="px-4 py-2.5 rounded-2xl bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-300 font-bold text-xs transition-all flex items-center gap-1.5">
                    <span>🧹 সমাধানকৃতগুলো মুছুন</span>
                </button>
            </form>

            <!-- Purge All Logs -->
            <form action="{{ route('admin.logs.clear') }}" method="POST" onsubmit="return confirm('সতর্কতা: এটি সম্পূর্ণ এরর লগ ডাটাবেস খালি করে দেবে! আপনি কি নিশ্চিত?')">
                @csrf
                <input type="hidden" name="scope" value="all">
                <button type="submit" class="px-3.5 py-2.5 rounded-2xl bg-rose-600/20 hover:bg-rose-600 border border-rose-600/40 text-rose-300 hover:text-white font-bold text-xs transition-all flex items-center gap-1.5">
                    <span>⚠️ সব লগ ক্লিয়ার</span>
                </button>
            </form>
        </div>
    </div>

    <!-- 4 Key Metrics Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <!-- Unresolved Errors -->
        <div class="bg-slate-900/80 p-5 sm:p-6 rounded-3xl border border-slate-800 shadow-sm flex flex-col justify-between hover:border-slate-700 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400">অমীমাংসিত এরর</span>
                <span class="w-9 h-9 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-amber-400 flex items-center justify-center text-sm font-bold">🚨</span>
            </div>
            <div class="mt-4">
                <span class="text-3xl font-black {{ $totalUnresolved > 0 ? 'text-amber-400' : 'text-emerald-400' }} font-en">{{ $totalUnresolved }}</span>
                <span class="text-[11px] text-slate-400 block font-bold mt-1">তাত্ক্ষণিক সমাধান প্রয়োজন</span>
            </div>
        </div>

        <!-- Critical Crashes -->
        <div class="bg-slate-900/80 p-5 sm:p-6 rounded-3xl border border-slate-800 shadow-sm flex flex-col justify-between hover:border-slate-700 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400">ক্রিটিক্যাল ক্র্যাশ</span>
                <span class="w-9 h-9 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-rose-400 flex items-center justify-center text-sm font-bold">💥</span>
            </div>
            <div class="mt-4">
                <span class="text-3xl font-black {{ $criticalCount > 0 ? 'text-rose-400' : 'text-slate-400' }} font-en">{{ $criticalCount }}</span>
                <span class="text-[11px] text-slate-400 block font-bold mt-1">ফ্যাটাল / 500 এক্সেপশন</span>
            </div>
        </div>

        <!-- Spooler & Agent Errors -->
        <div class="bg-slate-900/80 p-5 sm:p-6 rounded-3xl border border-slate-800 shadow-sm flex flex-col justify-between hover:border-slate-700 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400">প্রিন্টার / এজেন্ট সমস্যা</span>
                <span class="w-9 h-9 rounded-2xl bg-purple-500/10 border border-purple-500/20 text-purple-400 flex items-center justify-center text-sm font-bold">🖨️</span>
            </div>
            <div class="mt-4">
                <span class="text-3xl font-black {{ $spoolerErrorsCount > 0 ? 'text-purple-400' : 'text-slate-400' }} font-en">{{ $spoolerErrorsCount }}</span>
                <span class="text-[11px] text-slate-400 block font-bold mt-1">স্পুলার ও ফাইল মিসিং ত্রুটি</span>
            </div>
        </div>

        <!-- Today Errors -->
        <div class="bg-slate-900/80 p-5 sm:p-6 rounded-3xl border border-slate-800 shadow-sm flex flex-col justify-between hover:border-slate-700 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400">আজকের মোট এরর</span>
                <span class="w-9 h-9 rounded-2xl bg-blue-500/10 border border-blue-500/20 text-blue-400 flex items-center justify-center text-sm font-bold">📊</span>
            </div>
            <div class="mt-4">
                <span class="text-3xl font-black text-white font-en">{{ $todayErrorsCount }}</span>
                <span class="text-[11px] text-slate-400 block font-bold mt-1">আজকের সংঘটিত ঘটনা</span>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs (Database Logs vs Raw laravel.log) -->
    <div class="flex items-center gap-3 border-b border-slate-800 pb-3">
        <a href="{{ route('admin.logs', array_merge(request()->query(), ['tab' => 'database'])) }}" 
           class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition-all {{ $tab !== 'raw' ? 'bg-rose-600 text-white shadow-lg shadow-rose-600/30' : 'bg-slate-900 text-slate-400 hover:text-white hover:bg-slate-800' }}">
            📁 সেন্ট্রাল ডাটাবেস এরর লগ (Deduplicated)
        </a>
        <a href="{{ route('admin.logs', ['tab' => 'raw']) }}" 
           class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition-all {{ $tab === 'raw' ? 'bg-rose-600 text-white shadow-lg shadow-rose-600/30' : 'bg-slate-900 text-slate-400 hover:text-white hover:bg-slate-800' }}">
            📜 Laravel Raw ফাইল লগ (storage/logs)
        </a>
    </div>

    @if($tab === 'raw')
        <!-- RAW LARAVEL LOG VIEWER -->
        <div class="bg-slate-900/90 rounded-3xl border border-slate-800 shadow-xl overflow-hidden p-6 space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-800">
                <div>
                    <h3 class="text-sm font-bold text-white flex items-center gap-2">
                        <span>📜 storage/logs/laravel.log রিয়েল-টাইম কনটেন্ট</span>
                        <span class="text-[10px] font-mono px-2 py-0.5 rounded bg-slate-800 text-slate-400">সর্বশেষ ২৫০ লাইন</span>
                    </h3>
                    <p class="text-[11px] text-slate-400">সিস্টেম ফাইলের কাঁচা লগ স্ট্যাকট্রেস</p>
                </div>
                <div class="flex items-center gap-2">
                    <form action="{{ route('admin.logs.raw.clear') }}" method="POST" onsubmit="return confirm('আপনি কি নিশ্চিত যে laravel.log সম্পূর্ণ খালি করতে চান?')">
                        @csrf
                        <button type="submit" class="px-3.5 py-1.5 rounded-xl bg-rose-500/15 hover:bg-rose-500/25 border border-rose-500/30 text-rose-300 text-xs font-bold transition-all flex items-center gap-1.5">
                            <span>🧹 Raw লগ ফাইল খালি করুন</span>
                        </button>
                    </form>
                </div>
            </div>

            <div class="bg-slate-950 p-4 rounded-2xl border border-slate-800 font-mono text-[11px] text-slate-300 overflow-x-auto max-h-[600px] overflow-y-auto space-y-1 select-text">
                @forelse($rawLogs as $line)
                    @php
                        $colorClass = 'text-slate-300';
                        if (str_contains($line, '.ERROR:') || str_contains($line, '.CRITICAL:') || str_contains($line, '.EMERGENCY:')) {
                            $colorClass = 'text-rose-400 font-bold bg-rose-950/20 px-1 rounded';
                        } elseif (str_contains($line, '.WARNING:')) {
                            $colorClass = 'text-amber-300 font-semibold';
                        } elseif (str_contains($line, '.INFO:')) {
                            $colorClass = 'text-blue-300';
                        }
                    @endphp
                    <div class="{{ $colorClass }} whitespace-pre-wrap break-all">{{ $line }}</div>
                @empty
                    <div class="text-slate-500 py-8 text-center italic">কোনো লগ এন্ট্রি পাওয়া যায়নি (ফাইলটি ফাঁকা)।</div>
                @endforelse
            </div>
        </div>
    @else
        <!-- DATABASE LOGS SECTION -->

        <!-- Filters Form -->
        <div class="bg-slate-900/80 p-4 sm:p-5 rounded-3xl border border-slate-800 shadow-sm">
            <form action="{{ route('admin.logs') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                <input type="hidden" name="tab" value="database">

                <!-- Search Input -->
                <div class="lg:col-span-2">
                    <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">কী-ওয়ার্ড সার্চ</label>
                    <input type="text" name="search" value="{{ $search }}" placeholder="মেসেজ, এরর ক্লাস, URL বা ফাইল নাম দিয়ে খুঁজুন..." 
                           class="w-full px-3.5 py-2 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs placeholder-slate-600 focus:outline-none focus:border-rose-500">
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">স্ট্যাটাস</label>
                    <select name="status" class="w-full px-3 py-2 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs focus:outline-none focus:border-rose-500">
                        <option value="all" {{ $status === 'all' ? 'selected' : '' }}>সকল স্ট্যাটাস</option>
                        <option value="unresolved" {{ $status === 'unresolved' ? 'selected' : '' }}>🚨 অমীমাংসিত (Unresolved)</option>
                        <option value="resolved" {{ $status === 'resolved' ? 'selected' : '' }}>✅ সমাধানকৃত (Resolved)</option>
                    </select>
                </div>

                <!-- Severity Level Filter -->
                <div>
                    <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">সিভিয়ারিটি লেভেল</label>
                    <select name="level" class="w-full px-3 py-2 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs focus:outline-none focus:border-rose-500">
                        <option value="all" {{ $level === 'all' ? 'selected' : '' }}>সকল লেভেল</option>
                        <option value="critical" {{ $level === 'critical' ? 'selected' : '' }}>💥 Critical / Fatal</option>
                        <option value="error" {{ $level === 'error' ? 'selected' : '' }}>🛑 Error</option>
                        <option value="warning" {{ $level === 'warning' ? 'selected' : '' }}>⚠️ Warning</option>
                        <option value="info" {{ $level === 'info' ? 'selected' : '' }}>ℹ️ Info</option>
                    </select>
                </div>

                <!-- Filter & Reset Buttons -->
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 py-2 px-3 rounded-xl bg-rose-600 hover:bg-rose-500 text-white text-xs font-bold transition-all text-center">
                        ফিল্টার প্রয়োগ
                    </button>
                    @if(!empty($search) || $status !== 'unresolved' || $level !== 'all' || $type !== 'all')
                        <a href="{{ route('admin.logs') }}" class="py-2 px-3 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-bold transition-all text-center" title="রিসেট">
                            ✕
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- System Error Logs Table -->
        <div class="bg-slate-900/80 rounded-3xl border border-slate-800 shadow-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-950/70 text-slate-400 font-bold border-b border-slate-800 uppercase text-[10px] tracking-wider">
                        <tr>
                            <th class="px-5 py-4">লেভেল ও স্ট্যাটাস</th>
                            <th class="px-5 py-4">এরর ধরণ ও বিবরণ</th>
                            <th class="px-5 py-4">লোকেশন (ফাইল:লাইন)</th>
                            <th class="px-5 py-4 text-center">সংঘটন</th>
                            <th class="px-5 py-4">দোকান / ইউজার</th>
                            <th class="px-5 py-4">সর্বশেষ দেখা গেছে</th>
                            <th class="px-5 py-4 text-right">অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/80">
                        @forelse($logs as $log)
                            <tr class="hover:bg-slate-800/40 transition-colors {{ $log->status === 'resolved' ? 'opacity-70 bg-slate-950/40' : '' }}">
                                <!-- Level & Status Badge -->
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <div class="space-y-1">
                                        @if($log->level === 'critical' || $log->level === 'emergency')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase bg-rose-500/15 text-rose-400 border border-rose-500/30">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                                                CRITICAL
                                            </span>
                                        @elseif($log->level === 'error')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase bg-red-500/15 text-red-400 border border-red-500/30">
                                                ERROR
                                            </span>
                                        @elseif($log->level === 'warning')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase bg-amber-500/15 text-amber-400 border border-amber-500/30">
                                                WARNING
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase bg-blue-500/15 text-blue-400 border border-blue-500/30">
                                                INFO
                                            </span>
                                        @endif

                                        <div>
                                            @if($log->status === 'resolved')
                                                <span class="inline-block text-[9px] font-bold text-emerald-400 bg-emerald-500/10 px-2 py-0.5 rounded-md border border-emerald-500/20">
                                                    ✓ সমাধানকৃত
                                                </span>
                                            @else
                                                <span class="inline-block text-[9px] font-bold text-amber-400 bg-amber-500/10 px-2 py-0.5 rounded-md border border-amber-500/20">
                                                    ● অমীমাংসিত
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <!-- Error Type & Message Snippet -->
                                <td class="px-5 py-4 max-w-md">
                                    <div class="font-mono font-bold text-white text-[11px] truncate" title="{{ $log->error_type }}">
                                        {{ class_basename($log->error_type) }}
                                        <span class="text-[9px] text-slate-500 font-normal ml-1">({{ $log->error_type }})</span>
                                    </div>
                                    <p class="text-slate-300 text-xs font-medium mt-1 line-clamp-2" title="{{ $log->message }}">
                                        {{ $log->message }}
                                    </p>
                                    @if($log->url)
                                        <div class="mt-1 flex items-center gap-1.5 text-[10px] text-slate-400 font-mono truncate">
                                            <span class="font-bold text-slate-500 uppercase">{{ $log->method ?? 'GET' }}</span>
                                            <span class="truncate">{{ $log->url }}</span>
                                        </div>
                                    @endif
                                </td>

                                <!-- File & Line Location -->
                                <td class="px-5 py-4 max-w-xs">
                                    @if($log->file)
                                        <div class="font-mono text-[10px] text-slate-300 truncate" title="{{ $log->file }}">
                                            {{ basename($log->file) }}
                                            <span class="text-rose-400 font-bold">:{{ $log->line }}</span>
                                        </div>
                                        <span class="text-[9px] text-slate-500 truncate block font-mono" title="{{ $log->file }}">
                                            {{ Str::limit($log->file, 35) }}
                                        </span>
                                    @else
                                        <span class="text-slate-500 text-[10px]">N/A</span>
                                    @endif
                                </td>

                                <!-- Occurrences Count -->
                                <td class="px-5 py-4 text-center whitespace-nowrap">
                                    <span class="inline-block px-2.5 py-1 rounded-xl text-xs font-black font-mono {{ $log->occurrences_count > 5 ? 'bg-rose-500/20 text-rose-300 border border-rose-500/30' : 'bg-slate-800 text-slate-300' }}">
                                        {{ $log->occurrences_count }} বার
                                    </span>
                                </td>

                                <!-- Shop & User Context -->
                                <td class="px-5 py-4 whitespace-nowrap">
                                    @if($log->shop)
                                        <div class="text-slate-200 font-bold text-xs flex items-center gap-1">
                                            <span>🏪</span>
                                            <span class="truncate max-w-[120px]">{{ $log->shop->name }}</span>
                                        </div>
                                    @endif
                                    @if($log->user)
                                        <div class="text-[10px] text-slate-400">
                                            👤 {{ $log->user->name }}
                                        </div>
                                    @elseif(!$log->shop)
                                        <span class="text-[10px] text-slate-500">গ্লোবাল / গেস্ট</span>
                                    @endif
                                    @if($log->ip_address)
                                        <span class="text-[9px] text-slate-500 font-mono block">{{ $log->ip_address }}</span>
                                    @endif
                                </td>

                                <!-- Last Seen Time -->
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <span class="text-slate-300 text-xs font-medium block">
                                        {{ $log->last_seen_at ? $log->last_seen_at->diffForHumans() : $log->created_at->diffForHumans() }}
                                    </span>
                                    <span class="text-[10px] text-slate-500 font-mono">
                                        {{ $log->last_seen_at ? $log->last_seen_at->format('d M, h:i A') : $log->created_at->format('d M, h:i A') }}
                                    </span>
                                </td>

                                <!-- Actions -->
                                <td class="px-5 py-4 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <!-- Details Button (Modal) -->
                                        <button type="button" 
                                                onclick="openLogModal({{ json_encode($log) }})"
                                                class="px-2.5 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs transition-colors flex items-center gap-1"
                                                title="পূর্ণ বিবরণ ও স্ট্যাক ট্রেস দেখুন">
                                            <span>🔍 ট্রেস</span>
                                        </button>

                                        <!-- Resolve / Unresolve Button -->
                                        @if($log->status === 'unresolved')
                                            <form action="{{ route('admin.logs.resolve', $log->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="px-2.5 py-1.5 rounded-xl bg-emerald-500/15 hover:bg-emerald-500/30 border border-emerald-500/30 text-emerald-300 text-xs font-bold transition-all" title="সমাধান চিহ্নিত করুন">
                                                    ✓ সমাধান
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.logs.unresolve', $log->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="px-2.5 py-1.5 rounded-xl bg-amber-500/15 hover:bg-amber-500/30 border border-amber-500/30 text-amber-300 text-xs font-bold transition-all" title="পুনরায় ওপেন করুন">
                                                    🔄 রি-ওপেন
                                                </button>
                                            </form>
                                        @endif

                                        <!-- Delete Log -->
                                        <form action="{{ route('admin.logs.delete', $log->id) }}" method="POST" class="inline" onsubmit="return confirm('এই লগ এন্ট্রি ডিলিট করতে চান?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 rounded-xl text-slate-500 hover:text-rose-400 hover:bg-rose-500/10 transition-colors" title="মুছে ফেলুন">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                                    <div class="text-3xl mb-2">🎉</div>
                                    <div class="text-sm font-bold text-slate-300">কোনো এরর লগ পাওয়া যায়নি!</div>
                                    <div class="text-xs text-slate-500 mt-1">সিস্টেম সম্পূর্ণ সুরক্ষিত ও স্মুথভাবে চলছে।</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($logs->hasPages())
                <div class="p-4 border-t border-slate-800 bg-slate-950/40">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    @endif

</div>

<!-- DETAILED ERROR MODAL / SLIDE-OVER -->
<div id="errorDetailModal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm hidden flex items-center justify-center p-3 sm:p-6 overflow-y-auto">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl max-w-4xl w-full max-h-[92vh] flex flex-col shadow-2xl overflow-hidden animate-in fade-in zoom-in-95 duration-200">
        
        <!-- Modal Header -->
        <div class="p-5 sm:p-6 border-b border-slate-800 flex items-center justify-between bg-slate-950/80">
            <div class="flex items-center gap-3">
                <span id="modalLevelBadge" class="px-2.5 py-1 rounded-full text-xs font-black uppercase"></span>
                <div>
                    <h3 id="modalErrorType" class="text-base font-black text-white font-mono"></h3>
                    <p id="modalTime" class="text-[11px] text-slate-400 mt-0.5"></p>
                </div>
            </div>
            <button type="button" onclick="closeLogModal()" class="w-8 h-8 rounded-full bg-slate-800 text-slate-400 hover:text-white flex items-center justify-center text-lg font-bold">
                &times;
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-5 sm:p-6 overflow-y-auto space-y-5 text-xs">
            <!-- Error Message Box -->
            <div class="space-y-1.5">
                <label class="text-[10px] font-bold uppercase tracking-wider text-slate-400">এরর মেসেজ (Exception Message)</label>
                <div id="modalMessage" class="p-4 rounded-2xl bg-rose-950/30 border border-rose-500/30 text-rose-200 font-mono text-xs whitespace-pre-wrap select-text break-words"></div>
            </div>

            <!-- Context Info Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Location -->
                <div class="p-3.5 rounded-2xl bg-slate-950 border border-slate-800 space-y-1">
                    <span class="text-[10px] font-bold text-slate-500 uppercase block">কোড ফাইল ও লাইন নম্বর</span>
                    <div id="modalFile" class="font-mono text-slate-200 break-all select-text font-bold"></div>
                </div>

                <!-- Request URL & Method -->
                <div class="p-3.5 rounded-2xl bg-slate-950 border border-slate-800 space-y-1">
                    <span class="text-[10px] font-bold text-slate-500 uppercase block">HTTP রিকোয়েস্ট URL</span>
                    <div id="modalUrl" class="font-mono text-slate-200 break-all select-text"></div>
                </div>

                <!-- Client IP & Agent -->
                <div class="p-3.5 rounded-2xl bg-slate-950 border border-slate-800 space-y-1">
                    <span class="text-[10px] font-bold text-slate-500 uppercase block">ক্লায়েন্ট আইপি ও ইউজার এজেন্ট</span>
                    <div id="modalClient" class="font-mono text-slate-300 break-all select-text"></div>
                </div>

                <!-- Shop & User Context -->
                <div class="p-3.5 rounded-2xl bg-slate-950 border border-slate-800 space-y-1">
                    <span class="text-[10px] font-bold text-slate-500 uppercase block">দোকান / ইউজার ও সংঘটন সংখ্যা</span>
                    <div id="modalShop" class="text-slate-200"></div>
                </div>
            </div>

            <!-- Sanitized Request Payload (if present) -->
            <div id="modalPayloadContainer" class="space-y-1.5 hidden">
                <div class="flex items-center justify-between">
                    <label class="text-[10px] font-bold uppercase tracking-wider text-slate-400">রিকোয়েস্ট ডাটা (Sanitized Payload)</label>
                    <button type="button" onclick="copyModalContent('modalPayload')" class="text-[10px] text-rose-400 hover:underline">📋 ডাটা কপি করুন</button>
                </div>
                <pre id="modalPayload" class="p-4 rounded-2xl bg-slate-950 border border-slate-800 text-slate-300 font-mono text-[11px] overflow-x-auto max-h-48 select-text"></pre>
            </div>

            <!-- Full Stack Trace -->
            <div class="space-y-1.5">
                <div class="flex items-center justify-between">
                    <label class="text-[10px] font-bold uppercase tracking-wider text-slate-400">সম্পূর্ণ স্ট্যাক ট্রেস (Stack Trace)</label>
                    <button type="button" onclick="copyModalContent('modalStackTrace')" class="text-[10px] text-rose-400 hover:underline">📋 সম্পূর্ণ ট্রেস কপি করুন</button>
                </div>
                <pre id="modalStackTrace" class="p-4 rounded-2xl bg-slate-950 border border-slate-800 text-slate-400 font-mono text-[11px] overflow-x-auto max-h-72 select-text whitespace-pre leading-relaxed"></pre>
            </div>
        </div>

        <!-- Modal Footer Actions -->
        <div class="p-4 sm:p-5 border-t border-slate-800 bg-slate-950/80 flex items-center justify-between">
            <span id="modalHash" class="text-[10px] font-mono text-slate-500 truncate max-w-xs"></span>
            <div class="flex items-center gap-2">
                <button type="button" onclick="closeLogModal()" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs transition-colors">
                    বন্ধ করুন
                </button>
            </div>
        </div>

    </div>
</div>

<script>
    function openLogModal(log) {
        document.getElementById('modalErrorType').textContent = log.error_type;
        document.getElementById('modalMessage').textContent = log.message || '(কোনো মেসেজ নেই)';
        
        // Level Badge
        const badge = document.getElementById('modalLevelBadge');
        if (log.level === 'critical' || log.level === 'emergency') {
            badge.className = 'px-2.5 py-1 rounded-full text-xs font-black uppercase bg-rose-500/20 text-rose-300 border border-rose-500/40';
            badge.textContent = '💥 CRITICAL';
        } else if (log.level === 'error') {
            badge.className = 'px-2.5 py-1 rounded-full text-xs font-black uppercase bg-red-500/20 text-red-300 border border-red-500/40';
            badge.textContent = '🛑 ERROR';
        } else if (log.level === 'warning') {
            badge.className = 'px-2.5 py-1 rounded-full text-xs font-black uppercase bg-amber-500/20 text-amber-300 border border-amber-500/40';
            badge.textContent = '⚠️ WARNING';
        } else {
            badge.className = 'px-2.5 py-1 rounded-full text-xs font-black uppercase bg-blue-500/20 text-blue-300 border border-blue-500/40';
            badge.textContent = 'ℹ️ INFO';
        }

        // Time & count
        document.getElementById('modalTime').textContent = `মোট ঘটেছে: ${log.occurrences_count} বার • সর্বশেষ: ${log.last_seen_at || log.created_at}`;
        
        // File & Line
        document.getElementById('modalFile').textContent = log.file ? `${log.file}:${log.line || 0}` : 'N/A';
        
        // URL
        document.getElementById('modalUrl').textContent = log.url ? `[${log.method || 'GET'}] ${log.url}` : 'N/A (Console or Background)';

        // Client info
        document.getElementById('modalClient').textContent = `IP: ${log.ip_address || 'N/A'}\nUA: ${log.user_agent || 'N/A'}`;

        // Shop & User
        let shopName = log.shop ? log.shop.name : 'গ্লোবাল / অজ্ঞাত দোকান';
        let userName = log.user ? log.user.name : 'গেস্ট / অননুমোদিত';
        document.getElementById('modalShop').textContent = `🏪 দোকান: ${shopName} | 👤 ইউজার: ${userName} | স্ট্যাটাস: ${log.status}`;

        // Payload
        const payloadContainer = document.getElementById('modalPayloadContainer');
        const payloadPre = document.getElementById('modalPayload');
        if (log.payload) {
            payloadPre.textContent = log.payload;
            payloadContainer.classList.remove('hidden');
        } else {
            payloadContainer.classList.add('hidden');
        }

        // Stack Trace
        document.getElementById('modalStackTrace').textContent = log.stack_trace || '(স্ট্যাক ট্রেস সংরক্ষিত নেই)';
        document.getElementById('modalHash').textContent = `Hash: ${log.error_hash || 'N/A'}`;

        // Show modal
        document.getElementById('errorDetailModal').classList.remove('hidden');
    }

    function closeLogModal() {
        document.getElementById('errorDetailModal').classList.add('hidden');
    }

    function copyModalContent(elementId) {
        const text = document.getElementById(elementId).textContent;
        navigator.clipboard.writeText(text).then(() => {
            alert('ক্লিপবোর্ডে কপি করা হয়েছে!');
        }).catch(() => {
            alert('কপি ব্যর্থ হয়েছে। ম্যানুয়ালি কপি করুন।');
        });
    }

    // Close modal on Escape or outside click
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeLogModal();
    });
    document.getElementById('errorDetailModal').addEventListener('click', (e) => {
        if (e.target.id === 'errorDetailModal') closeLogModal();
    });
</script>
@endsection
