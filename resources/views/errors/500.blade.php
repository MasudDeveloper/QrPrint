<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>সার্ভার এরর (500) – PrintSheba</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Hind Siliguri', sans-serif; }
        .font-en { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full text-center space-y-6">
        <a href="/" class="inline-flex items-center gap-3 group">
            <img src="{{ asset('logo.svg') }}" alt="PrintSheba Logo" class="w-12 h-12 rounded-2xl shadow-lg">
            <span class="text-2xl font-black text-white font-en tracking-tight">Print<span class="text-rose-500">Sheba</span></span>
        </a>

        <div class="p-8 rounded-3xl bg-slate-900 border border-slate-800 shadow-2xl space-y-4">
            <div class="text-7xl font-black font-en text-transparent bg-clip-text bg-gradient-to-r from-amber-500 to-rose-500">
                500
            </div>
            <h1 class="text-xl font-black text-white">
                সার্ভারে সাময়িক সমস্যা হয়েছে!
            </h1>
            <p class="text-xs text-slate-400 leading-relaxed">
                আমাদের অটোমেশন ক্র্যাশ মনিটর সমস্যাটি স্বয়ংক্রিয়ভাবে রেকর্ড করেছে। ইঞ্জিনিয়ার টিম খুব দ্রুত এটি পর্যবেক্ষণ ও সমাধান করবে।
            </p>

            <div class="pt-4 flex items-center justify-center gap-3">
                <a href="/" class="px-6 py-3 rounded-full text-xs font-bold bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white shadow-lg shadow-rose-500/20 transition-all hover:scale-105">
                    🏠 হোমপেজে ফিরে যান
                </a>
            </div>
        </div>

        <div class="text-[11px] text-slate-500">
            © ২০২৬ PrintSheba • স্মার্ট অটো-প্রিন্ট সলিউশন
        </div>
    </div>
</body>
</html>

