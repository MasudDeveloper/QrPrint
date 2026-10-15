@extends('layouts.admin')

@section('title', 'হোমপেজ ভিডিও ও ওয়েবসাইট সেটিংস')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    
    <div>
        <h1 class="text-2xl font-black text-white flex items-center gap-2">
            <span>🎬 হোমপেজ ভিডিও ও গ্লোবাল সেটিংস</span>
        </h1>
        <p class="text-xs sm:text-sm text-slate-400 mt-1">
            এখানে আপনার ইউটিউব বা ভিডিও লিংক পেস্ট করলেই সেটি স্বয়ংক্রিয়ভাবে হোমপেজে প্রদর্শিত হবে। ফাঁকা রাখলে ভিডিও সেকশনটি হাইড থাকবে।
        </p>
    </div>

    <form action="{{ route('admin.settings.update') }}" method="POST" class="bg-slate-900/80 rounded-3xl p-6 sm:p-8 border border-slate-800 shadow-xl space-y-6">
        @csrf

        <!-- Homepage Video Box -->
        <div class="p-6 rounded-2xl bg-slate-950 border border-slate-800 space-y-4">
            <div class="flex items-center gap-2">
                <span class="text-lg">📹</span>
                <div>
                    <h3 class="text-sm font-bold text-white">হোমপেজ মার্কেটিং / ডেমো ভিডিও URL</h3>
                    <p class="text-[11px] text-slate-400">YouTube, Vimeo বা যেকোনো ভিডিও লিংক পেস্ট করুন</p>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 mb-1.5">ভিডিও লিংক (Video URL):</label>
                <input type="text" name="homepage_video_url" value="{{ old('homepage_video_url', $videoUrl) }}" 
                    class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-800 text-xs sm:text-sm font-mono text-white focus:outline-none focus:border-rose-500"
                    placeholder="https://www.youtube.com/watch?v=XXXXXX বা https://youtu.be/XXXXXX">
                <span class="text-[11px] text-slate-500 mt-1 block">💡 নোট: লিংক ফাঁকা রাখলে হোমপেজে কোনো ভিডিও সেকশন দেখাবে না। লিংক দিলে স্বয়ংক্রিয়ভাবে ভিডিও প্লেয়ার চলে আসবে।</span>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 mb-1.5">ভিডিওর শিরোনাম (Video Title):</label>
                <input type="text" name="homepage_video_title" value="{{ old('homepage_video_title', $videoTitle) }}" 
                    class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-800 text-xs sm:text-sm text-white focus:outline-none focus:border-rose-500"
                    placeholder="কীভাবে ২ মিনিটে কাস্টমার নিজে নিজে প্রিন্ট করবে — লাইভ ভিডিও ডেমো">
            </div>

            <!-- Current Video Preview if exists -->
            @if(!empty($videoUrl))
                @php
                    $previewUrl = $videoUrl;
                    if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $videoUrl, $m)) {
                        $previewUrl = 'https://www.youtube.com/embed/' . $m[1];
                    }
                @endphp
                <div class="pt-4 border-t border-slate-800">
                    <span class="text-xs font-bold text-slate-300 block mb-2">বর্তমান ভিডিও প্রিভিউ:</span>
                    <div class="aspect-video max-w-md rounded-2xl overflow-hidden border border-slate-800 shadow-md bg-black">
                        <iframe class="w-full h-full" src="{{ $previewUrl }}" frameborder="0" allowfullscreen></iframe>
                    </div>
                </div>
            @endif
        </div>

        <!-- Super Admin Subscription Payment Account Numbers -->
        <div class="p-6 rounded-2xl bg-slate-950 border border-slate-800 space-y-4">
            <div class="flex items-center gap-2">
                <span class="text-lg">💳</span>
                <div>
                    <h3 class="text-sm font-bold text-white">দোকানদারদের সাবস্ক্রিপশন ফি গ্রহণের নম্বর (bKash & Nagad)</h3>
                    <p class="text-[11px] text-slate-400">দোকানদাররা প্যাকেজ কেনার সময় এই নম্বরে টাকা পাঠাবে</p>
                </div>
            </div>

            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">সুপার এডমিন বিকাশ নম্বর (bKash):</label>
                    <input type="text" name="admin_bkash_number" value="{{ old('admin_bkash_number', $adminBkash ?? '') }}" 
                        class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-800 text-xs sm:text-sm font-mono text-white focus:outline-none focus:border-rose-500"
                        placeholder="017xxxxxxxx (Personal/Merchant)">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">সুপার এডমিন নগদ নম্বর (Nagad):</label>
                    <input type="text" name="admin_nagad_number" value="{{ old('admin_nagad_number', $adminNagad ?? '') }}" 
                        class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-800 text-xs sm:text-sm font-mono text-white focus:outline-none focus:border-rose-500"
                        placeholder="017xxxxxxxx (Personal/Merchant)">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 mb-1.5">দোকানদারদের জন্য পেমেন্ট নির্দেশিকা (ঐচ্ছিক):</label>
                <textarea name="admin_payment_instructions" rows="2" 
                    class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-slate-800 text-xs text-white focus:outline-none focus:border-rose-500"
                    placeholder="উদাঃ 'সেন্ড মানি করার পর ট্রানজেকশন আইডি ও আপনার সেন্ডার নম্বর দিয়ে সাবমিট করুন।'">{{ old('admin_payment_instructions', $paymentInstructions ?? '') }}</textarea>
            </div>
        </div>

        <div class="pt-4 flex justify-end">
            <button type="submit" class="px-8 py-3.5 rounded-xl bg-gradient-to-r from-rose-600 to-rose-700 hover:from-rose-500 hover:to-rose-600 text-white font-bold text-sm shadow-lg shadow-rose-600/30 transition-all cursor-pointer">
                💾 সেটিংস সেভ করুন
            </button>
        </div>
    </form>
</div>
@endsection
