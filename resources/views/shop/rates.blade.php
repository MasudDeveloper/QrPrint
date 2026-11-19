@extends('layouts.shop')

@section('title', ($shop->is_office_mode ? 'অফিস সেটিংস' : 'রেট ও পেমেন্ট নম্বর') . ' – ' . $shop->name)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-black text-white flex items-center gap-2">
            <span>{{ $shop->is_office_mode ? '🏢 অফিস সেটিংস ও প্রিন্টার কনফিগারেশন' : '💰 প্রিন্ট রেট ও পেমেন্ট সেটিংস' }}</span>
        </h1>
        <p class="text-xs sm:text-sm text-slate-400">
            {{ $shop->is_office_mode 
                ? 'আপনার প্রতিষ্ঠানের নাম, কর্মী যোগাযোগ এবং কর্মীদের জন্য প্রিন্টিং ফিচারসমূহ কনফিগার করুন' 
                : 'আপনার দোকানের নিজস্ব প্রিন্ট চার্জ এবং সরাসরি বিকাশ/নগদ পেমেন্ট নম্বর নির্ধারণ করুন' }}
        </p>
    </div>

    <form action="{{ route('shop.rates.update') }}" method="POST" class="bg-slate-900/80 rounded-3xl p-6 sm:p-8 border {{ $shop->is_office_mode ? 'border-indigo-900/50' : 'border-slate-800' }} shadow-xl space-y-6">
        @csrf

        <!-- Office Mode Network Banner -->
        @if($shop->is_office_mode)
            <div class="p-4 sm:p-5 rounded-2xl bg-indigo-950/40 border border-indigo-500/30 flex items-start gap-4">
                <span class="text-3xl">🏢</span>
                <div class="space-y-1">
                    <div class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md bg-indigo-500/20 text-indigo-300 text-[10px] font-bold uppercase">
                        সেন্ট্রাল অফিস নেটওয়ার্ক সক্রিয়
                    </div>
                    <h3 class="text-sm font-bold text-white">অভ্যন্তরীণ অটো-প্রিন্ট নেটওয়ার্ক</h3>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        প্রতিষ্ঠানের কর্মীরা QR কোড স্ক্যান করে বা নিজস্ব লিংক থেকে সরাসরি সেন্ট্রাল প্রিন্টারে ডকুমেন্ট প্রিন্ট দিতে পারবেন। নিচে প্রতিষ্ঠানের সাধারণ তথ্য কনফিগার করুন।
                    </p>
                </div>
            </div>
        @endif

        <!-- Establishment & Phone Info -->
        <div class="space-y-4">
            <h3 class="text-sm font-bold text-white uppercase tracking-wider border-b border-slate-800 pb-2">
                {{ $shop->is_office_mode ? 'প্রতিষ্ঠানের তথ্য' : 'সাধারণ তথ্য' }}
            </h3>
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">
                        {{ $shop->is_office_mode ? 'প্রতিষ্ঠানের নাম / কোম্পানি' : 'দোকানের নাম' }}
                    </label>
                    <input type="text" name="name" value="{{ old('name', $shop->name) }}" required 
                        class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-800 text-sm text-white focus:outline-none {{ $shop->is_office_mode ? 'focus:border-indigo-500' : 'focus:border-rose-500' }}">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">
                        {{ $shop->is_office_mode ? 'অফিসিয়াল যোগাযোগ নম্বর' : 'দোকানের মোবাইল নম্বর' }}
                    </label>
                    <input type="text" name="phone" value="{{ old('phone', $shop->phone) }}" 
                        class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-800 text-sm text-white focus:outline-none {{ $shop->is_office_mode ? 'focus:border-indigo-500' : 'focus:border-rose-500' }}">
                </div>
            </div>
        </div>

        @if(!$shop->is_office_mode)
            <!-- Payment Numbers (COMMERCIAL SHOPS ONLY) -->
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
        @endif

        <!-- Service Availability Toggles (Color, Duplex, Passport) -->
        <div class="space-y-4 pt-2">
            <h3 class="text-sm font-bold text-white uppercase tracking-wider border-b border-slate-800 pb-2 flex items-center justify-between">
                <span>{{ $shop->is_office_mode ? 'অফিসের প্রিন্টিং সার্ভিসসমূহ (চালু / বন্ধ)' : 'দোকানের প্রিন্টিং সার্ভিসসমূহ (চালু / বন্ধ)' }}</span>
                <span class="text-[11px] text-slate-400 font-normal">
                    {{ $shop->is_office_mode ? 'যে ফিচারটি চালু রাখবেন কর্মীরা কেবল সেটাই ব্যবহার করতে পারবেন' : 'কালার প্রিন্টার না থাকলে বন্ধ করে রাখুন, কাস্টমার স্ক্রিনে দেখাবে না' }}
                </span>
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
                    <input type="checkbox" name="has_duplex_print" value="1" {{ old('has_duplex_print', $shop->has_duplex_print) ? 'checked' : '' }} class="w-5 h-5 {{ $shop->is_office_mode ? 'accent-indigo-500' : 'accent-rose-500' }} rounded">
                </label>

                <!-- NID Card Print Toggle -->
                <label class="p-4 rounded-2xl bg-slate-950 border border-slate-800 cursor-pointer flex items-center justify-between hover:border-slate-700 transition-all">
                    <div>
                        <span class="text-xs font-bold text-emerald-400 block">🪪 এনআইডি কার্ড প্রিন্ট (NID)</span>
                        <span class="text-[10px] text-slate-400">স্মার্ট কার্ড ফ্রন্ট-ব্যাক অটো-ক্রপ ও A4 ফিট</span>
                    </div>
                    <input type="checkbox" name="has_nid_print" value="1" {{ old('has_nid_print', $shop->has_nid_print) ? 'checked' : '' }} class="w-5 h-5 accent-emerald-500 rounded">
                </label>
            </div>
        </div>

        @if($shop->is_office_mode)
            <!-- OFFICE PORTAL LINK CARD -->
            <div class="p-4 rounded-2xl bg-slate-950 border border-indigo-500/30 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="space-y-0.5">
                    <span class="text-[10px] font-bold uppercase text-indigo-400">কর্মীদের ডিরেক্ট প্রিন্ট পোর্টাল লিংক:</span>
                    <div class="font-mono text-xs text-white">{{ url('/office/' . $shop->slug) }}</div>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="navigator.clipboard.writeText('{{ url('/office/' . $shop->slug) }}'); alert('অফিস লিংক কপি হয়েছে!');" class="px-3.5 py-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition-all shadow">
                        লিংক কপি করুন
                    </button>
                    <a href="{{ url('/office/' . $shop->slug) }}" target="_blank" class="px-3.5 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-bold transition-all border border-slate-700">
                        ভিজিট করুন ↗
                    </a>
                </div>
            </div>
        @else
            <!-- Rate Pricing Matrix (COMMERCIAL SHOPS ONLY) -->
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

                    <!-- NID Card B&W Rate -->
                    <div class="p-4 rounded-2xl bg-slate-950 border border-emerald-500/20">
                        <label class="block text-xs font-bold text-emerald-400 mb-1">এনআইডি সাদাকালো (NID B&W)</label>
                        <span class="text-[11px] text-slate-400 block mb-2">উভয় পিঠ এ৪ পেজে সাদাকালো প্রিন্ট রেট</span>
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-bold text-emerald-400">৳</span>
                            <input type="number" step="0.5" name="rate_nid_bw" value="{{ old('rate_nid_bw', $shop->rate_nid_bw ?? 10.00) }}" required 
                                class="w-full px-3 py-2 rounded-lg bg-slate-900 border border-slate-700 font-bold text-white text-sm">
                        </div>
                    </div>

                    <!-- NID Card Color Rate -->
                    <div class="sm:col-span-2 p-4 rounded-2xl bg-slate-950 border border-emerald-500/30">
                        <label class="block text-xs font-bold text-emerald-400 mb-1">এনআইডি রঙিন (NID Color)</label>
                        <span class="text-[11px] text-slate-400 block mb-2">উভয় পিঠ এ৪ পেজে অরিজিনাল রঙিন প্রিন্ট রেট</span>
                        <div class="flex items-center gap-2 max-w-xs">
                            <span class="text-sm font-bold text-emerald-400">৳</span>
                            <input type="number" step="0.5" name="rate_nid_color" value="{{ old('rate_nid_color', $shop->rate_nid_color ?? 20.00) }}" required 
                                class="w-full px-3 py-2 rounded-lg bg-slate-900 border border-slate-700 font-bold text-white text-sm">
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="pt-4 flex justify-end">
            <button type="submit" class="px-8 py-3.5 rounded-xl bg-gradient-to-r {{ $shop->is_office_mode ? 'from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 shadow-indigo-600/20' : 'from-rose-500 to-rose-600 hover:from-rose-600 hover:to-rose-700 shadow-rose-500/20' }} text-white font-bold text-sm shadow-lg transition-all cursor-pointer">
                {{ $shop->is_office_mode ? '💾 অফিস সেটিংস সংরক্ষণ করুন' : '💾 রেট সংরক্ষণ করুন' }}
            </button>
        </div>
    </form>
</div>
@endsection
