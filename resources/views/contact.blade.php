<!DOCTYPE html>
<html lang="bn" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>যোগাযোগ – PrintSheba (প্রিন্ট সেবা) | আমাদের সাথে যোগাযোগ করুন</title>
    <meta name="description" content="PrintSheba কাস্টমার সাপোর্ট ও সেলস টিম। হটলাইন: 01644416378, হোয়াটসঅ্যাপ ও ইমেইলে সরাসরি কথা বলুন।">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#e11d48',
                        brandpink: '#f43f5e',
                        brandpurple: '#7c3aed',
                        brandcyan: '#06b6d4',
                        brandgreen: '#10b981',
                        darknavy: '#0f172a',
                    },
                    fontFamily: {
                        sans: ['"Hind Siliguri"', '"Plus Jakarta Sans"', 'sans-serif'],
                        en: ['"Plus Jakarta Sans"', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        body {
            background-color: #fafafa;
            color: #0f172a;
            font-family: 'Hind Siliguri', sans-serif;
            background-image: radial-gradient(#d1d5db 1.2px, transparent 1.2px);
            background-size: 24px 24px;
        }

        /* Pulse badge */
        @keyframes ringPulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.1); opacity: 0.85; filter: drop-shadow(0 0 10px rgba(244, 63, 94, 0.6)); }
        }
        .anim-ring {
            animation: ringPulse 2.2s ease-in-out infinite;
        }

        /* Location pin bounce */
        @keyframes pinBounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }
        .anim-pin {
            animation: pinBounce 2s ease-in-out infinite;
        }
    </style>
