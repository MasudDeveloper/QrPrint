@extends('layouts.shop')

@section('title', 'কিউআর পোস্টার – ' . $shop->name)

@section('content')
<style>
    @media print {
        @page {
            size: A4 portrait;
            margin: 10mm;
        }
        body {
            background-color: #ffffff !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        #printablePosterCard {
            border: 3px solid #0f172a !important;
            border-radius: 28px !important;
            padding: 30px !important;
            box-shadow: none !important;
            margin: 0 auto !important;
            max-width: 100% !important;
            page-break-inside: avoid !important;
        }
    }
</style>

<div class="max-w-3xl mx-auto space-y-6">
    
    <!-- Action Banner (Hidden on print) -->
    <div class="flex items-center justify-between bg-slate-900/90 p-5 rounded-3xl border border-slate-800 shadow-xl print:hidden">
        <div>
            <h1 class="text-lg font-bold text-white flex items-center gap-2">
                <span>🖨️ কাউন্টার কিউআর কোড পোস্টার</span>
            </h1>
            <p class="text-xs text-slate-400">পোস্টারটি এক ক্লিকে প্রিন্ট করে আপনার দোকানের দেওয়ালে বা কাঁচের কাউন্টারে সাঁটিয়ে দিন</p>
        </div>
        <button onclick="window.print()" class="px-6 py-3 rounded-2xl bg-gradient-to-r from-rose-500 to-rose-600 hover:from-rose-600 hover:to-rose-700 text-white font-bold text-xs shadow-lg shadow-rose-500/25 transition-all flex items-center gap-2 cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            <span>পোস্টার প্রিন্ট করুন</span>
        </button>
    </div>

    <!-- The Printable Poster Card (Always bright white for crisp printout) -->
    <div id="printablePosterCard" class="bg-white rounded-[36px] border-4 border-slate-900 p-8 sm:p-12 text-center space-y-8 shadow-2xl relative overflow-hidden">
        
        <!-- Header Badge with Logo -->
        <div class="flex items-center justify-center gap-2.5">
            <img src="{{ asset('logo.svg') }}" alt="PrintSheba" class="w-8 h-8 rounded-lg">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-slate-950 text-white text-xs font-bold uppercase tracking-wider">
                <span>⚡ স্মার্ট সেলফ-সার্ভিস প্রিন্ট কাউন্টার</span>
            </div>
        </div>

        <!-- Shop Name & Tagline -->
        <div class="space-y-2">
            <h2 class="text-3xl sm:text-5xl font-black text-slate-950 tracking-tight">
                {{ $shop->name }}
            </h2>
            <p class="text-base sm:text-lg font-bold text-rose-600">
                হোয়াটসঅ্যাপ বা পেনড্রাইভ ছাড়াই মোবাইল থেকে সরাসরি প্রিন্ট করুন
            </p>
        </div>

        <!-- QR Code Box -->
        <div class="w-full max-w-sm mx-auto p-6 bg-slate-50 rounded-3xl border-2 border-dashed border-slate-300 flex flex-col items-center">
            <!-- QR Code using reliable API -->
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data={{ urlencode($printUrl) }}&color=0f172a" 
                 alt="Shop QR Code" 
                 class="w-56 h-56 rounded-2xl shadow-md border-4 border-white">
            
            <div class="mt-4 px-3.5 py-2 rounded-xl bg-white border border-slate-200/90 shadow-xs w-full text-center">
                <span class="text-xs font-mono font-bold text-slate-700 break-all select-all block leading-relaxed">
                    {{ $printUrl }}
                </span>
            </div>
            <span class="text-[11px] font-bold text-emerald-600 mt-2 uppercase tracking-wider flex items-center gap-1">
                <span>●</span> মোবাইল ক্যামেরা দিয়ে স্ক্যান করুন
            </span>
        </div>

        <!-- 4 Steps Visual Guide -->
        <div class="grid grid-cols-4 gap-3 pt-2">
            <div class="p-3 rounded-2xl bg-slate-50 border border-slate-200">
                <span class="w-6 h-6 rounded-full bg-rose-500 text-white font-bold text-xs flex items-center justify-center mx-auto mb-1">১</span>
                <span class="text-xs font-black text-slate-900 block">QR স্ক্যান</span>
                <span class="text-[10px] text-slate-500">ক্যামেরা অন করুন</span>
            </div>
            <div class="p-3 rounded-2xl bg-slate-50 border border-slate-200">
                <span class="w-6 h-6 rounded-full bg-purple-600 text-white font-bold text-xs flex items-center justify-center mx-auto mb-1">২</span>
                <span class="text-xs font-black text-slate-900 block">ফাইল দিন</span>
                <span class="text-[10px] text-slate-500">PDF বা ছবি</span>
            </div>
            <div class="p-3 rounded-2xl bg-slate-50 border border-slate-200">
                <span class="w-6 h-6 rounded-full bg-pink-500 text-white font-bold text-xs flex items-center justify-center mx-auto mb-1">৩</span>
                <span class="text-xs font-black text-slate-900 block">পেমেন্ট</span>
                <span class="text-[10px] text-slate-500">বিকাশ / নগদ</span>
            </div>
            <div class="p-3 rounded-2xl bg-slate-50 border border-slate-200">
                <span class="w-6 h-6 rounded-full bg-emerald-500 text-white font-bold text-xs flex items-center justify-center mx-auto mb-1">৪</span>
                <span class="text-xs font-black text-slate-900 block">অটো প্রিন্ট</span>
                <span class="text-[10px] text-slate-500">নিমিষেই বের হবে</span>
            </div>
        </div>

        <!-- Privacy & Power by Footer -->
        <div class="pt-4 border-t border-slate-200 text-xs text-slate-400 flex items-center justify-between">
            <span>🔒 ১০০% প্রাইভেসি: প্রিন্ট হওয়ামাত্র সার্ভার ও পিসি থেকে ফাইল ডিলিট</span>
            <span class="font-bold text-slate-600 flex items-center gap-1.5">
                <img src="{{ asset('logo.svg') }}" alt="Logo" class="w-4 h-4">
                <span>PrintSheba Cloud</span>
            </span>
        </div>
    </div>
</div>
@endsection
