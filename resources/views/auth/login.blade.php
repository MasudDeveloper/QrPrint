<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>লগইন – PrintSheba (প্রিন্ট সেবা)</title>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Hind Siliguri', sans-serif;
            background-color: #fafafa;
            background-image: radial-gradient(#d1d5db 1.2px, transparent 1.2px);
            background-size: 24px 24px;
        }
        @keyframes floatIcon {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-7px) rotate(-3deg); }
        }
        @keyframes laserScan {
            0% { top: 15%; opacity: 0.2; }
            50% { top: 80%; opacity: 1; }
            100% { top: 15%; opacity: 0.2; }
        }
        .anim-float { animation: floatIcon 3.6s ease-in-out infinite; }
        .anim-laser {
            position: absolute;
            left: 15%;
            width: 70%;
            height: 2px;
            background: linear-gradient(90deg, transparent, #f43f5e, #ef4444, transparent);
            box-shadow: 0 0 10px 2px rgba(244, 63, 94, 0.8);
            animation: laserScan 2s ease-in-out infinite;
        }
    </style>
    <link rel="icon" type="image/svg+xml" href="{{ asset('logo.svg') }}">
</head>
<body class="min-h-screen flex flex-col justify-center items-center p-4">
    <div class="w-full max-w-md">
        <!-- Brand Header with Animated Illustration -->
        <div class="text-center mb-6">
            <a href="/" class="inline-flex items-center gap-3 group">
                <img src="{{ asset('logo.svg') }}" alt="PrintSheba Logo" class="w-12 h-12 rounded-2xl shadow-lg group-hover:scale-105 transition-transform duration-300">
                <div class="text-left">
                    <div class="text-2xl font-black text-slate-900 font-en tracking-tight">Print<span class="text-rose-500">Sheba</span></div>
                    <span class="text-[10px] tracking-widest text-slate-400 font-bold block uppercase -mt-1">সাইবার ক্যাফে অটোমেশন</span>
                </div>
            </a>
            <h1 class="text-2xl font-black text-slate-900 mt-5">দোকানদার / এডমিন লগইন</h1>
            <p class="text-xs text-slate-500 mt-1">আপনার ড্যাশবোর্ডে প্রবেশ করতে ইমেইল ও পাসওয়ার্ড দিন</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-xl shadow-slate-200/50">
            @if(session('info'))
                <div class="mb-5 p-3 rounded-xl bg-blue-50 border border-blue-200 text-blue-700 text-xs font-semibold">
                    {{ session('info') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-5 p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-semibold">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">ইমেইল ঠিকানা</label>
                    <input type="email" name="email" value="{{ old('email', 'masud.info@gmail.com') }}" required 
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20 transition-all"
                        placeholder="yourname@gmail.com">
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs font-bold text-slate-700">পাসওয়ার্ড</label>
                        <span class="text-[11px] text-slate-400">ডিফল্ট: password</span>
                    </div>
                    <input type="password" name="password" value="password" required 
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20 transition-all"
                        placeholder="••••••••">
                </div>

                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center gap-2 cursor-pointer text-slate-600">
                        <input type="checkbox" name="remember" class="rounded accent-rose-500">
                        <span>মনে রাখুন</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-3.5 px-6 rounded-xl bg-rose-500 hover:bg-rose-600 text-white font-bold text-sm shadow-lg shadow-rose-500/25 transition-all hover:scale-[1.01]">
                    লগইন করুন →
                </button>
            </form>

            <div class="mt-6 pt-6 border-t border-slate-100 text-center text-xs text-slate-500">
                নতুন দোকানদার? 
                <a href="{{ route('register') }}" class="font-bold text-rose-600 hover:underline">নতুন দোকান রেজিস্টার করুন</a>
            </div>
        </div>

        <!-- Quick Demo Switcher Info -->
        <div class="mt-6 p-4 rounded-2xl bg-white/80 backdrop-blur border border-slate-200 text-xs text-slate-600 text-center">
            <span class="font-bold text-slate-800">টেস্ট লগইন তথ্য:</span><br>
            সুপার এডমিন: <span class="font-mono text-rose-600 font-bold">masud.info@gmail.com</span> | পাসওয়ার্ড: <span class="font-mono">password</span><br>
            দোকানদার ডেমো: <span class="font-mono text-purple-600 font-bold">shop@printsheba.com</span> | পাসওয়ার্ড: <span class="font-mono">password</span>
        </div>
    </div>
</body>
</html>
