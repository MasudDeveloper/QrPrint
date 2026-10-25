@extends('layouts.shop')

@section('title', 'প্রিন্টার ম্যানেজমেন্ট – ' . $shop->name)

@section('content')
<div class="space-y-8">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-white flex items-center gap-2">
                <span>🖨️ সংযুক্ত প্রিন্টারসমূহ</span>
            </h1>
            <p class="text-xs sm:text-sm text-slate-400">দোকানের প্রিন্টারগুলোর নাম ও অটো-রাউটিং (সাদাকালো vs কালার) কনফিগার করুন</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('shop.agent_setup') }}" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 font-bold text-xs shadow-sm flex items-center gap-2 transition">
                <span>💻 উইন্ডোজ সফটওয়্যার ডাউনলোড</span>
            </a>
        </div>
    </div>

    <!-- Live Cloud Agent Auto-Sync Banner -->
    <div class="p-4 sm:p-5 rounded-3xl bg-emerald-500/10 border border-emerald-500/20 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3.5">
            <span class="relative flex h-3.5 w-3.5">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-emerald-500"></span>
            </span>
            <div>
                <h4 class="text-sm font-black text-emerald-300 flex items-center gap-2">
                    <span>উইন্ডোজ ক্লাউড এজেন্ট অটো-সিঙ্ক সচল</span>
                    <span class="px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 text-[10px] font-bold border border-emerald-500/30">v2.6 Native Sync</span>
                </h4>
                <p class="text-xs text-emerald-400/80 mt-0.5">
                    আপনার কম্পিউটারে <strong>PrintShebaAgent.exe</strong> সফটওয়্যারে যে প্রিন্টার সিলেক্ট করবেন, তা স্বয়ংক্রিয়ভাবে এখানে সিঙ্ক হয়ে যাবে।
                </p>
            </div>
        </div>
        <div class="flex items-center gap-2 shrink-0">
            <button onclick="window.location.reload()" class="px-3.5 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-emerald-400 border border-emerald-500/30 font-bold text-xs transition flex items-center gap-1.5 cursor-pointer shadow-xs">
                <span>🔄 এখনই রিফ্রেশ</span>
            </button>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-8">
        
        <!-- Left 2 Cols: Printer List -->
        <div class="lg:col-span-2 space-y-4">
            @forelse($printers as $printer)
                <div class="bg-slate-900/80 p-6 rounded-3xl border border-slate-800 shadow-sm flex items-center justify-between gap-4 transition hover:border-slate-700">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl {{ $printer->status === 'online' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-slate-800 text-slate-500' }} flex items-center justify-center font-bold text-2xl shrink-0">
                            🖨️
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-base font-bold text-white">{{ $printer->name }}</h3>
                                @if($printer->is_default)
                                    <span class="px-2 py-0.5 rounded-md bg-rose-500/20 text-rose-400 border border-rose-500/30 text-[10px] font-bold">ডিফল্ট প্রিন্টার</span>
                                @endif
                                @if($printer->status === 'online')
                                    <span class="px-2 py-0.5 rounded-md bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 text-[10px] font-bold">অটো-সিঙ্কড</span>
                                @endif
                            </div>
                            <div class="text-xs text-slate-400 mt-1">
                                সিস্টেম ড্রাইভার নাম: <span class="font-mono text-slate-200 font-bold">{{ $printer->system_printer_name }}</span>
                            </div>
                            <div class="flex flex-wrap items-center gap-2 mt-2">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $printer->type === 'bw' ? 'bg-slate-800 text-slate-300' : ($printer->type === 'color' ? 'bg-pink-500/10 text-pink-400 border border-pink-500/20' : 'bg-purple-500/10 text-purple-400 border border-purple-500/20') }}">
                                    রাউটিং: {{ $printer->type === 'bw' ? 'সাদাকালো (BW)' : ($printer->type === 'color' ? 'কালার (Color)' : strtoupper($printer->type)) }}
                                </span>
                                @if($printer->status === 'online')
                                    <span class="flex items-center gap-1.5 text-[11px] text-emerald-400 font-bold">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                        <span>অনলাইন ও প্রস্তুত</span>
                                    </span>
                                @else
                                    <span class="flex items-center gap-1.5 text-[11px] text-slate-500 font-bold">
                                        <span class="w-2 h-2 rounded-full bg-slate-600"></span>
                                        <span>অফলাইন</span>
                                    </span>
                                @endif
                                @if($printer->last_heartbeat_at)
                                    <span class="text-[10px] text-slate-500">
                                        • শেষ সিঙ্ক: {{ $printer->last_heartbeat_at->diffForHumans() }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('shop.printers.delete', $printer) }}" method="POST" onsubmit="return confirm('আপনি কি নিশ্চিত এই প্রিন্টারটি রিমুভ করতে চান?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-3.5 py-2 rounded-xl text-xs font-bold text-rose-400 hover:bg-rose-500/15 border border-rose-500/30 transition-colors cursor-pointer">
                            মুছুন
                        </button>
                    </form>
                </div>
            @empty
                <div class="bg-slate-900/60 p-12 rounded-3xl border border-dashed border-slate-800 text-center text-slate-500">
                    <div class="text-4xl mb-3">🖨️</div>
                    <p class="font-bold text-slate-300">এখনো কোনো প্রিন্টার যোগ করা হয়নি।</p>
                    <p class="text-xs text-slate-500 mt-1">আপনার কম্পিউটারে PrintShebaAgent.exe চালু করুন অথবা ডানপাশের ফর্ম থেকে প্রিন্টার যোগ করুন।</p>
                </div>
            @endforelse
        </div>

        <!-- Right Col: Add New Printer -->
        <div class="bg-slate-900/80 p-6 sm:p-8 rounded-3xl border border-slate-800 shadow-sm h-fit space-y-4">
            <h3 class="text-base font-bold text-white border-b border-slate-800 pb-3 flex items-center justify-between">
                <span>➕ ম্যানুয়ালি প্রিন্টার যোগ</span>
                <span class="text-[10px] font-normal text-slate-400">ঐচ্ছিক</span>
            </h3>

            <form action="{{ route('shop.printers.add') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1">প্রিন্টারের ডিসপ্লে নাম</label>
                    <input type="text" name="name" required 
                        class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-sm text-white focus:outline-none focus:border-rose-500"
                        placeholder="যেমন: HP LaserJet Pro M404">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1">উইন্ডোজের আসল ড্রাইভার নাম (হুবহু)</label>
                    <input type="text" name="system_printer_name" 
                        class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-sm font-mono text-white focus:outline-none focus:border-rose-500"
                        placeholder="HP LaserJet M404dn">
                    <span class="text-[10px] text-slate-500 mt-1 block">Windows Control Panel-এ যেভাবে নাম লেখা আছে</span>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1">প্রিন্টার টাইপ (স্মার্ট রাউটিং)</label>
                    <select name="type" class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-sm text-white focus:outline-none focus:border-rose-500">
                        <option value="bw">সাদাকালো লেজার (HP, Canon)</option>
                        <option value="color">কালার ইঙ্কজেট (Epson, Canon)</option>
                        <option value="all">সব ধরনের প্রিন্ট (All-in-one)</option>
                    </select>
                </div>

                <div class="flex items-center gap-2 pt-1">
                    <input type="checkbox" name="is_default" id="is_default" class="rounded accent-rose-500">
                    <label for="is_default" class="text-xs text-slate-400 font-semibold cursor-pointer">
                        প্রধান / ডিফল্ট প্রিন্টার হিসেবে সেট করুন
                    </label>
                </div>

                <button type="submit" class="w-full py-3 rounded-xl bg-gradient-to-r from-rose-500 to-rose-600 hover:from-rose-600 hover:to-rose-700 text-white font-bold text-xs shadow-md shadow-rose-500/20 transition-all cursor-pointer">
                    প্রিন্টার সেভ করুন
                </button>
            </form>
        </div>

    </div>
</div>
@endsection
