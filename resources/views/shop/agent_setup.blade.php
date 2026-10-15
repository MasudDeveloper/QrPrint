@extends('layouts.shop')

@section('title', 'উইন্ডোজ এজেন্ট সফটওয়্যার – ' . $shop->name)

@section('content')
<div class="max-w-5xl mx-auto space-y-8">
    <div>
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-rose-500/15 text-rose-400 text-xs font-bold uppercase mb-2 border border-rose-500/30">
            <span>💻</span> Standalone Windows Executable (.EXE)
        </div>
        <h1 class="text-2xl sm:text-3xl font-black text-white">উইন্ডোজ অটো-প্রিন্ট এজেন্ট সফটওয়্যার</h1>
        <p class="text-xs sm:text-sm text-slate-400 mt-1">আপনার দোকানের কম্পিউটারে এই সফটওয়্যারটি ব্যাকগ্রাউন্ডে চালু থাকলেই কাস্টমারের দেওয়া পেইড ফাইল কোনো মানুষের স্পর্শ ছাড়াই প্রিন্টারে অটোমেটিক বের হবে।</p>
    </div>

    <!-- PRIMARY DOWNLOAD CARD -->
    <div class="bg-gradient-to-br from-slate-900 via-slate-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-2xl border border-slate-800 relative overflow-hidden">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-3 max-w-xl">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="text-xs font-bold text-emerald-400 uppercase tracking-wider">ভার্সন v2.6 (রিয়েল-টাইম অটো-সিঙ্ক) • প্রস্তুত ও কম্পাইল্ড</span>
                </div>
                <h2 class="text-xl sm:text-2xl font-black text-white">
                    PrintShebaAgent.exe (সরাসরি রানযোগ্য ফাইল)
                </h2>
                <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                    কোনো Node.js বা জটিল কমান্ড প্রম্পটের ঝামেলা নেই। ফাইলটি ডাউনলোড করে ডাবল ক্লিক করলেই উইন্ডোজের সব প্রিন্টার ডিটেক্ট করে স্বয়ংক্রিয় প্রিন্টিং চালু হয়ে যাবে।
                </p>
                <div class="flex flex-wrap gap-2 pt-1 text-[11px] font-bold text-slate-300">
                    <span class="px-2.5 py-1 rounded-lg bg-slate-800/80 border border-slate-700">✓ সাইজ: মাত্র ৩০ KB</span>
                    <span class="px-2.5 py-1 rounded-lg bg-slate-800/80 border border-slate-700">✓ Windows 7 / 8 / 10 / 11</span>
                    <span class="px-2.5 py-1 rounded-lg bg-slate-800/80 border border-slate-700">✓ সিস্টেম ট্রে সাপোর্ট</span>
                </div>
            </div>

            <!-- Action Download Buttons -->
            <div class="flex flex-col gap-2.5 shrink-0 sm:w-72">
                <a href="{{ route('agent_download_zip_public', $shop->api_token) }}" class="inline-flex items-center justify-center gap-2 px-6 py-4 rounded-2xl bg-gradient-to-r from-emerald-500 via-teal-500 to-emerald-600 hover:from-emerald-600 hover:to-teal-600 text-white font-black text-sm shadow-xl shadow-emerald-500/30 transition-all hover:scale-105 text-center cursor-pointer">
                    <span>📦 সম্পূর্ণ প্যাকেজ জিপ (ZIP) ডাউনলোড</span>
                </a>
                <span class="text-[10px] text-emerald-300 text-center font-bold">⭐ রেকমেন্ডেড (এজেন্ট + সাইলেন্ট প্রিন্টার ইঞ্জিন + রেডি কনফিগ)</span>
                
                <div class="flex items-center gap-2 pt-1">
                    <a href="{{ route('shop.agent_download_exe') }}" class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 font-bold text-[11px] transition-colors text-center cursor-pointer">
                        <span>📥 শুধু .EXE ফাইল</span>
                    </a>
                    <a href="{{ route('shop.agent_download_config') }}" class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 font-bold text-[11px] transition-colors text-center cursor-pointer">
                        <span>⚙️ শুধু config.json</span>
                    </a>
                </div>
                
                <!-- Quick Copy API Token Box -->
                <div class="p-3 rounded-2xl bg-slate-950/90 border border-slate-800 space-y-1.5">
                    <div class="flex items-center justify-between text-[11px] text-slate-400">
                        <span>আপনার দোকানের API Token:</span>
                        <button type="button" onclick="navigator.clipboard.writeText('{{ $shop->api_token }}'); this.innerText='কপি হয়েছে!'; setTimeout(() => this.innerText='কপি করুন', 2000)" class="text-rose-400 hover:text-rose-300 font-bold cursor-pointer text-[10px]">
                            কপি করুন 📋
                        </button>
                    </div>
                    <div class="flex items-center justify-between gap-2 p-1.5 bg-slate-900 rounded-lg border border-slate-800">
                        <code class="text-[11px] font-mono text-rose-300 truncate select-all">{{ $shop->api_token }}</code>
                    </div>
                </div>
            </div>
        </div>

        <div class="absolute -right-10 -bottom-10 w-44 h-44 bg-rose-500/10 rounded-full blur-2xl"></div>
    </div>

    <!-- ANDROID PAYMENT LISTENER APP DOWNLOAD CARD -->
    <div class="bg-gradient-to-br from-emerald-950/40 via-slate-900 to-slate-950 rounded-3xl p-6 sm:p-8 text-white shadow-2xl border border-emerald-500/30 relative overflow-hidden">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-3 max-w-xl">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="text-xs font-bold text-emerald-400 uppercase tracking-wider">অ্যান্ড্রয়েড APK • ভার্সন ২.০ প্রস্তুত</span>
                </div>
                <h2 class="text-xl sm:text-2xl font-black text-white">
                    PrintSheba Payment (বিকাশ ও নগদ অটো-লিসেনার APK)
                </h2>
                <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                    যে ফোনে আপনার বিকাশ বা নগদ পার্সোনাল সিম ঢুকানো আছে, সেই ফোনে এই অ্যাপটি ইনস্টল করে রাখুন। কাস্টমার টাকা পাঠানো মাত্র এসএমএস রিড করে প্রিন্টারে অটোমেটিক প্রিন্ট কমান্ড চলে যাবে।
                </p>
                <div class="flex flex-wrap gap-2 pt-1 text-[11px] font-bold text-slate-300">
                    <span class="px-2.5 py-1 rounded-lg bg-emerald-950/80 border border-emerald-800/80">✓ ১০০% স্বয়ংক্রিয় bKash / Nagad ট্রানজেকশন ম্যাচিং</span>
                    <span class="px-2.5 py-1 rounded-lg bg-emerald-950/80 border border-emerald-800/80">✓ ব্যাকগ্রাউন্ড রানিং</span>
                </div>
            </div>

            <!-- Action Download Buttons -->
            <div class="flex flex-col gap-3 shrink-0">
                <a href="{{ route('shop.agent_download_apk') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white font-black text-sm shadow-xl shadow-emerald-500/25 transition-all hover:scale-105 text-center cursor-pointer">
                    <span>📱 ডাউনলোড করুন Android APK</span>
                </a>
                <div class="p-3.5 rounded-2xl bg-slate-950/90 border border-emerald-500/30 text-[11px] space-y-2">
                    <div class="text-slate-400 font-bold">অ্যাপের সেটিংস এ এই তথ্যগুলো দিন:</div>
                    <div class="flex items-center justify-between gap-2 p-1.5 bg-slate-900 rounded-lg border border-slate-800">
                        <span class="font-mono text-emerald-400 text-[10px] truncate">URL: {{ url('/') }}</span>
                        <button type="button" onclick="navigator.clipboard.writeText('{{ url('/') }}'); this.innerText='কপি!'; setTimeout(() => this.innerText='কপি', 2000)" class="text-emerald-400 hover:text-emerald-300 font-bold cursor-pointer text-[10px] shrink-0">
                            কপি
                        </button>
                    </div>
                    <div class="flex items-center justify-between gap-2 p-1.5 bg-slate-900 rounded-lg border border-slate-800">
                        <span class="font-mono text-emerald-400 text-[10px] truncate max-w-[140px]">Token: {{ $shop->api_token }}</span>
                        <button type="button" onclick="navigator.clipboard.writeText('{{ $shop->api_token }}'); this.innerText='কপি!'; setTimeout(() => this.innerText='কপি', 2000)" class="text-emerald-400 hover:text-emerald-300 font-bold cursor-pointer text-[10px] shrink-0">
                            কপি
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3 STEP SETUP INSTRUCTIONS (WINDOWS AGENT) -->
    <div class="bg-slate-900/80 rounded-3xl p-6 sm:p-8 border border-slate-800 shadow-xl space-y-6">
        <div class="flex items-center justify-between border-b border-slate-800 pb-4">
            <div>
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <span>💻 উইন্ডোজ এজেন্ট (.EXE) সেটআপ গাইড</span>
                </h3>
                <p class="text-xs text-slate-400">কম্পিউটারে কোনো কিছু ইনস্টল ছাড়াই ২ মিনিটে স্বয়ংক্রিয় প্রিন্টিং চালু করুন</p>
            </div>
            <span class="px-3 py-1 rounded-full bg-rose-500/10 border border-rose-500/20 text-rose-400 font-bold text-xs">Windows 7/8/10/11</span>
        </div>

        <div class="grid md:grid-cols-3 gap-6">
            <!-- Step 1 -->
            <div class="bg-slate-950 rounded-2xl p-5 border border-slate-800 space-y-3">
                <span class="w-8 h-8 rounded-xl bg-emerald-500 text-white font-black text-sm flex items-center justify-center">১</span>
                <h4 class="text-sm font-black text-white">সম্পূর্ণ প্যাকেজ (ZIP) ডাউনলোড করুন</h4>
                <p class="text-xs text-slate-400 leading-relaxed">
                    উপরের সবুজ <strong>"📦 সম্পূর্ণ প্যাকেজ জিপ (ZIP) ডাউনলোড"</strong> বাটনে ক্লিক করে ফাইলটি ডাউনলোড করুন এবং রাইট ক্লিক করে <strong>Extract / আনজিপ</strong> করুন। এতে এজেন্ট, সাইলেন্ট প্রিন্টার ইঞ্জিন এবং আপনার দোকানের টোকেনসহ কনফিগ ফাইল একসাথে পেয়ে যাবেন।
                </p>
            </div>

            <!-- Step 2 -->
            <div class="bg-slate-950 rounded-2xl p-5 border border-slate-800 space-y-3">
                <span class="w-8 h-8 rounded-xl bg-purple-600 text-white font-black text-sm flex items-center justify-center">২</span>
                <h4 class="text-sm font-black text-white">সফটওয়্যারটি ওপেন করুন</h4>
                <p class="text-xs text-slate-400 leading-relaxed">
                    <strong>PrintShebaAgent.exe</strong> ফাইলে ডাবল ক্লিক করে রান করুন। 
                    <span class="block mt-1.5 p-2 rounded-lg bg-amber-500/10 border border-amber-500/20 text-amber-300 text-[11px]">
                        💡 <em>উইন্ডোজ স্ক্রিনে <strong>"Windows protected your PC"</strong> আসলে নিচে থাকা <strong>"Run anyway"</strong> বাটনে একবার ক্লিক করলেই ওপেন হয়ে যাবে।</em>
                    </span>
                    এরপর আপনার Canon/HP/Epson প্রিন্টারটি সিলেক্ট করে <strong>"💾 সেটিংস সেভ করুন"</strong> বাটনে ক্লিক করুন।
                </p>
            </div>

            <!-- Step 3 -->
            <div class="bg-slate-950 rounded-2xl p-5 border border-slate-800 space-y-3">
                <span class="w-8 h-8 rounded-xl bg-emerald-500 text-white font-black text-sm flex items-center justify-center">৩</span>
                <h4 class="text-sm font-black text-white">টেস্ট প্রিন্ট পাঠান ও মিনিমাইজ করুন</h4>
                <p class="text-xs text-slate-400 leading-relaxed">
                    <strong>"🖨️ টেস্ট পেজ প্রিন্ট করুন"</strong> বাটনে চাপ দিয়ে টেস্ট পেজ বের করুন। এরপর মিনিমাইজ করে রাখুন—সফটওয়্যারটি ঘড়ির পাশে ব্যাকগ্রাউন্ডে নীরবে কাজ করতে থাকবে।
                </p>
            </div>
        </div>
    </div>

    <!-- ANDROID APP DETAILED SETUP INSTRUCTIONS -->
    <div class="bg-slate-900/80 rounded-3xl p-6 sm:p-8 border border-emerald-500/30 shadow-xl space-y-6">
        <div class="flex items-center justify-between border-b border-slate-800 pb-4">
            <div>
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <span>📱 অ্যান্ড্রয়েড পেমেন্ট লিসেনার (APK) সেটআপ গাইড</span>
                </h3>
                <p class="text-xs text-slate-400">যে ফোনে আপনার বিকাশ ও নগদ পার্সোনাল সিম রয়েছে, সেই ফোনে এটি চালু রাখুন</p>
            </div>
            <span class="px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 font-bold text-xs">Android 7.0 - 14+</span>
        </div>

        <div class="grid md:grid-cols-3 gap-6">
            <!-- App Step 1 -->
            <div class="bg-slate-950 rounded-2xl p-5 border border-slate-800 space-y-3">
                <span class="w-8 h-8 rounded-xl bg-emerald-500 text-white font-black text-sm flex items-center justify-center">১</span>
                <h4 class="text-sm font-black text-white">APK ইনস্টল করুন</h4>
                <p class="text-xs text-slate-400 leading-relaxed">
                    উপরের <strong>"📱 ডাউনলোড করুন Android APK"</strong> বাটনে ক্লিক করে ফোনে অ্যাপটি নামিয়ে ওপেন করুন। ফোন থেকে Unknown Sources বা Install Unknown Apps পারমিশনটি অন করে ইনস্টল সম্পন্ন করুন।
                </p>
            </div>

            <!-- App Step 2 -->
            <div class="bg-slate-950 rounded-2xl p-5 border border-slate-800 space-y-3">
                <span class="w-8 h-8 rounded-xl bg-teal-500 text-white font-black text-sm flex items-center justify-center">২</span>
                <h4 class="text-sm font-black text-white">নোটিফিকেশন অ্যাক্সেস চালু করুন</h4>
                <p class="text-xs text-slate-400 leading-relaxed">
                    অ্যাপে ঢুকলে <strong>"🔔 নোটিফিকেশন অ্যাক্সেস চালু করুন"</strong> দেখতে পাবেন। বাটনে চাপ দিয়ে তালিকায় <strong>PrintSheba Payment Listener</strong> চালু করে দিন। এতে কোনো ঝুঁকিপূর্ণ এসএমএস পারমিশন ছাড়াই বিকাশ ও নগদ ১০০% নিরাপদে কাজ করবে এবং কোনো ব্যাংকিং অ্যাপে কোনো ক্ষতিকর সতর্কতা আসবে না।
                </p>
            </div>

            <!-- App Step 3 -->
            <div class="bg-slate-950 rounded-2xl p-5 border border-slate-800 space-y-3">
                <span class="w-8 h-8 rounded-xl bg-cyan-500 text-white font-black text-sm flex items-center justify-center">৩</span>
                <h4 class="text-sm font-black text-white">টোকেন দিয়ে সংযোগ যাচাই করুন</h4>
                <p class="text-xs text-slate-400 leading-relaxed">
                    অ্যাপের সার্ভার URL বক্সে <code class="text-emerald-400 font-mono">{{ url('/') }}</code> এবং API Token বক্সে আপনার শপ টোকেনটি বসিয়ে <strong>"সংযোগ যাচাই"</strong> ও <strong>"সেটিংস সেভ"</strong> এ চাপ দিন। ব্যস, কাজ শেষ!
                </p>
            </div>
        </div>
    </div>

    <!-- ADVANCED FEATURES CHECKLIST -->
    <div class="bg-slate-900/80 rounded-3xl p-6 sm:p-8 border border-slate-800 shadow-xl space-y-4">
        <h3 class="text-base font-bold text-white uppercase tracking-wider">
            ✨ এজেন্ট সফটওয়্যারের বিল্ট-ইন এডভান্সড সুবিধাসমূহ
        </h3>

        <div class="grid sm:grid-cols-2 gap-4 text-xs font-semibold text-slate-300">
            <div class="p-4 rounded-xl bg-slate-950 border border-slate-800 flex items-start gap-3">
                <span class="text-rose-400 text-base">🖨️</span>
                <div>
                    <div class="font-bold text-white">কালার ও সাদাকালো অটো-রাউটিং</div>
                    <div class="text-[11px] text-slate-400 mt-0.5">কাস্টমার কালার প্রিন্ট দিলে কালার প্রিন্টারে এবং সাদাকালো দিলে লেজার প্রিন্টারে নিজে নিজে চলে যাবে।</div>
                </div>
            </div>

            <div class="p-4 rounded-xl bg-slate-950 border border-slate-800 flex items-start gap-3">
                <span class="text-emerald-400 text-base">🛡️</span>
                <div>
                    <div class="font-bold text-white">১০০% কাস্টমার গোপনীয়তা (Zero-Retention)</div>
                    <div class="text-[11px] text-slate-400 mt-0.5">প্রিন্টারে পেপার যাওয়ার সাথে সাথেই লোকাল মেমোরি থেকে কাস্টমারের ফাইল নিরাপদভাবে ডিলিট হয়ে যায়।</div>
                </div>
            </div>

            <div class="p-4 rounded-xl bg-slate-950 border border-slate-800 flex items-start gap-3">
                <span class="text-blue-400 text-base">⚡</span>
                <div>
                    <div class="font-bold text-white">উইন্ডোজ অটো-স্টার্ট সুবিধা</div>
                    <div class="text-[11px] text-slate-400 mt-0.5">কম্পিউটার অন করলেই নিজে থেকে চালু হয়ে যাবে, বারবার ওপেন করার ঝামেলা নেই।</div>
                </div>
            </div>

            <div class="p-4 rounded-xl bg-slate-950 border border-slate-800 flex items-start gap-3">
                <span class="text-purple-400 text-base">🔔</span>
                <div>
                    <div class="font-bold text-white">সিস্টেম ট্রে ব্যাকগ্রাউন্ড সার্ভিস</div>
                    <div class="text-[11px] text-slate-400 mt-0.5">টাস্কবারে ভিড় করবে না, মিনিমাইজ করলে ঘড়ির পাশে সিস্টেম ট্রেতে লুকিয়ে সচল থাকবে।</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
