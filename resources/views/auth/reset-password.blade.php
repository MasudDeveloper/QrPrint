<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>নতুন পাসওয়ার্ড সেট করুন – PrintSheba (প্রিন্ট সেবা)</title>
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
            <h1 class="text-2xl font-black text-slate-900 mt-5">নতুন পাসওয়ার্ড সেট করুন</h1>
            <p class="text-xs text-slate-500 mt-1">আপনার একাউন্টের জন্য একটি নতুন পাসওয়ার্ড নির্ধারণ করুন</p>
        </div>

        <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-xl shadow-slate-200/50 space-y-5">
            @if($errors->any())
                <div class="p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold space-y-1">
                    @foreach($errors->all() as $error)
                        <div>• {{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('password.update') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">ইমেইল ঠিকানা</label>
                    <input type="email" name="email" value="{{ $email ?? old('email') }}" required readonly
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-slate-600 text-sm focus:outline-none cursor-not-allowed">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">নতুন পাসওয়ার্ড</label>
                    <input type="password" name="password" required autofocus
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20 transition-all"
                        placeholder="কমপক্ষে ৬ অক্ষরের পাসওয়ার্ড">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">পাসওয়ার্ড নিশ্চিত করুন</label>
                    <input type="password" name="password_confirmation" required 
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20 transition-all"
                        placeholder="পাসওয়ার্ডটি আবার লিখুন">
                </div>

                <button type="submit" class="w-full py-3.5 px-6 rounded-xl bg-rose-500 hover:bg-rose-600 text-white font-bold text-sm shadow-lg shadow-rose-500/25 transition-all hover:scale-[1.01] cursor-pointer">
                    পাসওয়ার্ড পরিবর্তন করুন →
                </button>
            </form>
        </div>
    </div>
</body>
</html>
