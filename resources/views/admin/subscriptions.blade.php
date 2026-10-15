@extends('layouts.admin')

@section('title', 'সাবস্ক্রিপশন ও পেমেন্ট কিউ – সুপার এডমিন')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-rose-500/15 text-rose-400 text-xs font-bold border border-rose-500/30 mb-2">
                <span class="w-2 h-2 rounded-full bg-rose-400 animate-pulse"></span>
                <span>প্যাকেজ সাবস্ক্রিপশন কিউ</span>
            </div>
            <h1 class="text-2xl font-black text-white flex items-center gap-2">
                <span>💎 দোকানদারদের সাবস্ক্রিপশন ও পেমেন্ট হিস্ট্রি</span>
            </h1>
            <p class="text-xs sm:text-sm text-slate-400">দোকানদারদের পাঠানো বিকাশ ও নগদ TrxID ভেরিফাই করে অনুমোদন (Approve) করুন</p>
        </div>

        <div class="flex items-center gap-3">
            <span class="px-4 py-2 rounded-2xl bg-amber-500/15 text-amber-400 border border-amber-500/30 text-xs font-bold flex items-center gap-2">
                <span>⏳ অপেক্ষমান রিকোয়েস্ট:</span>
                <span class="font-black text-sm">{{ $pendingCount }} টি</span>
            </span>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 text-xs sm:text-sm font-bold flex items-center gap-3">
            <span class="text-xl">✅</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-slate-900/80 rounded-3xl border border-slate-800 shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-950/70 text-slate-400 font-bold border-b border-slate-800 uppercase text-[10px] tracking-wider">
                    <tr>
                        <th class="px-6 py-4">আবেদনের সময়</th>
                        <th class="px-6 py-4">দোকানের নাম</th>
                        <th class="px-6 py-4">নির্বাচিত প্যাকেজ</th>
                        <th class="px-6 py-4">টাকার পরিমাণ</th>
                        <th class="px-6 py-4">পেমেন্ট মাধ্যম</th>
                        <th class="px-6 py-4">TrxID ও সেন্ডার নম্বর</th>
                        <th class="px-6 py-4">স্ট্যাটাস</th>
                        <th class="px-6 py-4 text-right">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    @forelse($subscriptions as $sub)
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            <td class="px-6 py-4 text-slate-400 font-mono">
                                {{ $sub->created_at->format('d M, Y h:i A') }}
                            </td>
                            <td class="px-6 py-4 font-bold text-white">
                                <div class="text-sm">{{ $sub->shop->name ?? 'দোকান নেই' }}</div>
                                <div class="text-[10px] text-slate-400 font-mono">{{ $sub->shop->phone ?? '' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-bold text-rose-400">{{ $sub->plan_name }}</span>
                            </td>
                            <td class="px-6 py-4 font-en font-black text-emerald-400 text-sm">
                                ৳ {{ number_format($sub->amount, 0) }}
                            </td>
                            <td class="px-6 py-4 uppercase font-bold text-slate-300">
                                @if($sub->payment_method === 'bkash')
                                    <span class="text-pink-400">bKash</span>
                                @elseif($sub->payment_method === 'nagad')
                                    <span class="text-amber-400">Nagad</span>
                                @else
                                    {{ $sub->payment_method }}
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-mono font-black text-white text-xs tracking-wider bg-slate-950 px-2.5 py-1 rounded-lg border border-slate-800 inline-block">
                                    {{ $sub->transaction_id ?? 'N/A' }}
                                </div>
                                <div class="text-[10px] text-slate-400 font-mono mt-1">সেন্ডার: {{ $sub->sender_number }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @if($sub->status === 'approved')
                                    <span class="px-2.5 py-1 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 font-bold text-[10px]">
                                        ● অনুমোদিত (Active)
                                    </span>
                                @elseif($sub->status === 'rejected')
                                    <span class="px-2.5 py-1 rounded-full bg-rose-500/10 text-rose-400 border border-rose-500/20 font-bold text-[10px]">
                                        ✕ বাতিল (Rejected)
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full bg-amber-500/10 text-amber-400 border border-amber-500/20 font-bold text-[10px] animate-pulse">
                                        ⏳ অপেক্ষমান (Pending)
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if($sub->status === 'pending')
                                    <div class="flex items-center justify-end gap-2">
                                        <form action="{{ route('admin.subscriptions.approve', $sub) }}" method="POST">
                                            @csrf
                                            <button type="submit" onclick="return confirm('আপনি কি নিশ্চিত যে TrxID ভেরিফাই করে প্যাকেজটি সক্রিয় করতে চান?')" class="px-3 py-1.5 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-xs shadow-md transition-all cursor-pointer">
                                                ✓ অনুমোদন (Approve)
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.subscriptions.reject', $sub) }}" method="POST">
                                            @csrf
                                            <button type="submit" onclick="return confirm('আপনি কি এই রিকোয়েস্টটি বাতিল করতে চান?')" class="px-3 py-1.5 rounded-xl bg-rose-500/20 hover:bg-rose-500 text-rose-300 hover:text-white border border-rose-500/30 font-bold text-xs transition-all cursor-pointer">
                                                ✕ বাতিল
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-[10px] text-slate-500 font-mono">
                                        {{ $sub->approved_at ? $sub->approved_at->format('d M, h:i A') : 'সম্পন্ন' }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-slate-500">কোনো সাবস্ক্রিপশন রিকোয়েস্ট পাওয়া যায়নি</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($subscriptions->hasPages())
        <div class="p-4 border-t border-slate-800">
            {{ $subscriptions->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
