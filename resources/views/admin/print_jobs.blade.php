@extends('layouts.admin')

@section('title', 'সকল প্রিন্ট জব – সুপার এডমিন')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-black text-white flex items-center gap-2">
            <span>📄 সকল প্রিন্ট জব (Global Activity)</span>
        </h1>
        <p class="text-xs sm:text-sm text-slate-400">সারা দেশের প্রিন্টিং নেটওয়ার্কের রিয়েল-টাইম হিস্ট্রি ও ডাটা অডিট</p>
    </div>

    <div class="bg-slate-900/80 rounded-3xl border border-slate-800 shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-950/70 text-slate-400 font-bold border-b border-slate-800 uppercase text-[10px] tracking-wider">
                    <tr>
                        <th class="px-6 py-4">টোকেন নং</th>
                        <th class="px-6 py-4">দোকানের নাম</th>
                        <th class="px-6 py-4">কাস্টমার / ফোন</th>
                        <th class="px-6 py-4">ফাইল</th>
                        <th class="px-6 py-4">পেজ ও সাইজ</th>
                        <th class="px-6 py-4">টাকার পরিমাণ</th>
                        <th class="px-6 py-4">পেমেন্ট মেথড</th>
                        <th class="px-6 py-4">প্রিন্ট স্ট্যাটাস</th>
                        <th class="px-6 py-4">তারিখ ও সময়</th>
                        <th class="px-6 py-4 text-right">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    @forelse($jobs as $job)
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            <td class="px-6 py-4 font-mono font-bold text-white">{{ $job->job_token }}</td>
                            <td class="px-6 py-4 font-medium text-slate-300">{{ $job->shop->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4">
                                <div class="text-white">{{ $job->customer_name }}</div>
                                <span class="text-[10px] text-slate-500 font-mono">{{ $job->customer_phone ?? 'N/A' }}</span>
                            </td>
                            <td class="px-6 py-4 text-slate-400 max-w-xs truncate">{{ $job->original_name }}</td>
                            <td class="px-6 py-4">
                                <span class="font-bold text-slate-200">{{ $job->total_pages }} পেজ</span>
                                <span class="text-[10px] text-slate-500 block">({{ $job->paper_size }})</span>
                            </td>
                            <td class="px-6 py-4 font-mono font-bold text-emerald-400">৳{{ $job->total_amount }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $job->payment_method === 'bkash' ? 'bg-pink-500/10 text-pink-400 border border-pink-500/20' : ($job->payment_method === 'nagad' ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20' : 'bg-slate-800 text-slate-300') }}">
                                    {{ $job->payment_method }}
                                </span>
                                @if($job->payment_trx_id)
                                    <span class="text-[9px] text-slate-500 font-mono block mt-0.5 truncate">{{ $job->payment_trx_id }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $job->print_status === 'printed' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-blue-500/10 text-blue-400 border border-blue-500/20' }}">
                                    {{ strtoupper($job->print_status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-500 text-[11px] whitespace-nowrap">
                                {{ $job->created_at->format('d M, h:i A') }}
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                @if($job->payment_status !== 'paid' && $job->print_status !== 'cancelled')
                                    <form action="{{ route('admin.jobs.approve', $job) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-2.5 py-1 rounded-lg bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 font-bold text-[11px] transition-colors cursor-pointer">
                                            ✓ এপ্রুভ করুন
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.jobs.cancel', $job) }}" method="POST" class="inline" onsubmit="return confirm('আপনি কি নিশ্চিত এই প্রিন্ট অর্ডারটি বাতিল করতে চান?')">
                                        @csrf
                                        <button type="submit" class="px-2.5 py-1 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/30 font-bold text-[11px] transition-colors cursor-pointer">
                                            ✕ বাতিল
                                        </button>
                                    </form>
                                @elseif($job->print_status === 'cancelled')
                                    <span class="text-rose-400 font-bold text-[11px]">বাতিলকৃত</span>
                                @else
                                    <form action="{{ route('admin.jobs.reprint', $job) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-2.5 py-1 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/30 font-bold text-[11px] transition-colors cursor-pointer">
                                            🔄 রিপ্রিন্ট
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-6 py-8 text-center text-slate-500">কোনো প্রিন্ট জব পাওয়া যায়নি</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(method_exists($jobs, 'links'))
            <div class="p-4 border-t border-slate-800">
                {{ $jobs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
