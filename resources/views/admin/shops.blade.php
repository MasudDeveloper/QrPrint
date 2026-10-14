@extends('layouts.admin')

@section('title', 'দোকানসমূহ – সুপার এডমিন')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-white flex items-center gap-2">
                <span>🏪 নিবন্ধিত সকল সাইবার ক্যাফে ও দোকান</span>
            </h1>
            <p class="text-xs sm:text-sm text-slate-400">সারা দেশের সকল দোকানের তথ্য, প্রিন্টার এবং অ্যাক্টিভেশন স্ট্যাটাস পর্যবেক্ষণ ও নিয়ন্ত্রণ করুন</p>
        </div>
    </div>

    <div class="bg-slate-900/80 rounded-3xl border border-slate-800 shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-950/70 text-slate-400 font-bold border-b border-slate-800 uppercase text-[10px] tracking-wider">
                    <tr>
                        <th class="px-6 py-4">দোকানের নাম</th>
                        <th class="px-6 py-4">মালিক ও ফোন</th>
                        <th class="px-6 py-4">ইউনিক লিংক</th>
                        <th class="px-6 py-4">প্রিন্টার</th>
                        <th class="px-6 py-4">মোট প্রিন্ট</th>
                        <th class="px-6 py-4">স্ট্যাটাস</th>
                        <th class="px-6 py-4 text-right">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    @forelse($shops as $shop)
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            <td class="px-6 py-4 font-bold text-white">
                                <div>{{ $shop->name }}</div>
                                <span class="text-[10px] text-slate-500 font-mono">Token: {{ substr($shop->api_token, 0, 10) }}...</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-slate-300">{{ $shop->user->name ?? 'N/A' }}</div>
                                <div class="text-[11px] text-slate-400 font-mono">{{ $shop->phone ?? $shop->user->phone }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('customer.upload', $shop->slug) }}" target="_blank" class="text-rose-400 font-bold hover:underline font-mono text-[11px] flex items-center gap-1">
                                    <span>/print/{{ $shop->slug }}</span>
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-full bg-slate-800 text-slate-300 font-bold text-[11px]">
                                    {{ $shop->printers->count() }} টি
                                </span>
                            </td>
                            <td class="px-6 py-4 font-en font-bold text-emerald-400">
                                {{ $shop->print_jobs_count }}
                            </td>
                            <td class="px-6 py-4">
                                @if($shop->is_active)
                                    <span class="px-2.5 py-1 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 font-bold text-[10px]">
                                        ● সক্রিয় (Active)
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full bg-rose-500/10 text-rose-400 border border-rose-500/20 font-bold text-[10px]">
                                        ● নিষ্ক্রিয় (Suspended)
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <form action="{{ route('admin.shops.toggle', $shop) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 rounded-xl text-xs font-bold transition-colors cursor-pointer {{ $shop->is_active ? 'bg-rose-500/10 text-rose-400 hover:bg-rose-500/20 border border-rose-500/30' : 'bg-emerald-500/10 text-emerald-400 hover:bg-emerald-500/20 border border-emerald-500/30' }}">
                                        {{ $shop->is_active ? 'নিষ্ক্রিয় করুন' : 'সক্রিয় করুন' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-slate-500">কোনো দোকান পাওয়া যায়নি</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
