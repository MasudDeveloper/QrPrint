<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>দোকান রেজিস্ট্রেশন – PrintSheba (প্রিন্ট সেবা)</title>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Hind Siliguri', sans-serif;
            background-color: #fafafa;
            background-image: radial-gradient(#d1d5db 1.2px, transparent 1.2px);
            background-size: 24px 24px;
        }
        @keyframes rocketFloat {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-8px) rotate(4deg); }
        }
        .anim-rocket { animation: rocketFloat 3.2s ease-in-out infinite; }
    </style>
    <link rel="icon" type="image/svg+xml" href="{{ asset('logo.svg') }}">
</head>
<body class="min-h-screen flex flex-col justify-center items-center p-4">
    <div class="w-full max-w-lg">
        <!-- Brand Header with Animated Rocket Badge -->
        <div class="text-center mb-6">
            <a href="/" class="inline-flex items-center gap-3 group">
                <img src="{{ asset('logo.svg') }}" alt="PrintSheba Logo" class="w-12 h-12 rounded-2xl shadow-lg group-hover:scale-105 transition-transform duration-300">
                <div class="text-left">
                    <div class="text-2xl font-black text-slate-900 font-en tracking-tight">Print<span class="text-rose-500">Sheba</span></div>
                    <span class="text-[10px] tracking-widest text-slate-400 font-bold block uppercase -mt-1">সাইবার ক্যাফে অটোমেশন</span>
                </div>
            </a>
            <h1 class="text-2xl font-black text-slate-900 mt-5">নতুন দোকান রেজিস্ট্রেশন</h1>
            <p class="text-xs text-slate-500 mt-1">মাত্র ১ মিনিটে অ্যাকাউন্ট খুলে আপনার দোকানের কিউআর কোড তৈরি করুন</p>
        </div>

        <!-- Registration Card -->
        <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-xl shadow-slate-200/50">
            @if($errors->any())
                <div class="mb-5 p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-semibold">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">আপনার নাম</label>
                        <input type="text" name="name" value="{{ old('name') }}" required 
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20"
                            placeholder="মো: রহিম">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">দোকানের নাম</label>
                        <input type="text" name="shop_name" value="{{ old('shop_name') }}" required 
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20"
                            placeholder="রহিম কম্পিউটার্স & ফটোকপি">
                    </div>
                </div>

                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">মোবাইল / বিকাশ নম্বর</label>
                        <input type="tel" name="phone" value="{{ old('phone') }}" required 
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20"
                            placeholder="017XXXXXXXX">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">ইমেইল ঠিকানা</label>
                        <input type="email" name="email" value="{{ old('email') }}" required 
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20"
                            placeholder="shop@gmail.com">
                    </div>
                </div>

                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">পাসওয়ার্ড</label>
                        <input type="password" name="password" required 
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20"
                            placeholder="কমপক্ষে ৬ অক্ষর">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">পাসওয়ার্ড নিশ্চিত করুন</label>
                        <input type="password" name="password_confirmation" required 
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20"
                            placeholder="পুনরায় পাসওয়ার্ড লিখুন">
                    </div>
                </div>

                <button type="submit" class="w-full py-3.5 px-6 rounded-xl bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white font-bold text-sm shadow-lg shadow-rose-500/25 transition-all hover:scale-[1.01] mt-2">
                    দোকান চালু করুন (ফ্রি ট্রায়াল) →
                </button>
            </form>

            <div class="mt-6 pt-6 border-t border-slate-100 text-center text-xs text-slate-500">
                ইতিমধ্যে একাউন্ট আছে? 
                <a href="{{ route('login') }}" class="font-bold text-rose-600 hover:underline">লগইন করুন</a>
            </div>
        </div>
    </div>
</body>
</html>
