<!DOCTYPE html>
<html lang="bn" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>কীভাবে কাজ করে – PrintSheba (প্রিন্ট সেবা) | সাইবার ক্যাফে অটোমেশন গাইড</title>
    <meta name="description" content="জানুন কীভাবে মাত্র ৩টি সহজ ধাপে PrintSheba প্ল্যাটফর্ম আপনার সাইবার ক্যাফে ও প্রিন্টিং শপকে সম্পূর্ণ অটোমেটিক করে তোলে।">

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

        /* 1. LASER SCANNING ANIMATION */
        @keyframes laserScanVertical {
            0% { top: 8%; opacity: 0.2; }
            50% { top: 88%; opacity: 1; }
            100% { top: 8%; opacity: 0.2; }
        }
        .anim-laser {
            position: absolute;
            left: 5%;
            width: 90%;
            height: 3px;
            background: linear-gradient(90deg, transparent, #ef4444, #f43f5e, #ef4444, transparent);
            box-shadow: 0 0 14px 3px rgba(239, 68, 68, 0.9);
            animation: laserScanVertical 2.4s ease-in-out infinite;
        }

        /* 2. PAPER EJECT SLIDER */
        @keyframes paperEject {
            0% { transform: translateY(-30px); opacity: 0; }
            25% { transform: translateY(0); opacity: 1; }
            75% { transform: translateY(18px); opacity: 1; }
            100% { transform: translateY(35px); opacity: 0; }
        }
        .anim-paper-slide {
            animation: paperEject 3s cubic-bezier(0.4, 0, 0.2, 1) infinite;
        }

        /* 3. CLOUD SIGNAL BEAM */
        @keyframes signalPulse {
            0%, 100% { transform: scale(1); opacity: 0.8; }
            50% { transform: scale(1.15); opacity: 1; filter: drop-shadow(0 0 10px rgba(16, 185, 129, 0.6)); }
        }
        .anim-signal {
            animation: signalPulse 2s ease-in-out infinite;
        }

        /* 4. DATA PACKET MOVING DOWN */
        @keyframes flowDots {
            0% { transform: translateY(-20px); opacity: 0; }
            50% { opacity: 1; }
            100% { transform: translateY(60px); opacity: 0; }
        }
        .anim-packet {
            animation: flowDots 1.8s linear infinite;
        }

        /* 5. FLOATING MOCKUP */
        @keyframes floatSlow {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-8px); }
        }
        .animate-float {
            animation: floatSlow 4s ease-in-out infinite;
        }

        /* 6. UPLOAD PROGRESS PULSE */
        @keyframes pulseProgress {
            0% { width: 15%; }
            50% { width: 95%; }
            100% { width: 100%; }
        }
        .anim-progress {
            animation: pulseProgress 2.8s ease-in-out infinite;
        }
    </style>
