@extends('layouts.shop')

@section('title', 'শপ ড্যাশবোর্ড – ' . $shop->name)

@section('content')
<div class="space-y-8">
    
    <!-- Top Welcome Banner with Live Hardware Visualization -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 bg-gradient-to-r from-slate-900 via-slate-950 to-slate-900 border border-slate-800/80 p-6 sm:p-8 rounded-3xl text-white shadow-2xl relative overflow-hidden">
        
        <!-- Background Ambient Glow -->
        <div class="absolute -top-24 -left-24 w-72 h-72 bg-rose-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-72 h-72 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <!-- Left: Shop Info -->
        <div class="space-y-2.5 z-10">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/15 text-emerald-400 text-xs font-bold border border-emerald-500/30">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                <span>লাইভ অটো-প্রিন্ট ক্লাউড লিংক সক্রিয়</span>
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
                <span class="text-emerald-400 flex items-center gap-1.5 font-bold">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    RELAY ACTIVE
                </span>
                <span class="text-slate-500 font-bold">PORT #{{ $shop->id }}</span>
            </div>
            <!-- Printer Body -->
            <div class="w-44 bg-slate-950 rounded-xl p-3 border border-slate-800 text-center relative mt-2 shadow-inner">
                <div class="w-24 h-1.5 bg-slate-800 rounded mx-auto mb-2"></div>
                <!-- Continuous spooling sheet -->
                <div class="w-32 h-7 bg-white rounded-md shadow-md text-[9px] text-slate-900 font-mono font-bold flex items-center justify-center mx-auto animate-pulse">
                    📄 PrintSheba Spool
                </div>
            </div>
            <div class="text-[10px] text-slate-500 mt-2 font-mono">Status: Ready for Prints</div>
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
                <span class="text-3xl font-black text-emerald-400 font-en">৳ {{ number_format($todayEarnings, 2) }}</span>
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
                <span class="text-3xl font-black text-white font-en">{{ $todayPrintsCount }} টি</span>
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
            <span class="text-xs font-bold px-3 py-1 rounded-full bg-slate-800 text-slate-300 border border-slate-700">
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
                <tbody class="divide-y divide-slate-800/80">
                    @forelse($pendingJobs as $job)
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            <td class="px-6 py-4 font-mono font-bold text-white">{{ $job->job_token }}</td>
                            <td class="px-6 py-4 font-medium text-slate-300">
                                <div>{{ $job->customer_name }}</div>
                                @if($job->customer_phone)
                                    <span class="text-[10px] text-slate-500 font-mono">{{ $job->customer_phone }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-slate-400 max-w-xs truncate">{{ $job->original_name }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $job->color_mode === 'color' ? 'bg-pink-500/10 text-pink-400 border border-pink-500/20' : 'bg-slate-800 text-slate-300' }}">
                                    {{ $job->color_mode === 'color' ? 'কালার' : 'সাদাকালো' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-mono font-bold text-emerald-400">৳{{ $job->total_amount }}</td>
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
                                @if($job->payment_status !== 'paid')
                                    <form action="{{ route('shop.jobs.approve', $job) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white font-bold text-xs shadow-md shadow-emerald-500/20 transition-all cursor-pointer flex items-center gap-1">
                                            <span>✓</span>
                                            <span>ক্যাশ পেলাম / প্রিন্ট দিন</span>
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('shop.jobs.reprint', $job) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 font-bold text-xs transition-colors cursor-pointer">
                                            🔄 ফোর্স প্রিন্ট
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
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
                <tbody class="divide-y divide-slate-800/80">
                    @forelse($completedJobs as $job)
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            <td class="px-6 py-4 font-mono font-bold text-white">{{ $job->job_token }}</td>
                            <td class="px-6 py-4 text-slate-400 max-w-xs truncate">{{ $job->original_name }}</td>
                            <td class="px-6 py-4 font-bold text-slate-200">{{ $job->total_pages }} পেজ ({{ $job->paper_size }})</td>
                            <td class="px-6 py-4 font-mono font-bold text-emerald-400">৳{{ $job->total_amount }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $job->payment_method === 'bkash' ? 'bg-pink-500/10 text-pink-400 border border-pink-500/20' : ($job->payment_method === 'nagad' ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20' : 'bg-slate-800 text-slate-300') }}">
                                    {{ $job->payment_method }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-500 text-[11px]">
                                {{ $job->printed_at ? $job->printed_at->format('h:i A') : $job->created_at->format('h:i A') }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <form action="{{ route('shop.jobs.reprint', $job) }}" method="POST" class="inline" onsubmit="return confirm('আপনি কি এই ডকুমেন্টটি পুনরায় প্রিন্ট করতে চান?')">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 rounded-xl bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/30 font-bold text-xs transition-colors cursor-pointer flex items-center gap-1 ml-auto">
                                        <span>🖨️</span>
                                        <span>রি-প্রিন্ট করুন</span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-slate-500">এখনো কোনো প্রিন্ট সম্পন্ন হয়নি</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
