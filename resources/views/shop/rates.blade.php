@extends('layouts.shop')

@section('title', 'রেট ও পেমেন্ট নম্বর – ' . $shop->name)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-black text-white flex items-center gap-2">
            <span>💰 প্রিন্ট রেট ও পেমেন্ট সেটিংস</span>
        </h1>
        <p class="text-xs sm:text-sm text-slate-400">আপনার দোকানের নিজস্ব প্রিন্ট চার্জ এবং সরাসরি বিকাশ/নগদ পেমেন্ট নম্বর নির্ধারণ করুন</p>
    </div>

    <form action="{{ route('shop.rates.update') }}" method="POST" class="bg-slate-900/80 rounded-3xl p-6 sm:p-8 border border-slate-800 shadow-xl space-y-6">
        @csrf

        <!-- Shop & Phone Info -->
        <div class="space-y-4">
            <h3 class="text-sm font-bold text-white uppercase tracking-wider border-b border-slate-800 pb-2">
                সাধারণ তথ্য
            </h3>
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">দোকানের নাম</label>
                    <input type="text" name="name" value="{{ old('name', $shop->name) }}" required 
                        class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-800 text-sm text-white focus:outline-none focus:border-rose-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">দোকানের মোবাইল নম্বর</label>
                    <input type="text" name="phone" value="{{ old('phone', $shop->phone) }}" 
                        class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-800 text-sm text-white focus:outline-none focus:border-rose-500">
                </div>
            </div>
        </div>

        <!-- Payment Numbers -->
        <div class="space-y-4 pt-2">
            <h3 class="text-sm font-bold text-white uppercase tracking-wider border-b border-slate-800 pb-2 flex items-center justify-between">
                <span>মোবাইল ব্যাংকিং ওয়ালেট</span>
                <span class="text-[11px] text-emerald-400 font-normal">১০০% সরাসরি আপনার নিজস্ব নম্বরে টাকা আসবে</span>
            </h3>
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-pink-400 mb-1.5">বিকাশ পার্সোনাল নম্বর (bKash)</label>
                    <input type="text" name="bkash_number" value="{{ old('bkash_number', $shop->bkash_number) }}" 
                        class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-pink-500/30 text-sm font-mono text-white focus:outline-none focus:border-pink-500"
                        placeholder="017XXXXXXXX">
                </div>
                <div>
                    <label class="block text-xs font-bold text-amber-400 mb-1.5">নগদ পার্সোনাল নম্বর (Nagad)</label>
                    <input type="text" name="nagad_number" value="{{ old('nagad_number', $shop->nagad_number) }}" 
                        class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-amber-500/30 text-sm font-mono text-white focus:outline-none focus:border-amber-500"
                        placeholder="018XXXXXXXX">
                </div>
            </div>
        </div>

        <!-- Service Availability Toggles (Color, Duplex, Passport) -->
        <div class="space-y-4 pt-2">
            <h3 class="text-sm font-bold text-white uppercase tracking-wider border-b border-slate-800 pb-2 flex items-center justify-between">
                <span>দোকানের প্রিন্টিং সার্ভিসসমূহ (চালু / বন্ধ)</span>
                <span class="text-[11px] text-slate-400 font-normal">কালার প্রিন্টার না থাকলে বন্ধ করে রাখুন, কাস্টমার স্ক্রিনে দেখাবে না</span>
            </h3>

            <div class="grid sm:grid-cols-3 gap-4">
                <!-- Color Print Toggle -->
                <label class="p-4 rounded-2xl bg-slate-950 border border-slate-800 cursor-pointer flex items-center justify-between hover:border-slate-700 transition-all">
                    <div>
                        <span class="text-xs font-bold text-pink-400 block">🎨 কালার প্রিন্ট</span>
                        <span class="text-[10px] text-slate-400">রঙিন প্রিন্টার আছে কি?</span>
                    </div>
                    <input type="checkbox" name="has_color_print" value="1" {{ old('has_color_print', $shop->has_color_print) ? 'checked' : '' }} class="w-5 h-5 accent-pink-500 rounded">
                </label>

                <!-- Duplex Print Toggle -->
                <label class="p-4 rounded-2xl bg-slate-950 border border-slate-800 cursor-pointer flex items-center justify-between hover:border-slate-700 transition-all">
                    <div>
                        <span class="text-xs font-bold text-slate-200 block">📄 এপিঠ-ওপিঠ (Duplex)</span>
                        <span class="text-[10px] text-slate-400">বোথ সাইড সাপোর্ট</span>
                    </div>
                    <input type="checkbox" name="has_duplex_print" value="1" {{ old('has_duplex_print', $shop->has_duplex_print) ? 'checked' : '' }} class="w-5 h-5 accent-rose-500 rounded">
                </label>

                <!-- Passport Photo Toggle -->
                <label class="p-4 rounded-2xl bg-slate-950 border border-slate-800 cursor-pointer flex items-center justify-between hover:border-slate-700 transition-all">
                    <div>
                        <span class="text-xs font-bold text-purple-400 block">📸 পাসপোর্ট সাইজ ছবি</span>
                        <span class="text-[10px] text-slate-400">৪×৬ ফটো পেপার প্রিন্ট</span>
                    </div>
                    <input type="checkbox" name="has_passport_photo" value="1" {{ old('has_passport_photo', $shop->has_passport_photo) ? 'checked' : '' }} class="w-5 h-5 accent-purple-500 rounded">
                </label>
            </div>
        </div>

        <!-- Rate Pricing Matrix -->
        <div class="space-y-4 pt-2">
            <h3 class="text-sm font-bold text-white uppercase tracking-wider border-b border-slate-800 pb-2">
                প্রতি পেজ প্রিন্ট রেট তালিকা (টাকা)
            </h3>
            
            <div class="grid sm:grid-cols-2 gap-4">
                <!-- B&W Single -->
                <div class="p-4 rounded-2xl bg-slate-950 border border-slate-800">
                    <label class="block text-xs font-bold text-slate-200 mb-1">সাদাকালো (১ পাশ / Single)</label>
                    <span class="text-[11px] text-slate-400 block mb-2">লেজার প্রিন্টারে সাধারণ সাদা-কালো প্রতি পেজ</span>
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-bold text-slate-400">৳</span>
                        <input type="number" step="0.5" name="rate_bw_single" value="{{ old('rate_bw_single', $shop->rate_bw_single) }}" required 
                            class="w-full px-3 py-2 rounded-lg bg-slate-900 border border-slate-700 font-bold text-white text-sm">
                    </div>
                </div>

                <!-- B&W Double -->
                <div class="p-4 rounded-2xl bg-slate-950 border border-slate-800">
                    <label class="block text-xs font-bold text-slate-200 mb-1">সাদাকালো (উভয় পাশ / Duplex)</label>
                    <span class="text-[11px] text-slate-400 block mb-2">একই পাতার এপিঠ-ওপিঠ প্রিন্ট রেট</span>
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-bold text-slate-400">৳</span>
                        <input type="number" step="0.5" name="rate_bw_double" value="{{ old('rate_bw_double', $shop->rate_bw_double) }}" required 
                            class="w-full px-3 py-2 rounded-lg bg-slate-900 border border-slate-700 font-bold text-white text-sm">
                    </div>
                </div>

                <!-- Color Single -->
                <div class="p-4 rounded-2xl bg-slate-950 border border-pink-500/20">
                    <label class="block text-xs font-bold text-pink-400 mb-1">কালার (১ পাশ / Single)</label>
                    <span class="text-[11px] text-slate-400 block mb-2">ইঙ্কজেটে রঙিন ডকুমেন্ট প্রতি পেজ</span>
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-bold text-pink-400">৳</span>
                        <input type="number" step="0.5" name="rate_color_single" value="{{ old('rate_color_single', $shop->rate_color_single) }}" required 
                            class="w-full px-3 py-2 rounded-lg bg-slate-900 border border-slate-700 font-bold text-white text-sm">
                    </div>
                </div>

                <!-- Color Double -->
                <div class="p-4 rounded-2xl bg-slate-950 border border-pink-500/20">
                    <label class="block text-xs font-bold text-pink-400 mb-1">কালার (উভয় পাশ / Duplex)</label>
                    <span class="text-[11px] text-slate-400 block mb-2">রঙিন এপিঠ-ওপিঠ বোথ সাইড প্রিন্ট</span>
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-bold text-pink-400">৳</span>
                        <input type="number" step="0.5" name="rate_color_double" value="{{ old('rate_color_double', $shop->rate_color_double) }}" required 
                            class="w-full px-3 py-2 rounded-lg bg-slate-900 border border-slate-700 font-bold text-white text-sm">
                    </div>
                </div>

                <!-- 4x6 Passport Photo -->
                <div class="sm:col-span-2 p-4 rounded-2xl bg-slate-950 border border-purple-500/20">
                    <label class="block text-xs font-bold text-purple-400 mb-1">৪×৬ পাসপোর্ট সাইজ ছবি (ফটো পেপার)</label>
                    <span class="text-[11px] text-slate-400 block mb-2">হাই-গ্লসি ফটো পেপারে অটো-গ্রিড পাসপোর্ট সাইজ প্রিন্ট প্যাকেজ</span>
                    <div class="flex items-center gap-2 max-w-xs">
                        <span class="text-sm font-bold text-purple-400">৳</span>
                        <input type="number" step="1" name="rate_photo_passport" value="{{ old('rate_photo_passport', $shop->rate_photo_passport) }}" required 
                            class="w-full px-3 py-2 rounded-lg bg-slate-900 border border-slate-700 font-bold text-white text-sm">
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-4 flex justify-end">
            <button type="submit" class="px-8 py-3.5 rounded-xl bg-gradient-to-r from-rose-500 to-rose-600 hover:from-rose-600 hover:to-rose-700 text-white font-bold text-sm shadow-lg shadow-rose-500/20 transition-all cursor-pointer">
                💾 রেট সংরক্ষণ করুন
            </button>
        </div>
    </form>
</div>
@endsection
