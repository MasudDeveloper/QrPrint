@extends('layouts.shop')

@section('title', 'আয় ও প্রিন্ট অডিট রিপোর্ট')

@section('content')
<div class="space-y-6">

    <!-- Header & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-white flex items-center gap-2.5">
                <span>📈 {{ $shop->is_office_mode ? '🏢 অফিস প্রিন্ট ও পেপার ব্যবহার অডিট' : 'আয় ও প্রিন্ট অডিট রিপোর্ট' }}</span>
            </h1>
            <p class="text-xs sm:text-sm text-slate-400 mt-1">
                {{ $periodLabel }} — {{ $shop->is_office_mode ? 'অফিসের কর্মীদের পেপার ব্যবহার, রঙিন ও সাদাকালো প্রিন্টের পুঙ্খানুপুঙ্খ অডিট লগ' : 'দোকানদারদের জন্য দৈনিক, সাপ্তাহিক ও মাসিক আয়-ব্যয়ের পুঙ্খানুপুঙ্খ হিসাব' }}
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('shop.reports.export', request()->all()) }}" 
               class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-lg shadow-emerald-600/20 transition-all flex items-center gap-2">
                <span>📊</span> <span>এক্সেল (CSV) এক্সপোর্ট</span>
            </a>
            <a href="{{ route('shop.reports.print', request()->all()) }}" target="_blank"
               class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold text-xs border border-slate-700 transition-all flex items-center gap-2">
                <span>🖨️</span> <span>প্রিন্ট / PDF</span>
            </a>
        </div>
    </div>

    <!-- Date Filter Buttons & Custom Range -->
    <div class="bg-slate-900/80 rounded-3xl p-4 sm:p-5 border border-slate-800 shadow-sm space-y-4">
        <div class="flex flex-wrap items-center gap-2 text-xs">
            <span class="text-slate-400 font-bold mr-1">ফিল্টার:</span>
            <a href="{{ route('shop.reports', ['period' => 'today']) }}" 
               class="px-3.5 py-1.5 rounded-xl font-bold transition-all {{ $period === 'today' ? 'bg-rose-500 text-white shadow-md shadow-rose-500/20' : 'bg-slate-800 text-slate-300 hover:bg-slate-700' }}">
                আজকে
            </a>
            <a href="{{ route('shop.reports', ['period' => 'yesterday']) }}" 
               class="px-3.5 py-1.5 rounded-xl font-bold transition-all {{ $period === 'yesterday' ? 'bg-rose-500 text-white shadow-md shadow-rose-500/20' : 'bg-slate-800 text-slate-300 hover:bg-slate-700' }}">
                গতকাল
            </a>
            <a href="{{ route('shop.reports', ['period' => 'this_week']) }}" 
               class="px-3.5 py-1.5 rounded-xl font-bold transition-all {{ $period === 'this_week' ? 'bg-rose-500 text-white shadow-md shadow-rose-500/20' : 'bg-slate-800 text-slate-300 hover:bg-slate-700' }}">
                এই সপ্তাহ
            </a>
            <a href="{{ route('shop.reports', ['period' => 'this_month']) }}" 
               class="px-3.5 py-1.5 rounded-xl font-bold transition-all {{ $period === 'this_month' ? 'bg-rose-500 text-white shadow-md shadow-rose-500/20' : 'bg-slate-800 text-slate-300 hover:bg-slate-700' }}">
                এই মাস
            </a>
            <a href="{{ route('shop.reports', ['period' => 'last_month']) }}" 
               class="px-3.5 py-1.5 rounded-xl font-bold transition-all {{ $period === 'last_month' ? 'bg-rose-500 text-white shadow-md shadow-rose-500/20' : 'bg-slate-800 text-slate-300 hover:bg-slate-700' }}">
                গত মাস
            </a>
        </div>

        <!-- Custom Date Range Form -->
        <form action="{{ route('shop.reports') }}" method="GET" class="pt-3 border-t border-slate-800/80 flex flex-wrap items-center gap-3 text-xs">
            <input type="hidden" name="period" value="custom">
            <span class="text-slate-400 font-bold">কাস্টম তারিখ:</span>
            <input type="date" name="start_date" value="{{ $startDate ?? today()->toDateString() }}" 
                   class="px-3 py-1.5 rounded-xl bg-slate-950 border border-slate-800 text-white font-mono focus:outline-none focus:border-rose-500">
            <span class="text-slate-500">থেকে</span>
            <input type="date" name="end_date" value="{{ $endDate ?? today()->toDateString() }}" 
                   class="px-3 py-1.5 rounded-xl bg-slate-950 border border-slate-800 text-white font-mono focus:outline-none focus:border-rose-500">
            <button type="submit" class="px-4 py-1.5 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-bold transition-all">
                সার্চ করুন
            </button>
        </form>
    </div>

    <!-- Summary Metrics Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Revenue -->
        <div class="p-5 rounded-3xl bg-slate-900/80 border border-slate-800 space-y-1">
            <span class="text-xs font-bold text-slate-400 block">সর্বমোট আয় (Total Revenue)</span>
            <div class="text-2xl sm:text-3xl font-black text-emerald-400 font-en">৳ {{ number_format($metrics['total_revenue'], 2) }}</div>
            <span class="text-[10px] text-slate-500 block">পরিশোধিত সকল অর্ডার থেকে</span>
        </div>

        <!-- Cash vs Digital Breakdown -->
        <div class="p-5 rounded-3xl bg-slate-900/80 border border-slate-800 space-y-1">
            <span class="text-xs font-bold text-slate-400 block">ক্যাশ বনাম ডিজিটাল আয়</span>
            <div class="text-sm font-bold text-slate-200 pt-1 space-y-1">
                <div class="flex justify-between">
                    <span class="text-slate-400">💵 ক্যাশ আয়:</span>
                    <span class="font-en text-white font-bold">৳ {{ number_format($metrics['cash_revenue'], 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-pink-400">📱 বিকাশ/নগদ:</span>
                    <span class="font-en text-pink-400 font-bold">৳ {{ number_format($metrics['digital_revenue'], 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Total Prints & Pages -->
        <div class="p-5 rounded-3xl bg-slate-900/80 border border-slate-800 space-y-1">
            <span class="text-xs font-bold text-slate-400 block">প্রিন্ট ও পেজ সংখ্যা</span>
            <div class="text-2xl sm:text-3xl font-black text-cyan-400 font-en">{{ number_format($metrics['total_pages_printed']) }} <span class="text-sm font-normal text-slate-400">পেজ</span></div>
            <span class="text-[10px] text-slate-500 block">মোট {{ $metrics['printed_jobs'] }} টি সফল অর্ডার প্রিন্ট হয়েছে</span>
        </div>

        <!-- Color vs BW Breakdown -->
        <div class="p-5 rounded-3xl bg-slate-900/80 border border-slate-800 space-y-1">
            <span class="text-xs font-bold text-slate-400 block">প্রিন্ট মোড বিভাজন</span>
            <div class="text-sm font-bold text-slate-200 pt-1 space-y-1">
                <div class="flex justify-between">
                    <span class="text-slate-400">⚫ সাদাকালো জব:</span>
                    <span class="font-en text-white font-bold">{{ $metrics['bw_jobs'] }} টি</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-rose-400">🎨 রঙিন (Color):</span>
                    <span class="font-en text-rose-400 font-bold">{{ $metrics['color_jobs'] }} টি</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-slate-900/80 rounded-3xl border border-slate-800 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-800 flex items-center justify-between">
            <h3 class="text-sm font-bold text-white flex items-center gap-2">
                <span>📋 বিস্তারিত ট্রানজেকশন ও প্রিন্ট লিস্ট</span>
                <span class="text-xs font-mono font-bold px-2 py-0.5 rounded-full bg-slate-800 text-slate-400">
                    {{ $jobs->total() }} টি রেকর্ড
                </span>
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-300">
                <thead class="bg-slate-950/60 text-slate-400 uppercase text-[10px] font-mono border-b border-slate-800">
                    <tr>
                        <th class="px-5 py-3.5">টোকেন নং</th>
                        <th class="px-5 py-3.5">তারিখ ও সময়</th>
                        <th class="px-5 py-3.5">গ্রাহক / রেফারেন্স</th>
                        <th class="px-5 py-3.5">ফাইল</th>
                        <th class="px-5 py-3.5">পেজ × কপি</th>
                        <th class="px-5 py-3.5">মোড</th>
                        <th class="px-5 py-3.5">মেথড / TrxID</th>
                        <th class="px-5 py-3.5 text-right">টাকার পরিমাণ</th>
                        <th class="px-5 py-3.5 text-center">স্ট্যাটাস</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    @forelse($jobs as $job)
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            <td class="px-5 py-4 font-mono font-bold text-white">{{ $job->job_token }}</td>
                            <td class="px-5 py-4 text-slate-400 font-mono">{{ $job->created_at->format('d M, h:i A') }}</td>
                            <td class="px-5 py-4 font-bold text-slate-200">
                                {{ $job->pickup_reference ?: ($job->customer_name ?: 'কাস্টমার') }}
                            </td>
                            <td class="px-5 py-4 max-w-[180px] truncate text-slate-300" title="{{ $job->original_name }}">
                                {{ $job->original_name }}
                            </td>
                            <td class="px-5 py-4 font-mono font-bold text-slate-200">
                                {{ $job->total_pages }} × {{ $job->copies }}
                                <span class="text-[10px] text-slate-500 font-normal">({{ $job->total_pages * $job->copies }})</span>
                            </td>
                            <td class="px-5 py-4">
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $job->color_mode === 'color' ? 'bg-pink-500/10 text-pink-400 border border-pink-500/20' : 'bg-slate-800 text-slate-300' }}">
                                    {{ $job->color_mode === 'color' ? 'কালার' : 'সাদাকালো' }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <div class="font-bold text-white uppercase text-[11px]">{{ $job->payment_method }}</div>
                                @if($job->payment_trx_id)
                                    <span class="font-mono text-[10px] text-slate-400">{{ $job->payment_trx_id }}</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-right font-en font-black text-sm {{ $job->payment_status === 'paid' ? 'text-emerald-400' : 'text-slate-400' }}">
                                ৳ {{ number_format($job->total_amount, 2) }}
                            </td>
                            <td class="px-5 py-4 text-center">
                                @if($job->print_status === 'printed')
                                    <span class="text-[10px] font-bold px-2.5 py-1 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                        ✓ প্রিন্ট সম্পন্ন
                                    </span>
                                @elseif($job->print_status === 'cancelled')
                                    <span class="text-[10px] font-bold px-2.5 py-1 rounded-full bg-rose-500/10 text-rose-400 border border-rose-500/20">
                                        ✕ বাতিল
                                    </span>
                                @else
                                    <span class="text-[10px] font-bold px-2.5 py-1 rounded-full bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                        ⏳ কিউতে আছে
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center text-slate-500">
                                নির্বাচিত সময়ের মধ্যে কোনো প্রিন্ট বা ট্রানজেকশনের রেকর্ড পাওয়া যায়নি।
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($jobs->hasPages())
            <div class="p-4 border-t border-slate-800">
                {{ $jobs->links() }}
            </div>
        @endif
    </div>

</div>
@endsection

