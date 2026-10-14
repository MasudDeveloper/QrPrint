<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>দোকান সাময়িকভাবে বন্ধ – PrintSheba</title>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Hind Siliguri', sans-serif; background-color: #fafafa; }
    </style>
</head>
<body class="min-h-screen flex flex-col justify-center items-center p-4 text-center">
    <div class="max-w-md w-full bg-white p-8 rounded-3xl border border-slate-200 shadow-xl space-y-4">
        <div class="w-16 h-16 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center mx-auto text-2xl font-bold">
            ⏸️
        </div>
        <h1 class="text-xl font-black text-slate-900">{{ $shop->name }}</h1>
        <p class="text-xs text-slate-500 leading-relaxed">
            এই দোকানের সেলফ-সার্ভিস প্রিন্ট সিস্টেম বর্তমানে সাময়িকভাবে বন্ধ রয়েছে। অনুগ্রহ করে দোকানদারের সাথে সরাসরি কাউন্টারে যোগাযোগ করুন।
        </p>
        @if($shop->phone)
            <div class="pt-2">
                <a href="tel:{{ $shop->phone }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-slate-900 text-white font-bold text-xs">
                    📞 দোকানদারকে কল দিন: {{ $shop->phone }}
                </a>
            </div>
        @endif
    </div>
</body>
</html>