</head>
<body class="selection:bg-pink-500 selection:text-white">

    <!-- Top Navbar -->
    @include('partials.header')

    <!-- HERO HEADER -->
    <section class="relative pt-14 pb-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-b from-white via-rose-50/20 to-slate-50 border-b border-slate-200">
        <div class="max-w-4xl mx-auto text-center space-y-4">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-rose-100 border border-rose-200 text-rose-700 text-xs font-extrabold uppercase tracking-wider">
                <span class="anim-ring">📞</span> সরাসরি যোগাযোগ ও সাপোর্ট
            </div>
            
            <h1 class="text-3xl sm:text-5xl font-black text-slate-900 tracking-tight leading-tight">
                আমরা আপনার সহায়তায় <span class="bg-gradient-to-r from-rose-600 to-pink-500 bg-clip-text text-transparent">সর্বদা প্রস্তুত</span>
            </h1>

            <p class="text-base sm:text-lg text-slate-600 max-w-2xl mx-auto leading-relaxed font-medium">
                আপনার সাইবার ক্যাফে বা প্রিন্টিং শপে PrintSheba সেটআপ করতে কোনো সাহায্য প্রয়োজন? কল করুন অথবা সরাসরি মেসেজ দিন।
            </p>
        </div>
    </section>

    <!-- CONTACT CARDS + FORM SECTION -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="grid lg:grid-cols-12 gap-12 items-start">
                
                <!-- Left 5 Cols: Contact Details & Direct Action Cards -->
                <div class="lg:col-span-5 space-y-6">
                    <div>
                        <h2 class="text-2xl font-black text-slate-900 mb-2">সরাসরি যোগাযোগ করুন</h2>
                        <p class="text-sm text-slate-600 leading-relaxed">
                            যেকোনো প্রয়োজনে আমাদের সাপোর্ট টিম প্রতিদিন সকাল ৯:০০ টা থেকে রাত ১১:০০ টা পর্যন্ত লাইভ সহায়তা দিয়ে থাকে।
                        </p>
                    </div>

                    <!-- Direct Card 1: Phone Hotline -->
                    <a href="tel:01644416378" class="flex items-center gap-4 p-5 rounded-2xl bg-gradient-to-r from-rose-50 to-pink-50/50 border-2 border-rose-200 hover:border-rose-400 transition-all group shadow-sm hover:shadow-md">
                        <div class="w-13 h-13 rounded-2xl bg-rose-500 text-white flex items-center justify-center text-xl shrink-0 p-3 shadow-md group-hover:scale-110 transition-transform">
                            📞
                        </div>
                        <div>
                            <div class="text-[11px] font-black text-rose-700 uppercase tracking-wider">হটলাইন ও সরাসরি কল</div>
                            <div class="text-lg font-black text-slate-900 font-mono group-hover:text-rose-600 transition-colors">01644416378</div>
                            <div class="text-xs text-slate-500">তাৎক্ষণিক কাস্টমার কেয়ার</div>
                        </div>
                    </a>

                    <!-- Direct Card 2: WhatsApp Direct -->
                    <a href="https://wa.me/8801644416378" target="_blank" class="flex items-center gap-4 p-5 rounded-2xl bg-gradient-to-r from-emerald-50 to-teal-50/50 border-2 border-emerald-200 hover:border-emerald-400 transition-all group shadow-sm hover:shadow-md">
                        <div class="w-13 h-13 rounded-2xl bg-emerald-500 text-white flex items-center justify-center text-xl shrink-0 p-3 shadow-md group-hover:scale-110 transition-transform">
                            💬
                        </div>
                        <div>
                            <div class="text-[11px] font-black text-emerald-700 uppercase tracking-wider">হোয়াটসঅ্যাপ চ্যাট</div>
                            <div class="text-lg font-black text-slate-900 font-mono group-hover:text-emerald-600 transition-colors">01644416378</div>
                            <div class="text-xs text-slate-500">ক্লিক করে চ্যাট শুরু করুন</div>
                        </div>
                    </a>

                    <!-- Direct Card 3: Email Support -->
                    <a href="mailto:masud.info@gmail.com" class="flex items-center gap-4 p-5 rounded-2xl bg-slate-50 border border-slate-200 hover:border-slate-300 transition-all group shadow-sm">
                        <div class="w-13 h-13 rounded-2xl bg-slate-800 text-white flex items-center justify-center text-xl shrink-0 p-3 shadow-md group-hover:scale-110 transition-transform">
                            ✉️
                        </div>
                        <div>
                            <div class="text-[11px] font-black text-slate-500 uppercase tracking-wider">অফিসিয়াল ইমেইল</div>
                            <div class="text-base font-bold text-slate-900 group-hover:text-rose-600 transition-colors">masud.info@gmail.com</div>
                            <div class="text-xs text-slate-500">সাধারণ জিজ্ঞাসা ও ইনভয়েস</div>
                        </div>
                    </a>

                    <!-- Direct Card 4: Official Developer Hub -->
                    <div class="p-5 rounded-2xl bg-slate-900 text-white shadow-xl space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">ডেভেলপার টিম</span>
                            <span class="anim-pin text-sm">📍 ঢাকা, বাংলাদেশ</span>
                        </div>
                        <div class="text-lg font-black text-emerald-400">
                            MR Developer
                        </div>
                        <p class="text-xs text-slate-300 leading-relaxed">
                            বাংলাদেশের প্রযুক্তিগত উন্নয়নের লক্ষ্যে উদ্ভাবনী সফটওয়্যার সমাধান তৈরি করছে MR Developer টিম।
                        </p>
                        <div class="pt-2">
                            <a href="https://mrdeveloper.xyz" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-bold text-rose-400 hover:text-rose-300 hover:underline">
                                <span>🌐 পোর্টফোলিও ভিজিট করুন: mrdeveloper.xyz</span> ➔
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Right 7 Cols: Interactive Contact Form -->
                <div class="lg:col-span-7 bg-white rounded-3xl p-6 sm:p-10 border-2 border-slate-200 shadow-xl space-y-6">
                    <div>
                        <h3 class="text-xl sm:text-2xl font-black text-slate-900">
                            আমাদের একটি বার্তা পাঠান
                        </h3>
                        <p class="text-xs sm:text-sm text-slate-500 font-medium mt-1">
                            ফর্মটি পূরণ করুন, আমাদের প্রতিনিধি অল্প সময়ের মধ্যে আপনার সাথে ফোনে যোগাযোগ করবে।
                        </p>
                    </div>

                    @if(session('success'))
                        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-300 text-emerald-900 text-sm font-bold flex items-center gap-3">
                            <span class="text-xl">✅</span>
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    <form action="{{ route('contact.submit') }}" method="POST" class="space-y-4">
                        @csrf
                        
                        <div class="grid sm:grid-cols-2 gap-4">
                            <!-- Name -->
                            <div class="space-y-1.5">
                                <label for="name" class="block text-xs font-bold text-slate-700">আপনার পূর্ণ নাম <span class="text-rose-500">*</span></label>
                                <input type="text" id="name" name="name" required placeholder="মোঃ মাসুদ রানা" class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-300 text-sm focus:outline-none focus:border-rose-500 focus:bg-white transition-all">
                            </div>

                            <!-- Phone -->
                            <div class="space-y-1.5">
                                <label for="phone" class="block text-xs font-bold text-slate-700">মোবাইল নাম্বার <span class="text-rose-500">*</span></label>
                                <input type="tel" id="phone" name="phone" required placeholder="017xxxxxxxx" class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-300 text-sm focus:outline-none focus:border-rose-500 focus:bg-white transition-all font-mono">
                            </div>
                        </div>

                        <div class="grid sm:grid-cols-2 gap-4">
                            <!-- Shop Name -->
                            <div class="space-y-1.5">
                                <label for="shop_name" class="block text-xs font-bold text-slate-700">দোকান / ব্যবসার নাম</label>
                                <input type="text" id="shop_name" name="shop_name" placeholder="মায়েশা কম্পিউটার পয়েন্ট" class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-300 text-sm focus:outline-none focus:border-rose-500 focus:bg-white transition-all">
                            </div>

                            <!-- District / Location -->
                            <div class="space-y-1.5">
                                <label for="district" class="block text-xs font-bold text-slate-700">জেলা / এলাকা</label>
                                <input type="text" id="district" name="district" placeholder="উদাঃ মিরপুর, ঢাকা / সিলেট" class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-300 text-sm focus:outline-none focus:border-rose-500 focus:bg-white transition-all">
                            </div>
                        </div>

                        <!-- Message -->
                        <div class="space-y-1.5">
                            <label for="message" class="block text-xs font-bold text-slate-700">আপনার বার্তা বা প্রশ্ন <span class="text-rose-500">*</span></label>
                            <textarea id="message" name="message" rows="4" required placeholder="আপনার প্রিন্টার মডেল বা সিস্টেম সম্পর্কে কোনো প্রশ্ন থাকলে লিখুন..." class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-300 text-sm focus:outline-none focus:border-rose-500 focus:bg-white transition-all"></textarea>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="w-full py-3.5 px-6 rounded-2xl bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white font-black text-sm shadow-lg shadow-rose-500/25 transition-all hover:scale-[1.01] flex items-center justify-center gap-2">
                            <span>🚀 বার্তাটি পাঠান</span>
                        </button>
                    </form>
                </div>

            </div>

        </div>
    </section>

    <!-- Global Footer -->
    @include('partials.footer')

</body>
</html>