</head>
<body class="selection:bg-pink-500 selection:text-white">

    <!-- Top Navbar -->
    <header class="sticky top-0 z-50 backdrop-blur-md bg-white/90 border-b border-slate-200/80 transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <!-- Brand Logo -->
            <a href="/" class="flex items-center gap-3 group">
                <div class="w-11 h-11 rounded-2xl bg-white border border-slate-200 p-1.5 shadow-sm flex items-center justify-center group-hover:scale-105 transition-transform">
                    <svg class="w-7 h-7 text-rose-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 14h12v8H6z" fill="#f43f5e" fill-opacity="0.15" />
                    </svg>
                </div>
                <div>
                    <div class="text-2xl font-black text-slate-900 font-en tracking-tight flex items-center">
                        <span>Print</span><span class="text-rose-500">Sheba</span>
                    </div>
                    <span class="text-[10px] tracking-widest text-slate-400 font-bold uppercase block -mt-1">
                        Scan • Upload • Print
                    </span>
                </div>
            </a>

            <!-- Nav Links -->
            <nav class="hidden lg:flex items-center gap-7 text-sm font-semibold text-slate-600">
                <a href="/" class="hover:text-rose-600 transition-colors">হোম</a>
                <a href="{{ route('features') }}" class="hover:text-rose-600 transition-colors">ফিচারসমূহ</a>
                <a href="{{ route('how_it_works') }}" class="text-rose-600 font-bold border-b-2 border-rose-500 pb-0.5">কীভাবে কাজ করে</a>
                <a href="{{ route('faq') }}" class="hover:text-rose-600 transition-colors">প্রশ্নোত্তর</a>
                <a href="{{ route('contact') }}" class="hover:text-rose-600 transition-colors">যোগাযোগ</a>
            </nav>

            <!-- Actions on Right -->
            <div class="flex items-center gap-3">
                @auth
                    @if(auth()->user()->isSuperAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-full text-xs font-bold text-white bg-slate-900 hover:bg-slate-800 shadow-sm transition-all hover:scale-105">
                            <span>⚡ সুপার এডমিন</span>
                        </a>
                    @else
                        <a href="{{ route('shop.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-full text-xs font-bold text-white bg-slate-900 hover:bg-slate-800 shadow-sm transition-all hover:scale-105">
                            <span>🏪 শপ ড্যাশবোর্ড</span>
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-bold text-slate-700 hover:text-slate-950 bg-white hover:bg-slate-50 border border-slate-300 shadow-sm transition-all">
                        <span>🔑 সাইন ইন</span>
                    </a>
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-bold text-white bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 shadow-md shadow-rose-500/20 transition-all hover:scale-105">
                        <span>✨ ফ্রি ট্রায়াল শুরু করুন</span>
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- HERO HEADER -->
    <section class="relative pt-14 pb-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-b from-white via-rose-50/20 to-slate-50 border-b border-slate-200">
        <div class="max-w-4xl mx-auto text-center space-y-4">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-rose-100 border border-rose-200 text-rose-700 text-xs font-extrabold uppercase tracking-wider">
                <span class="animate-pulse">⚙️</span> অটোমেশন টেকনোলজি প্রসেস
            </div>
            
            <h1 class="text-3xl sm:text-5xl font-black text-slate-900 tracking-tight leading-tight">
                কীভাবে কাজ করে <span class="bg-gradient-to-r from-rose-600 to-pink-500 bg-clip-text text-transparent">PrintSheba?</span>
            </h1>

            <p class="text-base sm:text-lg text-slate-600 max-w-2xl mx-auto leading-relaxed font-medium">
                কোনো জটিল সফটওয়্যার ইনস্টল বা কম্পিউটার পরিবর্তন করতে হবে না। মাত্র ৩ মিনিটে প্রিন্টারের সাথে কানেক্ট করুন আর সারাজীবনের জন্য হাত গুটিয়ে আরাম করুন।
            </p>

            <!-- Quick Step Badges -->
            <div class="flex flex-wrap items-center justify-center gap-3 pt-3 text-xs font-bold text-slate-700">
                <span class="px-3 py-1.5 rounded-full bg-white border border-slate-200 shadow-sm">১. এক ক্লিকে রেজিস্ট্রেশন</span>
                <span class="text-slate-300">➔</span>
                <span class="px-3 py-1.5 rounded-full bg-white border border-slate-200 shadow-sm">২. কিউআর পোস্টার সাঁটানো</span>
                <span class="text-slate-300">➔</span>
                <span class="px-3 py-1.5 rounded-full bg-white border border-slate-200 shadow-sm">৩. কাস্টমার স্ক্যান ও অটো-প্রিন্ট</span>
            </div>
        </div>
    </section>

    <!-- COMPLETE 4-PHASE DETAILED WORKFLOW (ANIMATED) -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-20">
            
            <!-- STEP 1: দোকান খোলা ও প্রিন্টার যুক্ত করা -->
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Left: Visual Animated Mockup -->
                <div class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-3xl p-6 sm:p-8 text-white shadow-2xl relative overflow-hidden border border-slate-700">
                    <div class="flex items-center justify-between border-b border-slate-700 pb-3 mb-4">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-rose-500"></span>
                            <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                            <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                        </div>
                        <span class="text-xs font-mono text-emerald-400 font-bold">PrintSheba Windows Connector v2.1</span>
                    </div>

                    <!-- Animated Windows Agent Terminal -->
                    <div class="space-y-3 font-mono text-xs">
                        <div class="text-slate-400">$ printsheba-agent connect --shop=maisha-print</div>
                        <div class="text-emerald-400 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                            ✓ সেন্ট্রাল ক্লাউড সার্ভারের সাথে সফলভাবে সংযুক্ত!
                        </div>
                        <div class="bg-slate-800/80 rounded-xl p-3 border border-slate-700 space-y-2">
                            <div class="text-slate-300 font-bold flex items-center justify-between">
                                <span>কানেক্টেড প্রিন্টার্স সনাক্ত:</span>
                                <span class="text-emerald-400 text-[10px] bg-emerald-950 px-2 py-0.5 rounded">অটো-ডিটেক্টেড</span>
                            </div>
                            <div class="flex items-center gap-2 text-slate-200">
                                <span>🖨️ Canon LBP 2900</span>
                                <span class="text-[10px] text-emerald-400 font-bold">(Ready)</span>
                            </div>
                            <div class="flex items-center gap-2 text-slate-200">
                                <span>🖨️ Epson L3210 Color EcoTank</span>
                                <span class="text-[10px] text-emerald-400 font-bold">(Ready)</span>
                            </div>
                        </div>
                        <div class="text-amber-300 text-[11px] animate-pulse">
                            ⚡ Spooler Listener চালু হয়েছে... নতুন প্রিন্ট জবের অপেক্ষায়।
                        </div>
                    </div>

                    <!-- Glowing Signal Wave -->
                    <div class="absolute -right-10 -bottom-10 w-44 h-44 bg-emerald-500/10 rounded-full blur-2xl"></div>
                </div>

                <!-- Right: Explanatory Content -->
                <div class="space-y-4">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-lg bg-rose-100 text-rose-700 text-xs font-black">
                        ধাপ ০১ • সেটআপ প্রক্রিয়া
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-black text-slate-900 leading-tight">
                        দোকানের নাম দিয়ে ফ্রি একাউন্ট খুলুন ও প্রিন্টার সিলেক্ট করুন
                    </h2>
                    <p class="text-sm sm:text-base text-slate-600 leading-relaxed">
                        প্রথমে PrintSheba-তে আপনার সাইবার ক্যাফের নামে রেজিস্ট্রেশন করুন। কোনো জটিল সফটওয়্যার ড্রাইভার পরিবর্তন করতে হবে না। আপনার উইন্ডোজ কম্পিউটারে থাকা বর্তমান প্রিন্টারগুলো (Canon, Epson, HP, Brother) স্বয়ংক্রিয়ভাবে ডিটেক্ট হয়ে যাবে।
                    </p>

                    <div class="space-y-2 pt-2 text-xs sm:text-sm font-semibold text-slate-700">
                        <div class="flex items-start gap-2.5">
                            <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs shrink-0 mt-0.5">✓</span>
                            <span><strong>১ মিনিটের সেটআপ:</strong> উইন্ডোজ ৭, ১০ বা ১১ যেকোনো কম্পিউটারে চলে।</span>
                        </div>
                        <div class="flex items-start gap-2.5">
                            <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs shrink-0 mt-0.5">✓</span>
                            <span><strong>রেট কাস্টমাইজেশন:</strong> সাদাকালো প্রিন্ট কত (যেমন ৩৳/৫৳) আর কালার প্রিন্ট কত (১০৳/১৫৳) আপনি নিজে ঠিক করবেন।</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- STEP 2: রেডিমেড কিউআর পোস্টার কাউন্টারে সাঁটিয়ে দিন -->
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Left: Explanatory Content -->
                <div class="space-y-4 order-2 lg:order-1">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-lg bg-amber-100 text-amber-800 text-xs font-black">
                        ধাপ ০২ • কিউআর স্থাপন
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-black text-slate-900 leading-tight">
                        ড্যাশবোর্ড থেকে প্রস্তুত A4 কিউআর পোস্টারটি প্রিন্ট করে সাঁটিয়ে দিন
                    </h2>
                    <p class="text-sm sm:text-base text-slate-600 leading-relaxed">
                        আপনার ড্যাশবোর্ডে এক ক্লিকেই একটি আকর্ষণীয়, কালারফুল এবং হাই-রেজোলিউশন QR কোড পোস্টার তৈরি হয়ে যায়। পোস্টারে আপনার দোকানের নাম ও প্রিন্টের সকল রেট সুন্দরভাবে লেখা থাকে।
                    </p>

                    <div class="space-y-2 pt-2 text-xs sm:text-sm font-semibold text-slate-700">
                        <div class="flex items-start gap-2.5">
                            <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs shrink-0 mt-0.5">✓</span>
                            <span><strong>কাউন্টার বা দেয়ালে টানান:</strong> কাস্টমার দোকানে ঢুকেই সরাসরি নিজের মোবাইল দিয়ে স্ক্যান করতে পারবে।</span>
                        </div>
                        <div class="flex items-start gap-2.5">
                            <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs shrink-0 mt-0.5">✓</span>
                            <span><strong>নির্দিষ্ট প্রিন্টারের কিউআর:</strong> সাদাকালো প্রিন্টারের জন্য আলাদা ও কালার প্রিন্টারের জন্য আলাদা কিউআর কোড জেনারেট করার সুবিধা।</span>
                        </div>
                    </div>
                </div>

                <!-- Right: Animated Poster with Laser Sweep -->
                <div class="order-1 lg:order-2 bg-gradient-to-b from-amber-50 to-orange-50/40 rounded-3xl p-6 sm:p-8 border-2 border-amber-200 flex items-center justify-center relative shadow-lg">
                    <!-- Physical Poster Simulation -->
                    <div class="w-64 bg-white rounded-2xl shadow-2xl border-4 border-slate-900 p-4 text-center relative overflow-hidden">
                        <!-- Scanning Laser Beam -->
                        <div class="anim-laser"></div>

                        <div class="bg-rose-500 text-white rounded-lg py-1.5 px-3 mb-3">
                            <div class="text-[10px] font-black uppercase tracking-wider">মায়েশা কম্পিউটার পয়েন্ট</div>
                            <div class="text-[8px] font-semibold opacity-90">স্বত্বাধিকারী: মোঃ মাসুদ রানা</div>
                        </div>

                        <!-- Big Center QR Code -->
                        <div class="w-36 h-36 mx-auto bg-slate-50 border-2 border-slate-200 p-2 rounded-xl shadow-inner mb-3 flex items-center justify-center">
                            <svg class="w-full h-full text-slate-900" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M2 2h8v8H2V2zm2 2v4h4V4H4zm-2 8h8v8H2v-8zm2 2v4h4v-4H4zm8-12h8v8h-8V2zm2 2v4h4V4h-4zm2 8h2v2h-2v-2zm-2 2h2v2h-2v-2zm4 0h2v2h-2v-2zm-2 2h2v2h-2v-2zm2 2h2v2h-2v-2zm-4 0h2v2h-2v-2zm4-6h2v2h-2v-2zm-4 0h2v2h-2v-2zm0-2h2v2h-2v-2z"/>
                            </svg>
                        </div>

                        <div class="text-xs font-black text-slate-900">
                            মোবাইল দিয়ে স্ক্যান করুন 📱
                        </div>
                        <div class="text-[9px] font-bold text-slate-500 mt-1">
                            সাদাকালো: ৫৳ | কালার: ১৫৳
                        </div>
                    </div>
                </div>
            </div>

            <!-- STEP 3: কাস্টমার নিজে স্ক্যান, আপলোড ও অটো-পে করবে -->
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Left: Animated Mobile UI Simulation -->
                <div class="bg-gradient-to-br from-indigo-50 to-blue-50/50 rounded-3xl p-6 sm:p-8 border-2 border-blue-200 flex items-center justify-center shadow-lg">
                    <!-- Mobile Mockup -->
                    <div class="w-64 bg-slate-900 rounded-[32px] p-2.5 shadow-2xl border-4 border-slate-800">
                        <div class="bg-white rounded-[24px] p-3 space-y-3 overflow-hidden text-slate-900">
                            <!-- Mobile Header -->
                            <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                                <div class="text-[10px] font-black text-rose-600">PrintSheba Portal</div>
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                            </div>

                            <!-- File Selected Box -->
                            <div class="bg-slate-50 rounded-xl p-2 border border-slate-200 space-y-1">
                                <div class="flex items-center justify-between text-[9px] font-bold">
                                    <span class="truncate">📄 CV_Resume_Final.pdf</span>
                                    <span class="text-emerald-600">৩ পেজ</span>
                                </div>
                                <div class="w-full bg-slate-200 h-1.5 rounded-full overflow-hidden">
                                    <div class="bg-rose-500 h-full anim-progress"></div>
                                </div>
                            </div>

                            <!-- Options: Color & Copies -->
                            <div class="grid grid-cols-2 gap-2 text-[9px] font-bold">
                                <div class="bg-rose-50 border border-rose-200 text-rose-700 p-1.5 rounded-lg text-center">
                                    কালার প্রিন্ট (১ কপি)
                                </div>
                                <div class="bg-slate-50 border border-slate-200 text-slate-700 p-1.5 rounded-lg text-center">
                                    A4 পেপার
                                </div>
                            </div>

                            <!-- Bill Calculation -->
                            <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-2 text-center">
                                <div class="text-[9px] font-bold text-slate-500">মোট পরিশোধযোগ্য বিল:</div>
                                <div class="text-lg font-black text-emerald-700">৳ ৪৫.০০</div>
                            </div>

                            <!-- Pay Button -->
                            <div class="bg-gradient-to-r from-pink-600 to-rose-600 text-white font-black text-center py-2 rounded-xl text-xs shadow-md shadow-rose-500/30">
                                💳 বিকাশ / নগদ দিয়ে পে করুন
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Explanatory Content -->
                <div class="space-y-4">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-lg bg-blue-100 text-blue-800 text-xs font-black">
                        ধাপ ০৩ • কাস্টমার সেলফ-সার্ভিস
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-black text-slate-900 leading-tight">
                        কাস্টমার স্ক্যান করবে, ফাইল দিবে এবং স্বয়ংক্রিয়ভাবে বিল দেবে
                    </h2>
                    <p class="text-sm sm:text-base text-slate-600 leading-relaxed">
                        কাস্টমারকে কোনো অ্যাপ ডাউনলোড করতে হবে না। ক্যামেরায় কিউআর স্ক্যান করলেই তাৎক্ষণিক মোবাইল ব্রাউজারে পোর্টাল খুলে যাবে। কাস্টমার সরাসরি ফাইল চয়েস করবে, কালার/সাদাকালো এবং পেজ সংখ্যা বাছবে।
                    </p>

                    <div class="space-y-2 pt-2 text-xs sm:text-sm font-semibold text-slate-700">
                        <div class="flex items-start gap-2.5">
                            <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs shrink-0 mt-0.5">✓</span>
                            <span><strong>লাইভ অটো ক্যালকুলেটর:</strong> পেজ সংখ্যা অনুযায়ী স্বয়ংক্রিয়ভাবে টাকার অংক হিসাব হয়ে যাবে। দোকানদারকে কোনো দরদাম করতে হবে না।</span>
                        </div>
                        <div class="flex items-start gap-2.5">
                            <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs shrink-0 mt-0.5">✓</span>
                            <span><strong>বাকি থাকার সুযোগ ০%:</strong> পেমেন্ট সফল না হওয়া পর্যন্ত সিস্টেম প্রিন্টারে কোনো ফাইল পাঠায় না। তাই ১০০% নগদ ক্যাশ ইনকাম নিশ্চিত।</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- STEP 4: প্রিন্টার থেকে অটোমেটিক পেজ বের হবে -->
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Left: Explanatory Content -->
                <div class="space-y-4 order-2 lg:order-1">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-lg bg-emerald-100 text-emerald-800 text-xs font-black">
                        ধাপ ০৪ • জিরো টাচ প্রিন্ট
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-black text-slate-900 leading-tight">
                        পেমেন্ট কনফার্ম হওয়ামাত্রই প্রিন্টার স্বয়ংক্রিয়ভাবে পেজ ছেড়ে দেবে!
                    </h2>
                    <p class="text-sm sm:text-base text-slate-600 leading-relaxed">
                        কাস্টমার বিকাশ বা নগদে টাকা পরিশোধ করার ১ সেকেন্ডের মধ্যে উইন্ডোজ ব্যাকগ্রাউন্ড এজেন্ট সরাসরি প্রিন্টারের স্পুলারে কমান্ড পাঠিয়ে দেয়। কোনো দোকানদার বা কর্মীর হাত না দিয়েই প্রিন্টার স্বয়ংক্রিয়ভাবে চালু হয়ে প্রিন্ট কপি বের করে দেয়।
                    </p>

                    <div class="space-y-2 pt-2 text-xs sm:text-sm font-semibold text-slate-700">
                        <div class="flex items-start gap-2.5">
                            <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs shrink-0 mt-0.5">✓</span>
                            <span><strong>কম্পিউটারে হাত দেওয়া লাগবে না:</strong> মাউসে ক্লিক বা কোনো ফাইল ওপেন করার কোনো ঝামেলাই নেই।</span>
                        </div>
                        <div class="flex items-start gap-2.5">
                            <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs shrink-0 mt-0.5">✓</span>
                            <span><strong>১০০% ভাইরাস প্রটেকশন:</strong> কোনো পেনড্রাইভ ঢুকানো হয় না, ফলে দোকান বা পিসিতে কোনো ভাইরাস প্রবেশের প্রশ্নই আসে না।</span>
                        </div>
                    </div>
                </div>

                <!-- Right: Animated LaserJet Model Spooling Paper -->
                <div class="order-1 lg:order-2 bg-gradient-to-b from-emerald-50 to-teal-50/50 rounded-3xl p-6 sm:p-8 border-2 border-emerald-200 flex items-center justify-center shadow-lg">
                    <div class="w-72 relative flex flex-col items-center">
                        <!-- Cloud Sync Badge -->
                        <div class="anim-signal mb-3 flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-600 text-white text-[10px] font-black shadow-md">
                            <span>📡 লাইভ সিগন্যাল রিসিভড</span>
                            <span class="w-2 h-2 rounded-full bg-emerald-300 animate-ping"></span>
                        </div>

                        <!-- Printer Main Body -->
                        <div class="w-64 h-32 bg-slate-900 rounded-2xl border-2 border-slate-700 shadow-2xl p-4 relative z-10 flex flex-col justify-between">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center gap-1.5">
                                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                    <span class="text-[9px] text-emerald-400 font-mono font-bold">PRINTING IN PROGRESS</span>
                                </div>
                                <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                            </div>

                            <div class="w-full h-1.5 bg-slate-950 rounded-full"></div>

                            <div class="flex justify-between items-center text-[9px] text-slate-400 font-mono">
                                <span>Canon LBP 2900</span>
                                <span class="text-emerald-400 font-bold">100% Spooled</span>
                            </div>
                        </div>

                        <!-- Continuous Spooling Paper Sheet -->
                        <div class="anim-paper-slide w-44 h-24 bg-white rounded-lg shadow-xl border border-slate-300 p-3 -mt-6 z-0 space-y-2">
                            <div class="w-full h-1.5 bg-slate-300 rounded"></div>
                            <div class="w-3/4 h-1.5 bg-slate-300 rounded"></div>
                            <div class="w-5/6 h-1.5 bg-slate-300 rounded"></div>
                            <div class="w-1/2 h-1.5 bg-emerald-400 rounded"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- HARDWARE COMPATIBILITY BADGES -->
    <section class="py-14 bg-slate-50 border-t border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-6">
            <h3 class="text-xl sm:text-2xl font-black text-slate-900">
                সব ব্র্যান্ডের প্রিন্টার ও উইন্ডোজের সাথে ১০০% সাপোর্ট
            </h3>
            <p class="text-xs sm:text-sm text-slate-500 font-medium max-w-xl mx-auto">
                আপনার দোকানে যেকোনো প্রিন্টারই থাকুক না কেন—PrintSheba ড্রাইভারে কোনো রিকনফিগারিং ছাড়াই কাজ করে।
            </p>

            <div class="flex flex-wrap items-center justify-center gap-4 pt-2">
                <span class="px-5 py-2.5 rounded-2xl bg-white border border-slate-200 text-slate-800 font-bold text-xs shadow-sm flex items-center gap-2">
                    🖨️ Canon LBP & Pixma Series
                </span>
                <span class="px-5 py-2.5 rounded-2xl bg-white border border-slate-200 text-slate-800 font-bold text-xs shadow-sm flex items-center gap-2">
                    🖨️ Epson EcoTank (L3110/L3210/L805)
                </span>
                <span class="px-5 py-2.5 rounded-2xl bg-white border border-slate-200 text-slate-800 font-bold text-xs shadow-sm flex items-center gap-2">
                    🖨️ HP LaserJet & Smart Tank
                </span>
                <span class="px-5 py-2.5 rounded-2xl bg-white border border-slate-200 text-slate-800 font-bold text-xs shadow-sm flex items-center gap-2">
                    🖨️ Brother Monochrome & Color
                </span>
                <span class="px-5 py-2.5 rounded-2xl bg-white border border-slate-200 text-slate-800 font-bold text-xs shadow-sm flex items-center gap-2">
                    💻 Windows 7 / 8 / 10 / 11 (32 & 64 bit)
                </span>
            </div>
        </div>
    </section>

    <!-- CTA FOOTER SECTION -->
    <section class="py-16 bg-gradient-to-br from-slate-900 via-darknavy to-slate-900 text-white text-center relative overflow-hidden">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 relative z-10">
            <h2 class="text-3xl sm:text-4xl font-black leading-tight">
                আজই আপনার সাইবার ক্যাফেকে অটোমেটিক করুন
            </h2>
            <p class="text-slate-300 text-sm sm:text-base max-w-2xl mx-auto font-medium">
                কোনো হিডেন চার্জ নেই। রেজিস্ট্রেশন করুন এবং প্রথম ৫০টি প্রিন্ট সম্পূর্ণ ফ্রিতে চালিয়ে দেখুন কীভাবে আপনার দোকানের সময় বাঁচে এবং আয় বৃদ্ধি পায়।
            </p>
            <div class="flex flex-wrap items-center justify-center gap-4 pt-2">
                <a href="{{ route('register') }}" class="px-8 py-3.5 rounded-full text-sm font-extrabold text-white bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 shadow-xl shadow-rose-500/30 transition-all hover:scale-105">
                    ✨ ফ্রি রেজিস্ট্রেশন করুন
                </a>
                <a href="https://wa.me/8801644416378" target="_blank" class="px-7 py-3.5 rounded-full text-sm font-bold text-slate-200 bg-white/10 hover:bg-white/20 border border-white/20 backdrop-blur-md transition-all">
                    💬 হোয়াটসঅ্যাপে কথা বলুন: 01644416378
                </a>
            </div>
        </div>
    </section>

    <!-- Global Footer -->
    <footer class="bg-white border-t border-slate-200 py-12 text-slate-600 text-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-rose-500 text-white flex items-center justify-center font-black">
                    P
                </div>
                <div>
                    <div class="font-black text-slate-900 text-sm">PrintSheba (প্রিন্ট সেবা)</div>
                    <div class="text-[11px] text-slate-400">বাংলাদেশের সাইবার ক্যাফে অটোমেশন সিস্টেম</div>
                </div>
            </div>

            <div class="flex flex-wrap items-center justify-center gap-6 font-semibold">
                <a href="/" class="hover:text-rose-600">হোম</a>
                <a href="{{ route('features') }}" class="hover:text-rose-600">ফিচারসমূহ</a>
                <a href="{{ route('how_it_works') }}" class="text-rose-600 font-bold">কীভাবে কাজ করে</a>
                <a href="{{ route('faq') }}" class="hover:text-rose-600">প্রশ্নোত্তর</a>
                <a href="{{ route('contact') }}" class="hover:text-rose-600">যোগাযোগ</a>
            </div>

            <div class="text-center md:text-right text-[11px] text-slate-400">
                Developed by <a href="https://mrdeveloper.xyz" target="_blank" class="text-rose-600 font-bold hover:underline">MR Developer</a>
                <div>হটলাইন: 01644416378 | masud.info@gmail.com</div>
            </div>
        </div>
    </footer>

</body>
</html>
