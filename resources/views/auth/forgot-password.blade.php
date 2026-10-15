<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>পাসওয়ার্ড রিসেট – PrintSheba (প্রিন্ট সেবা)</title>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Hind Siliguri', sans-serif;
            background-color: #fafafa;
            background-image: radial-gradient(#d1d5db 1.2px, transparent 1.2px);
            background-size: 24px 24px;
        }
    </style>
    <link rel="icon" type="image/svg+xml" href="{{ asset('logo.svg') }}">
</head>
<body class="min-h-screen flex flex-col justify-center items-center p-4">
    <div class="w-full max-w-md">
        <!-- Brand Header -->
        <div class="text-center mb-6">
            <a href="/" class="inline-flex items-center gap-3 group">
                <img src="{{ asset('logo.svg') }}" alt="PrintSheba Logo" class="w-12 h-12 rounded-2xl shadow-lg group-hover:scale-105 transition-transform duration-300">
                <div class="text-left">
                    <div class="text-2xl font-black text-slate-900 font-en tracking-tight">Print<span class="text-rose-500">Sheba</span></div>
                    <span class="text-[10px] tracking-widest text-slate-400 font-bold block uppercase -mt-1">সাইবার ক্যাফে অটোমেশন</span>
                </div>
            </a>
            <h1 class="text-2xl font-black text-slate-900 mt-5">পাসওয়ার্ড পুনরুদ্ধার</h1>
            <p class="text-xs text-slate-500 mt-1">আপনার একাউন্টের ইমেইল দিন, আমরা রিসেট লিঙ্ক পাঠাব</p>
        </div>

        <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-xl shadow-slate-200/50 space-y-5">
            @if(session('status'))
                <div class="p-3.5 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center gap-2">
                    <span>✅</span>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold space-y-1">
                    @foreach($errors->all() as $error)
                        <div>• {{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('password.email') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">আপনার রেজিস্টার্ড ইমেইল ঠিকানা</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20 transition-all"
                        placeholder="yourname@gmail.com">
                </div>

                <button type="submit" class="w-full py-3.5 px-6 rounded-xl bg-rose-500 hover:bg-rose-600 text-white font-bold text-sm shadow-lg shadow-rose-500/25 transition-all hover:scale-[1.01] cursor-pointer">
                    রিসেট লিঙ্ক পাঠান →
                </button>
            </form>

            <div class="pt-4 border-t border-slate-100 text-center text-xs">
                <a href="{{ route('login') }}" class="font-bold text-slate-600 hover:text-rose-600 transition flex items-center justify-center gap-1">
                    <span>←</span> <span>লগইন পেজে ফিরে যান</span>
                </a>
            </div>
        </div>
    </div>
</body>
</html>
