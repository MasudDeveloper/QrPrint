<!DOCTYPE html>
<html lang="bn" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ফিচারসমূহ – PrintSheba (প্রিন্ট সেবা) | সাইবার ক্যাফে অটোমেশন সিস্টেম</title>
    <meta name="description" content="সাইবার ক্যাফে ও কম্পিউটার দোকানের জন্য সম্পূর্ণ অটোমেশন প্ল্যাটফর্ম: হোয়াটসঅ্যাপ জটলা, পেনড্রাইভের ভাইরাস ও বাকি টাকার ঝামেলা থেকে স্থায়ী মুক্তি।">

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

        /* 1. WHATSAPP ANIMATION: Phone vibration, incoming chat bubble & unread alert */
        @keyframes phoneRing {
            0%, 100% { transform: rotate(0deg); }
            20% { transform: rotate(-2deg); }
            40% { transform: rotate(2deg); }
            60% { transform: rotate(-1deg); }
            80% { transform: rotate(1deg); }
        }
        @keyframes chatPop {
            0%, 100% { transform: translateY(0px) scale(1); }
            50% { transform: translateY(-4px) scale(1.02); }
        }
        @keyframes badgePulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.18); opacity: 0.85; }
        }
        .anim-phone { animation: phoneRing 4s ease-in-out infinite; }
        .anim-chat-pop { animation: chatPop 3s ease-in-out infinite; }
        .anim-badge-pulse { animation: badgePulse 1.8s ease-in-out infinite; }

        /* 2. PENDRIVE ANIMATION: Glitch alert & virus warning pulse */
        @keyframes virusPulse {
            0%, 100% { filter: drop-shadow(0 0 2px rgba(239, 68, 68, 0.4)); transform: scale(1); }
            50% { filter: drop-shadow(0 0 12px rgba(239, 68, 68, 0.9)); transform: scale(1.05); }
        }
        @keyframes warningFlicker {
            0%, 100% { opacity: 1; transform: rotate(0deg); }
            25% { opacity: 0.3; transform: rotate(-4deg); }
            75% { opacity: 1; transform: rotate(4deg); }
        }
        .anim-virus { animation: virusPulse 2.2s ease-in-out infinite; }
        .anim-warning { animation: warningFlicker 1.4s infinite; }

        /* 3. BENGALI BAKI KHATA ANIMATION: Red Ledger, frustrated red stamp & coin drop */
        @keyframes khataTilt {
            0%, 100% { transform: translateY(0) rotate(-1deg); }
            50% { transform: translateY(-6px) rotate(1deg); }
        }
        @keyframes redStampPulse {
            0%, 100% { transform: scale(1) rotate(-8deg); opacity: 0.9; }
            50% { transform: scale(1.12) rotate(-8deg); opacity: 1; filter: drop-shadow(0 0 8px rgba(239, 68, 68, 0.6)); }
        }
        @keyframes coinDrop {
            0% { transform: translateY(-14px); opacity: 0; }
            40% { transform: translateY(0px); opacity: 1; }
            80% { transform: translateY(0px); opacity: 1; }
            100% { transform: translateY(10px); opacity: 0; }
        }
        .anim-khata { animation: khataTilt 3.6s ease-in-out infinite; }
        .anim-stamp { animation: redStampPulse 2s ease-in-out infinite; }
        .anim-coin { animation: coinDrop 2.5s ease-in-out infinite; }

        /* 4. IMPATIENT HUMAN CROWD ANIMATION: Annoyed body gestures, foot tapping, watch checking */
        @keyframes footTap {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-3px); }
        }
        @keyframes headShake {
            0%, 100% { transform: rotate(0deg); }
            25% { transform: rotate(-5deg); }
            75% { transform: rotate(5deg); }
        }
        @keyframes watchLook {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-2px) scale(1.04); }
        }
        @keyframes sweatDrip {
            0% { transform: translateY(-3px); opacity: 0; }
            40% { opacity: 1; }
            100% { transform: translateY(6px); opacity: 0; }
        }
        @keyframes clockSpin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .anim-foot { animation: footTap 1s ease-in-out infinite; }
        .anim-head-annoyed { animation: headShake 2.4s ease-in-out infinite; }
        .anim-watch { animation: watchLook 3s ease-in-out infinite; }
        .anim-sweat { animation: sweatDrip 2s ease-in-out infinite; }
        .anim-clock-hand { transform-origin: center; animation: clockSpin 5s linear infinite; }

        /* 5. STEP 1: SCANNING LASER BEAM */
        @keyframes laserScanStep {
            0% { top: 10%; opacity: 0.3; }
            50% { top: 85%; opacity: 1; }
            100% { top: 10%; opacity: 0.3; }
        }
        .anim-laser {
            position: absolute;
            left: 10%;
            width: 80%;
            height: 3px;
            background: linear-gradient(90deg, transparent, #ef4444, #f43f5e, #ef4444, transparent);
            box-shadow: 0 0 12px 2px rgba(239, 68, 68, 0.9);
            animation: laserScanStep 2.2s ease-in-out infinite;
        }

        /* 6. STEP 2: MOBILE UPLOAD ARROW */
        @keyframes uploadFloat {
            0%, 100% { transform: translateY(4px); opacity: 0.6; }
            50% { transform: translateY(-8px); opacity: 1; }
        }
        .anim-upload-arrow { animation: uploadFloat 1.8s ease-in-out infinite; }

        /* 7. STEP 3: PRINTER CONTINUOUS PAPER OUT */
        @keyframes printerSlide {
            0% { transform: translateY(-20px); opacity: 0; }
            30% { transform: translateY(0px); opacity: 1; }
            70% { transform: translateY(12px); opacity: 1; }
            100% { transform: translateY(22px); opacity: 0; }
        }
        .anim-print-paper { animation: printerSlide 3.2s cubic-bezier(0.4, 0, 0.2, 1) infinite; }

        /* Floating elements */
        @keyframes floatSlow {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-8px); }
        }
        .animate-float { animation: floatSlow 4s ease-in-out infinite; }
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
                <a href="{{ route('features') }}" class="text-rose-600 font-bold border-b-2 border-rose-500 pb-0.5">ফিচারসমূহ</a>
                <a href="{{ route('how_it_works') }}" class="hover:text-rose-600 transition-colors">কীভাবে কাজ করে</a>
                <a href="{{ route('faq') }}" class="hover:text-rose-600 transition-colors">প্রশ্নোত্তর</a>
                <a href="{{ route('contact') }}" class="hover:text-rose-600 transition-colors">যোগাযোগ</a>
            </nav>

            <!-- Actions on Right -->
            <div class="flex items-center gap-3">
                @auth
                    @if(auth()->user()->isSuperAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-full text-xs font-bold text-white bg-slate-900 hover:bg-slate-800 shadow-sm transition-all hover:scale-105">
                            <span>⚡ সুপার এডমিন ড্যাশবোর্ড</span>
                        </a>
                    @else
                        <a href="{{ route('shop.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-full text-xs font-bold text-white bg-slate-900 hover:bg-slate-800 shadow-sm transition-all hover:scale-105">
                            <span>🏪 শপ ড্যাশবোর্ড</span>
                        </a>
                    @endif
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="px-3 py-2 rounded-full text-xs font-bold text-rose-600 hover:bg-rose-50 transition-colors">
                            লগআউট
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-bold text-slate-700 hover:text-slate-950 bg-white hover:bg-slate-50 border border-slate-300 shadow-sm transition-all hover:border-slate-400">
                        <span>🔑 সাইন ইন</span>
                    </a>
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-bold text-white bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 shadow-md shadow-rose-500/20 transition-all hover:scale-105">
                        <span>✨ রেজিস্ট্রেশন</span>
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- FEATURES HERO SECTION -->
    <section class="pt-12 pb-16 px-4 sm:px-6 lg:px-8 text-center relative overflow-hidden">
        <!-- Ambient subtle glow circles -->
        <div class="absolute top-10 left-1/2 -translate-x-1/2 w-96 h-96 bg-gradient-to-br from-rose-200/40 via-purple-200/30 to-transparent rounded-full blur-3xl pointer-events-none -z-10"></div>

        <div class="max-w-4xl mx-auto space-y-4">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-rose-50 border border-rose-200 text-rose-700 text-xs font-bold uppercase tracking-wider">
                <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                <span>⚡ এক্সক্লুসিভ ফিচারসমূহ</span>
            </div>

            <h1 class="text-4xl sm:text-6xl font-black text-slate-950 tracking-tight leading-tight">
                শুধুমাত্র একটি প্রিন্টার নয় — <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-rose-500 via-pink-500 to-purple-600">
                    পুরো দোকানের পূর্ণাঙ্গ অটোমেশন সিস্টেম
                </span>
            </h1>

            <p class="text-base sm:text-lg text-slate-600 max-w-2xl mx-auto font-medium">
                বাংলাদেশের সাইবার ক্যাফে ও কম্পিউটার পয়েন্টের প্রতিটি বাস্তব ভোগান্তি দূর করতে তৈরি হয়েছে PrintSheba
            </p>

            <div class="flex flex-wrap items-center justify-center gap-2 pt-2 text-xs font-bold text-slate-700">
                <span class="px-3 py-1 rounded-full bg-white border border-slate-200 shadow-sm">✓ ১০০% ভাইরাস মুক্ত</span>
                <span class="px-3 py-1 rounded-full bg-white border border-slate-200 shadow-sm">✓ ০% বাকি টাকা</span>
                <span class="px-3 py-1 rounded-full bg-white border border-slate-200 shadow-sm">✓ হোয়াটসঅ্যাপ জটলা মুক্ত</span>
                <span class="px-3 py-1 rounded-full bg-white border border-slate-200 shadow-sm">✓ অটো বিকাশ/নগদ ভেরিফাই</span>
            </div>
        </div>
    </section>

    <!-- SECTION 1: WHAT HAPPENS IN YOUR SHOP RIGHT NOW? (4 Detailed Animated Problem Cards) -->
    <section class="py-16 bg-white border-t border-slate-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center max-w-3xl mx-auto mb-14 space-y-3">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-50 border border-amber-200 text-amber-800 text-xs font-bold uppercase tracking-wider">
                    <span>⚠️</span> সাইবার ক্যাফে ও কম্পিউটার পয়েন্টের নিত্যদিনের বাস্তবতা
                </div>
                <h2 class="text-2xl sm:text-4xl font-black text-slate-900 leading-tight">
                    প্রতিদিন আপনার দোকানে কী কী সমস্যা ও ভোগান্তি ঘটে?
                </h2>
                <p class="text-sm sm:text-base text-slate-600 font-medium leading-relaxed">
                    বাংলাদেশের হাজারো কম্পিউটার দোকানে সকাল থেকে রাত পর্যন্ত এই ৪টি মারাত্মক ঝামেলায় সময়, টাকা ও কাস্টমার নষ্ট হয়
                </p>
                <div class="w-20 h-1 bg-amber-400 rounded-full mx-auto"></div>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <!-- CARD 1: Asking for files on WhatsApp (Animated Phone + Real Chat Bubbles) -->
                <div class="bg-gradient-to-b from-white to-slate-50/70 rounded-3xl p-6 border-2 border-slate-200 shadow-sm hover:shadow-xl hover:border-emerald-400 transition-all flex flex-col justify-between group">
                    <div>
                        <!-- Animated Graphic Box -->
                        <div class="h-52 rounded-2xl bg-emerald-50/60 border border-emerald-100 flex items-center justify-center relative overflow-hidden mb-5 p-2">
                            <!-- WhatsApp Phone Screen Simulation -->
                            <div class="anim-phone w-36 h-44 bg-slate-900 rounded-2xl shadow-xl border-2 border-slate-700 p-1.5 relative flex flex-col justify-between">
                                <!-- Top WhatsApp Header -->
                                <div class="bg-emerald-600 rounded-t-lg px-2 py-1 flex items-center justify-between text-white">
                                    <div class="flex items-center gap-1">
                                        <div class="w-2 h-2 rounded-full bg-emerald-300"></div>
                                        <span class="text-[9px] font-bold font-sans">WhatsApp</span>
                                    </div>
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-300 animate-ping"></span>
                                </div>
                                <!-- Incoming Bengali Chat Messages -->
                                <div class="space-y-1.5 my-auto px-1">
                                    <div class="anim-chat-pop bg-emerald-100 text-emerald-950 p-1 rounded-md text-[8px] font-bold shadow-xs">
                                        📩 ভাই NID টা পাইলেন?
                                    </div>
                                    <div class="anim-chat-pop bg-white text-slate-800 p-1 rounded-md text-[8px] font-medium shadow-xs border border-slate-100">
                                        📄 রেজুমে ২ কপি কালার...
                                    </div>
                                    <div class="bg-rose-100 text-rose-900 p-1 rounded-md text-[8px] font-semibold">
                                        💸 টাকা পরে দিচ্ছি ভাই!
                                    </div>
                                </div>
                                <!-- Floating Unread Badge -->
                                <div class="anim-badge-pulse absolute -top-2 -right-2 w-7 h-7 rounded-full bg-rose-500 text-white font-extrabold text-[11px] flex items-center justify-center shadow-lg border-2 border-white">
                                    +15
                                </div>
                                <!-- Download Stuck Indicator -->
                                <div class="bg-slate-800 rounded px-1.5 py-0.5 flex items-center justify-between text-[8px] text-amber-400">
                                    <span>ডাউনলোড জটলা...</span>
                                    <span class="animate-spin text-[10px]">⏳</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 mb-2">
                            <span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 text-[10px] font-black uppercase tracking-wider">সমস্যা ০১</span>
                            <span class="text-[11px] font-bold text-slate-400">WhatsApp Chaos</span>
                        </div>
                        <h3 class="text-base sm:text-lg font-black text-slate-900 mb-2 leading-snug">
                            হোয়াটসঅ্যাপে ফাইল নেওয়ার মহাবিপদ ও জটলা
                        </h3>
                        <p class="text-xs text-slate-600 leading-relaxed space-y-1">
                            প্রতি কাস্টমারকে নিজের ব্যক্তিগত নাম্বার দিতে হয়। দিনে ৫০ জন কাস্টমারের শত শত মেসেজে ইনবক্স ভেসে যায়। <strong>"ভাই আমার ফাইলটা পাইছেন?"</strong> খুঁজতে খুঁজতে পিসির সামনে মিনিট দশেক ফাইল ডাউনলোড করতেই শেষ। ব্যক্তিগত নাম্বারে পরবর্তীতে রাত-বিরাতে মেসেজ ও কল দিয়ে বিরক্ত করার ঝামেলা তো রয়েছেই।
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-200/80 text-[11px] font-extrabold text-rose-600 flex items-center justify-between">
                        <span>✕ দৈনিক সময় নষ্ট: ১-২ ঘণ্টা</span>
                        <span class="text-xs">⏱️</span>
                    </div>
                </div>

                <!-- CARD 2: Pendrive Viruses (Animated USB + Windows Threat Alert) -->
                <div class="bg-gradient-to-b from-white to-slate-50/70 rounded-3xl p-6 border-2 border-slate-200 shadow-sm hover:shadow-xl hover:border-rose-400 transition-all flex flex-col justify-between group">
                    <div>
                        <!-- Animated Graphic Box -->
                        <div class="h-52 rounded-2xl bg-rose-50/60 border border-rose-100 flex items-center justify-center relative overflow-hidden mb-5 p-2">
                            <!-- Windows Virus Warning Popup Simulator -->
                            <div class="w-full max-w-[155px] space-y-2">
                                <!-- Windows Alert Box -->
                                <div class="bg-white rounded-xl shadow-lg border border-rose-200 p-2 text-center relative">
                                    <div class="flex items-center justify-between border-b border-slate-100 pb-1 mb-1">
                                        <div class="flex items-center gap-1">
                                            <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                                            <span class="text-[8px] font-bold text-slate-600">Windows Security</span>
                                        </div>
                                        <span class="text-[9px] font-bold text-rose-600">✕</span>
                                    </div>
                                    <div class="text-[9px] font-bold text-rose-600 flex items-center justify-center gap-1">
                                        <span class="anim-warning text-xs">⚠️</span> থ্রেট সনাক্ত হয়েছে!
                                    </div>
                                    <div class="text-[7px] text-slate-500 font-mono mt-0.5">
                                        Shortcut.Virus.exe
                                    </div>
                                    <div class="mt-1 px-1.5 py-0.5 bg-rose-50 rounded text-[7px] font-bold text-rose-700">
                                        CPU লোড: ৯৯% (Hanging)
                                    </div>
                                </div>

                                <!-- USB Pendrive Model with Red Virus Radiation -->
                                <div class="anim-virus flex items-center justify-center">
                                    <div class="w-20 h-8 bg-slate-900 rounded-lg border border-slate-700 flex items-center px-2 justify-between shadow-md relative">
                                        <span class="w-2 h-2 rounded-full bg-rose-500 anim-warning shadow-md shadow-rose-500"></span>
                                        <span class="text-[7px] font-bold font-mono text-slate-400">USB 3.0</span>
                                    </div>
                                    <div class="w-4 h-5 bg-slate-300 rounded-r border-t border-b border-r border-slate-400 flex items-center justify-center">
                                        <div class="w-2 h-3 bg-slate-500 rounded-xs"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 mb-2">
                            <span class="px-2 py-0.5 rounded bg-rose-100 text-rose-800 text-[10px] font-black uppercase tracking-wider">সমস্যা ০২</span>
                            <span class="text-[11px] font-bold text-slate-400">Virus & PC Crash</span>
                        </div>
                        <h3 class="text-base sm:text-lg font-black text-slate-900 mb-2 leading-snug">
                            পেনড্রাইভের মারাত্মক শর্টকাট ভাইরাস
                        </h3>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            কাস্টমারদের আনা পেনড্রাইভে থাকে হিডেন ও শর্টকাট ভাইরাস। পিসিতে ঢোকানোর সাথে সাথেই উইন্ডোজ স্লো হয়ে যায়, ব্রাউজার বন্ধ হয়ে যায় এবং গুরুত্বপূর্ণ সরকারি ফরম বা দোকানের ফাইল করাপ্ট হয়ে যায়। মাঝে মাঝেই উইন্ডোজ ক্র্যাশ করায় হাজার টাকা দিয়ে নতুন সেটআপ দিতে হয়।
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-200/80 text-[11px] font-extrabold text-rose-600 flex items-center justify-between">
                        <span>✕ পিসি ক্র্যাশ ও উইন্ডোজ নষ্ট ঝুঁকি</span>
                        <span class="text-xs">💥</span>
                    </div>
                </div>

                <!-- CARD 3: The Accounting Headache (Realistic Bengali Red Baki Khata + Unpaid Debts) -->
                <div class="bg-gradient-to-b from-white to-slate-50/70 rounded-3xl p-6 border-2 border-slate-200 shadow-sm hover:shadow-xl hover:border-amber-400 transition-all flex flex-col justify-between group">
                    <div>
                        <!-- Animated Graphic Box: Traditional Bengali Lal Baki Khata -->
                        <div class="h-52 rounded-2xl bg-amber-50/60 border border-amber-100 flex items-center justify-center relative overflow-hidden mb-5 p-2">
                            <!-- Traditional Red Bengali Ledger (লাল খেরো বাকি খাতা) -->
                            <div class="anim-khata w-36 bg-amber-50 rounded-xl shadow-xl border-2 border-amber-300 p-2 relative flex flex-col justify-between">
                                <!-- Red Leather Binding Spine -->
                                <div class="absolute -left-1 top-0 bottom-0 w-3 bg-rose-700 rounded-l-xl border-r border-amber-400 flex flex-col justify-around py-1">
                                    <div class="w-1.5 h-1 bg-amber-300 mx-auto rounded-full"></div>
                                    <div class="w-1.5 h-1 bg-amber-300 mx-auto rounded-full"></div>
                                    <div class="w-1.5 h-1 bg-amber-300 mx-auto rounded-full"></div>
                                </div>
                                <!-- Ledger Header -->
                                <div class="pl-2 border-b border-amber-200 pb-1 flex items-center justify-between">
                                    <span class="text-[9px] font-black text-rose-800">📕 বাকি খাতা</span>
                                    <span class="text-[8px] text-amber-700 font-bold">অক্টোবর</span>
                                </div>
                                <!-- Authentic Handwritten Bengali Debt Entries -->
                                <div class="pl-2 space-y-1 my-1.5 text-[8px] font-semibold text-slate-700">
                                    <div class="flex items-center justify-between border-b border-amber-100 pb-0.5">
                                        <span>করিম ভাই:</span>
                                        <span class="text-rose-600 font-bold">২৫০৳ ✕</span>
                                    </div>
                                    <div class="flex items-center justify-between border-b border-amber-100 pb-0.5">
                                        <span>রফিক ছাত্র:</span>
                                        <span class="text-rose-600 font-bold">৮০৳ ✕</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span>সালাম চাচা:</span>
                                        <span class="text-rose-600 font-bold">১৫০৳ ✕</span>
                                    </div>
                                </div>
                                <!-- Stamped Debt Alert -->
                                <div class="anim-stamp pl-2 pt-0.5">
                                    <div class="bg-rose-600 text-white font-extrabold text-[8px] px-1.5 py-0.5 rounded text-center shadow-md">
                                        বকেয়া: ৩,৫০০+ ৳
                                    </div>
                                </div>
                                <!-- Dropping Lost Coin -->
                                <div class="anim-coin absolute -bottom-2 right-1 w-6 h-6 rounded-full bg-amber-400 border border-amber-600 text-amber-950 font-black text-[10px] flex items-center justify-center shadow-md">
                                    ৳
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 mb-2">
                            <span class="px-2 py-0.5 rounded bg-amber-100 text-amber-800 text-[10px] font-black uppercase tracking-wider">সমস্যা ০৩</span>
                            <span class="text-[11px] font-bold text-slate-400">The Accounting Headache</span>
                        </div>
                        <h3 class="text-base sm:text-lg font-black text-slate-900 mb-2 leading-snug">
                            লাল বাকি খাতা ও দৈনিক টাকার গরমিল
                        </h3>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            পরিচিত মুখ, বন্ধু-বান্ধব বা ছাত্ররা প্রিন্ট নিয়ে বলে—<strong>"ভাই খুচরা নাই, পরে দিচ্ছি"</strong> বা <strong>"বিকাশ করে দেবনে"</strong>। তাড়াহুড়ার ভিড়ে খাতায় নাম আর টাকা ঠিকমতো টোকা হয় না। মাস শেষে দেখা যায় ৩,০০০ থেকে ৫,০০০ টাকার কোনো পাত্তা নেই! বাকি চাইতে গেলে লজ্জা, না চাইলে নিজের পকেট ফাঁকা।
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-200/80 text-[11px] font-extrabold text-rose-600 flex items-center justify-between">
                        <span>✕ মাসে ৩,০০০৳ – ৫,০০০৳ বকেয়া ক্ষতি</span>
                        <span class="text-xs">📉</span>
                    </div>
                </div>

                <!-- CARD 4: Everyone Waiting in the Crowd (Human Silhouettes with Impatient Body Language) -->
                <div class="bg-gradient-to-b from-white to-slate-50/70 rounded-3xl p-6 border-2 border-slate-200 shadow-sm hover:shadow-xl hover:border-purple-400 transition-all flex flex-col justify-between group">
                    <div>
                        <!-- Animated Graphic Box: Human Silhouette Queue with Foot Tapping & Watch Checking -->
                        <div class="h-52 rounded-2xl bg-purple-50/60 border border-purple-100 flex items-center justify-center relative overflow-hidden mb-5 p-2">
                            <div class="w-full flex flex-col items-center">
                                <!-- Hanging Wall Clock with Spinning Pointer -->
                                <div class="flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-white border border-purple-200 shadow-sm mb-2">
                                    <svg class="w-3.5 h-3.5 text-rose-500 anim-clock-hand" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                        <circle cx="12" cy="12" r="10"/>
                                        <polyline points="12 6 12 12 16 14"/>
                                    </svg>
                                    <span class="text-[9px] font-black text-rose-600">অপেক্ষা: ২০+ মিনিট</span>
                                </div>

                                <!-- Human Crowd in Queue Illustration -->
                                <div class="flex items-end justify-center gap-2 relative pt-2">
                                    <!-- Person 3: Arms Crossed, sighing (Back of line) -->
                                    <div class="flex flex-col items-center opacity-85">
                                        <div class="w-4 h-4 rounded-full bg-slate-500 mb-0.5"></div>
                                        <div class="w-6 h-10 bg-slate-600 rounded-t-lg"></div>
                                        <span class="text-[7px] text-slate-500 font-bold">কাস্টমার ৩</span>
                                    </div>

                                    <!-- Person 2: Checking Watch + Sweat Drop + Speech Bubble (Middle) -->
                                    <div class="anim-watch flex flex-col items-center relative z-10">
                                        <!-- Impatient Speech Bubble -->
                                        <div class="anim-chat-pop absolute -top-5 -left-3 bg-rose-500 text-white text-[7px] font-black px-1.5 py-0.5 rounded shadow-md whitespace-nowrap">
                                            😤 আর কতক্ষণ?!
                                        </div>
                                        <!-- Head with sweat droplet -->
                                        <div class="w-5 h-5 rounded-full bg-amber-600 relative mb-0.5">
                                            <span class="anim-sweat absolute -right-1 top-0 text-[8px]">💧</span>
                                        </div>
                                        <!-- Body with watch arm pose -->
                                        <div class="w-7 h-12 bg-amber-500 rounded-t-lg flex items-center justify-center">
                                            <div class="w-2.5 h-1 bg-white rounded-full"></div>
                                        </div>
                                        <span class="text-[7px] text-amber-800 font-black">কাস্টমার ২</span>
                                    </div>

                                    <!-- Person 1: Tapping Foot + Annoyed Shaking Head at Counter (Front) -->
                                    <div class="anim-head-annoyed flex flex-col items-center">
                                        <div class="w-5 h-5 rounded-full bg-rose-600 mb-0.5"></div>
                                        <div class="w-8 h-14 bg-rose-500 rounded-t-lg relative">
                                            <!-- Foot Tapping at bottom -->
                                            <div class="anim-foot absolute -bottom-1 left-1 w-2.5 h-1.5 bg-slate-800 rounded"></div>
                                        </div>
                                        <span class="text-[7px] text-rose-800 font-black">কাস্টমার ১</span>
                                    </div>

                                    <!-- Counter Line Indicator -->
                                    <div class="w-1.5 h-16 bg-slate-800 rounded-full ml-1"></div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 mb-2">
                            <span class="px-2 py-0.5 rounded bg-purple-100 text-purple-800 text-[10px] font-black uppercase tracking-wider">সমস্যা ০৪</span>
                            <span class="text-[11px] font-bold text-slate-400">Crowd & Customer Churn</span>
                        </div>
                        <h3 class="text-base sm:text-lg font-black text-slate-900 mb-2 leading-snug">
                            কাউন্টারে দীর্ঘ লাইন, বিরক্তি ও কাস্টমার হারানো
                        </h3>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            একসাথে ৫ জন কাস্টমার এলে ১ জনের ফাইল পিসিতে খুলে সেট করে প্রিন্ট দিতে ৫-৭ মিনিট লেগে যায়। পেছনে দাঁড়ানো মানুষগুলো অধৈর্য হয়ে ঘড়ি দেখে আর উশখুশ করে। পরীক্ষার বা অফিসের তাড়া থাকলে ভিড় দেখে অনেকেই প্রিন্ট না করে পাশের দোকানে চলে যায় — এতে কাস্টমারও হারায়, সুনামও নষ্ট হয়।
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-200/80 text-[11px] font-extrabold text-rose-600 flex items-center justify-between">
                        <span>✕ দৈনিক ৩-৫টি নিশ্চিত কাস্টমার মিস</span>
                        <span class="text-xs">🏃‍♂️</span>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- SECTION 2: AFTER SETTING UP PRINTSHEBA (GREEN HIGHLIGHT CONTAINER - DETAILED 3-STEP SOLUTION) -->
    <section class="py-16 bg-slate-50 border-t border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center max-w-3xl mx-auto mb-12 space-y-3">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold uppercase tracking-wider">
                    <span>✨</span> আধুনিক অটোমেশন প্রযুক্তি
                </div>
                <h2 class="text-2xl sm:text-4xl font-black text-slate-900 leading-tight">
                    PrintSheba সেটআপ করার পর কী কী চমৎকার সমাধান পাবেন?
                </h2>
                <p class="text-sm sm:text-base text-slate-600 font-medium leading-relaxed">
                    আপনার দোকানের কাজ শুধু ১টি—কাউন্টারে কিউআর কোড টানিয়ে রাখা। বাকি ১০০% কাজ স্বয়ংক্রিয়ভাবে করবে PrintSheba!
                </p>
                <div class="w-20 h-1 bg-emerald-500 rounded-full mx-auto"></div>
            </div>

            <!-- The Big Green Bordered Card like reference -->
            <div class="bg-white rounded-[36px] border-2 border-emerald-400 p-6 sm:p-10 shadow-xl space-y-8">
                
                <div class="grid md:grid-cols-3 gap-6">
                    
                    <!-- STEP 1: Stick the QR in shop -->
                    <div class="bg-slate-50/80 rounded-3xl p-6 border border-slate-200 text-left space-y-4 hover:bg-white hover:shadow-lg transition-all flex flex-col justify-between">
                        <div>
                            <!-- Animated QR Poster Box with laser sweep -->
                            <div class="h-44 rounded-2xl bg-white border border-slate-200 flex items-center justify-center relative overflow-hidden shadow-inner mb-4">
                                <div class="anim-laser"></div>
                                <div class="w-28 h-36 bg-amber-50 rounded-xl shadow-md border-2 border-slate-800 p-2 flex flex-col items-center justify-between text-center relative">
                                    <div class="text-[8px] font-black text-slate-900 truncate w-full">🏪 আপনার দোকান</div>
                                    <!-- QR Code Graphic -->
                                    <div class="w-16 h-16 bg-white p-1 rounded border border-slate-300">
                                        <svg class="w-full h-full text-slate-950" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M2 2h8v8H2V2zm2 2v4h4V4H4zm-2 8h8v8H2v-8zm2 2v4h4v-4H4zm8-12h8v8h-8V2zm2 2v4h4V4h-4zm2 8h2v2h-2v-2zm-2 2h2v2h-2v-2zm4 0h2v2h-2v-2zm-2 2h2v2h-2v-2zm2 2h2v2h-2v-2zm-4 0h2v2h-2v-2zm4-6h2v2h-2v-2zm-4 0h2v2h-2v-2zm0-2h2v2h-2v-2z"/>
                                        </svg>
                                    </div>
                                    <div class="text-[7px] font-extrabold text-rose-600 bg-rose-50 px-1 py-0.5 rounded w-full">
                                        স্ক্যান করে প্রিন্ট নিন
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 mb-1">
                                <span class="w-6 h-6 rounded-md bg-emerald-600 text-white font-bold text-xs inline-flex items-center justify-center font-en">১</span>
                                <h4 class="text-base font-black text-slate-900">দোকানে QR পোস্টার সাঁটিয়ে দিন</h4>
                            </div>
                            <p class="text-[11px] font-semibold text-emerald-700 mb-2">Stick the QR Poster in Shop</p>
                            <p class="text-xs text-slate-600 leading-relaxed">
                                রেজিস্ট্রেশনের পর এক ক্লিকেই আপনার দোকানের নাম ও নিজস্ব রেট সহ রেডিমেড A4 সাইজের কালারফুল পোস্টার প্রিন্ট করে কাউন্টারে বা দরজায় লাগিয়ে দিন।
                            </p>
                        </div>

                        <div class="pt-3 border-t border-slate-200/80 text-[11px] font-bold text-emerald-800 space-y-1">
                            <div>✓ কোনো জটিল ট্রেনিং বা সফটওয়্যার শেখার দরকার নেই</div>
                            <div>✓ দোকান ও প্রতিটি প্রিন্টারের নিজস্ব কিউআর কোড</div>
                        </div>
                    </div>

                    <!-- STEP 2: Customer scans, uploads, pays -->
                    <div class="bg-slate-50/80 rounded-3xl p-6 border border-slate-200 text-left space-y-4 hover:bg-white hover:shadow-lg transition-all flex flex-col justify-between">
                        <div>
                            <!-- Animated Mobile Screen with floating upload arrow & Auto Bill Calculator -->
                            <div class="h-44 rounded-2xl bg-blue-50/50 border border-blue-100 flex items-center justify-center relative overflow-hidden shadow-inner mb-4">
                                <div class="w-28 h-36 bg-white rounded-2xl border-2 border-slate-800 shadow-xl p-2 flex flex-col justify-between items-center relative">
                                    <!-- Mobile Notch -->
                                    <div class="w-8 h-1 bg-slate-300 rounded-full"></div>
                                    
                                    <!-- Animated Upload Flow -->
                                    <div class="w-full space-y-1 my-auto text-center">
                                        <div class="anim-upload-arrow w-7 h-7 rounded-full bg-rose-500 text-white flex items-center justify-center text-xs shadow-md mx-auto">
                                            ⬆
                                        </div>
                                        <div class="text-[8px] font-bold text-slate-700">ফাইল আপলোড হচ্ছে...</div>
                                        <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                            <div class="bg-emerald-500 h-1.5 rounded-full w-4/5 animate-pulse"></div>
                                        </div>
                                    </div>

                                    <!-- Paid Badge -->
                                    <div class="w-full bg-emerald-50 border border-emerald-200 rounded px-1 py-0.5 flex items-center justify-center gap-1 text-[8px] font-black text-emerald-700">
                                        <span>✓ বিকাশ Paid: ৳২০</span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 mb-1">
                                <span class="w-6 h-6 rounded-md bg-emerald-600 text-white font-bold text-xs inline-flex items-center justify-center font-en">২</span>
                                <h4 class="text-base font-black text-slate-900">কাস্টমার স্ক্যান, আপলোড ও পে করবে</h4>
                            </div>
                            <p class="text-[11px] font-semibold text-emerald-700 mb-2">Customer Scans, Uploads & Pays</p>
                            <p class="text-xs text-slate-600 leading-relaxed">
                                কাস্টমার তার মোবাইল দিয়ে কিউআর স্ক্যান করলেই দোকানের মোবাইল পোর্টাল খুলবে। সেখানে ফাইল আপলোড করে পেজ সিলেক্ট করবে এবং স্বয়ংক্রিয়ভাবে বিকাশ/নগদে বিল পরিশোধ করবে।
                            </p>
                        </div>

                        <div class="pt-3 border-t border-slate-200/80 text-[11px] font-bold text-emerald-800 space-y-1">
                            <div>✓ পেজ হিসাবের টেনশন নেই — অটোমেটিক বিল তৈরি</div>
                            <div>✓ টাকা পরিশোধ ছাড়া প্রিন্ট হবে না — বাকি পড়ার সুযোগ ০%</div>
                        </div>
                    </div>

                    <!-- STEP 3: The print comes out on its own -->
                    <div class="bg-slate-50/80 rounded-3xl p-6 border border-slate-200 text-left space-y-4 hover:bg-white hover:shadow-lg transition-all flex flex-col justify-between">
                        <div>
                            <!-- Animated Printer Model with continuous sliding paper -->
                            <div class="h-44 rounded-2xl bg-amber-50/40 border border-amber-100 flex items-center justify-center relative overflow-hidden shadow-inner mb-4">
                                <div class="w-32 relative flex flex-col items-center">
                                    <!-- Printer Main Body -->
                                    <div class="w-28 h-16 bg-slate-900 rounded-xl border border-slate-700 shadow-xl p-2 relative z-10 flex flex-col justify-between">
                                        <div class="flex justify-between items-center px-1">
                                            <div class="flex items-center gap-1">
                                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                                <span class="text-[7px] text-emerald-400 font-mono font-bold">READY</span>
                                            </div>
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                                        </div>
                                        <div class="w-full h-1 bg-slate-950 rounded"></div>
                                    </div>
                                    <!-- Continuous Spooling Paper Sheet -->
                                    <div class="anim-print-paper w-20 h-16 bg-white rounded shadow-md border border-slate-300 p-1.5 -mt-4 z-0 space-y-1">
                                        <div class="w-full h-1 bg-slate-200 rounded"></div>
                                        <div class="w-3/4 h-1 bg-slate-200 rounded"></div>
                                        <div class="w-1/2 h-1 bg-emerald-400 rounded"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 mb-1">
                                <span class="w-6 h-6 rounded-md bg-emerald-600 text-white font-bold text-xs inline-flex items-center justify-center font-en">৩</span>
                                <h4 class="text-base font-black text-slate-900">প্রিন্ট নিজে নিজেই বের হয়ে আসবে!</h4>
                            </div>
                            <p class="text-[11px] font-semibold text-emerald-700 mb-2">Instant Zero-Touch Auto Print</p>
                            <p class="text-xs text-slate-600 leading-relaxed">
                                পেমেন্ট কনফার্ম হওয়ামাত্রই কোনো মানুষের স্পর্শ ছাড়াই প্রিন্টারে স্বয়ংক্রিয়ভাবে সিগন্যাল চলে যাবে। সেকেন্ডের মধ্যে প্রিন্ট বের হয়ে আসবে এবং কাস্টমার হাত বাড়িয়ে কাগজ নিয়ে চলে যাবে!
                            </p>
                        </div>

                        <div class="pt-3 border-t border-slate-200/80 text-[11px] font-bold text-emerald-800 space-y-1">
                            <div>✓ মাউসে ক্লিক বা ফাইল ওপেন করার কোনো দরকার নেই</div>
                            <div>✓ একসাথে একাধিক কাস্টমার নিজেই প্রিন্ট নিয়ে নেবে</div>
                        </div>
                    </div>

                </div>

                <!-- Green Highlight Freedom Banner -->
                <div class="p-5 sm:p-6 rounded-2xl bg-gradient-to-r from-emerald-50 via-teal-50 to-emerald-50 border-2 border-emerald-300 text-center shadow-xs">
                    <p class="text-sm sm:text-base font-bold text-emerald-950 leading-relaxed">
                        🎉 <strong class="text-emerald-800 font-black">দোকানদারের ১০০% কাউন্টার স্বাধীনতা:</strong> আপনি আরামে বসে চা খান কিংবা অন্যান্য কাজে সময় দিন — ফাইল আদান-প্রদান, দরদাম করা, পেনড্রাইভ গোঁজা কিংবা বাকি টাকার হিসেব মেলানোর কোনো দরকার নেই। দিনশেষে মোবাইল ড্যাশবোর্ডে এক ক্লিকেই পেয়ে যাবেন সারাদিনের নিট লাভ ও ক্যাশ ব্যালেন্স!
                    </p>
                </div>

            </div>

        </div>
    </section>

    <!-- SECTION 3: 8 FULL SHOP SYSTEM FEATURES -->
    <section class="py-16 bg-white border-t border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-14 space-y-2">
                <div class="inline-flex items-center gap-2 text-2xl font-bold">
                    <span>🎁</span>
                    <h2 class="text-2xl sm:text-3xl font-black text-slate-900">
                        শুধুমাত্র প্রিন্ট নয় — পুরো দোকানের অটোমেশন
                    </h2>
                </div>
                <p class="text-xs sm:text-sm text-slate-500 font-semibold">
                    Not just Printing — A System for the Whole Shop
                </p>
                <div class="w-16 h-1 bg-rose-500 rounded-full mx-auto mt-2"></div>
            </div>

            <!-- 8 Feature Cards -->
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <!-- 1 -->
                <div class="p-6 rounded-3xl bg-slate-50/70 border border-slate-200 hover:bg-white hover:shadow-lg transition-all space-y-3">
                    <span class="w-10 h-10 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center text-lg font-bold">
                        ✂️
                    </span>
                    <h3 class="text-base font-bold text-slate-900">মোবাইলে ক্রপ ও ব্রাইটনেস</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        কাস্টমার ফোন থেকেই ছবি রোটেট, অপ্রয়োজনীয় বর্ডার কাটা ও লাইট অ্যাডজাস্ট করতে পারে। দোকানদারকে ফটোশপ খুলে সময় নষ্ট করতে হয় না।
                    </p>
                </div>

                <!-- 2 -->
                <div class="p-6 rounded-3xl bg-slate-50/70 border border-slate-200 hover:bg-white hover:shadow-lg transition-all space-y-3">
                    <span class="w-10 h-10 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center text-lg font-bold">
                        🖼️
                    </span>
                    <h3 class="text-base font-bold text-slate-900">৪×৬ পাসপোর্ট সাইজ গ্রিড</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        ১ ক্লিকে ৪ কপি বা ৮ কপি পাসপোর্ট সাইজ ছবি নিখুঁত কাটিং বর্ডার সহ ৪×৬ ফটো পেপারে সেটিং হয়ে প্রিন্ট বের হয়। কোনো পেপার ওয়েস্টেজ নেই।
                    </p>
                </div>

                <!-- 3 -->
                <div class="p-6 rounded-3xl bg-slate-50/70 border border-slate-200 hover:bg-white hover:shadow-lg transition-all space-y-3">
                    <span class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-lg font-bold">
                        🔀
                    </span>
                    <h3 class="text-base font-bold text-slate-900">সাদাকালো ও কালার রাউটিং</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        সাদা-কালো ডকুমেন্ট অটোমেটিক দ্রুতগতির লেজার প্রিন্টারে চলে যায়, আর কালার পেজ চলে যায় ইঙ্কজেট প্রিন্টারে। ভুল হওয়ার চান্স শূন্য।
                    </p>
                </div>

                <!-- 4 -->
                <div class="p-6 rounded-3xl bg-slate-50/70 border border-slate-200 hover:bg-white hover:shadow-lg transition-all space-y-3">
                    <span class="w-10 h-10 rounded-xl bg-pink-100 text-pink-600 flex items-center justify-center text-lg font-bold">
                        📲
                    </span>
                    <h3 class="text-base font-bold text-slate-900">বিকাশ ও নগদ অটো ভেরিফাই</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        দোকানদারের ফোনে বিকাশ/নগদে টাকা আসামাত্র ব্যাকগ্রাউন্ড এসএমএস রিডার মিলিয়ে তৎক্ষণাৎ প্রিন্ট রিলিজ করে। কোনো বাকি থাকার সুযোগ নেই।
                    </p>
                </div>

                <!-- 5 -->
                <div class="p-6 rounded-3xl bg-slate-50/70 border border-slate-200 hover:bg-white hover:shadow-lg transition-all space-y-3">
                    <span class="w-10 h-10 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center text-lg font-bold">
                        ⚡
                    </span>
                    <h3 class="text-base font-bold text-slate-900">বিদ্যুৎ বা নেট গেলে রিকভারি</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        বিদ্যুৎ বা ইন্টারনেট চলে গেলেও পেইড কাজ ড্রপ হয় না; লোকাল কিউতে সেভ থাকে এবং বিদ্যুৎ আসতেই একা একাই প্রিন্ট বের হতে থাকে।
                    </p>
                </div>

                <!-- 6 -->
                <div class="p-6 rounded-3xl bg-slate-50/70 border border-slate-200 hover:bg-white hover:shadow-lg transition-all space-y-3">
                    <span class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center text-lg font-bold">
                        🔒
                    </span>
                    <h3 class="text-base font-bold text-slate-900">জিরো-ডাটা প্রাইভেসি গার্ড</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        প্রিন্টারের রোলার থেকে পেজ বের হওয়ামাত্র সার্ভার ও পিসি মেমোরি থেকে ফাইল স্থায়ীভাবে মুছে যায়। এনআইডি বা ব্যাংক ডকুমেন্টের ১০০% নিরাপত্তা।
                    </p>
                </div>

                <!-- 7 -->
                <div class="p-6 rounded-3xl bg-slate-50/70 border border-slate-200 hover:bg-white hover:shadow-lg transition-all space-y-3">
                    <span class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-lg font-bold">
                        📑
                    </span>
                    <h3 class="text-base font-bold text-slate-900">পেজ রেঞ্জ ও ডুপ্লেক্স কন্ট্রোল</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        বড় পিডিএফ থেকে নির্দিষ্ট দরকারি পেজ (যেমন: ৫-১২) এবং একপিঠ নাকি এপিঠ-ওপিঠ (Duplex) প্রিন্ট হবে তা কাস্টমার মোবাইল থেকেই সিলেক্ট করতে পারে।
                    </p>
                </div>

                <!-- 8 -->
                <div class="p-6 rounded-3xl bg-slate-50/70 border border-slate-200 hover:bg-white hover:shadow-lg transition-all space-y-3">
                    <span class="w-10 h-10 rounded-xl bg-cyan-100 text-cyan-600 flex items-center justify-center text-lg font-bold">
                        📊
                    </span>
                    <h3 class="text-base font-bold text-slate-900">দৈনিক আয় ও রসিদ রিপোর্ট</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        আজকের দিনে কত কপি সাদাকালো, কত কপি রঙিন প্রিন্ট হলো এবং কত টাকা ক্যাশ বা বিকাশে আসলো—রিয়েল-টাইম লাভ-ক্ষতির সম্পূর্ণ নির্ভুল হিসাব।
                    </p>
                </div>

            </div>
        </div>
    </section>

    <!-- SECTION 4: BEFORE VS AFTER COMPARISON TABLE -->
    <section class="py-16 bg-slate-50 border-t border-slate-200">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center max-w-xl mx-auto mb-12 space-y-2">
                <div class="inline-flex items-center gap-2 text-2xl font-bold">
                    <span>⚖️</span>
                    <h2 class="text-2xl sm:text-3xl font-black text-slate-900">
                        এনালগ পদ্ধতি বনাম PrintSheba
                    </h2>
                </div>
                <p class="text-xs sm:text-sm text-slate-500 font-semibold">
                    Before vs After: প্রত্যক্ষ পার্থক্য অনুভব করুন
                </p>
                <div class="w-16 h-1 bg-amber-400 rounded-full mx-auto mt-2"></div>
            </div>

            <!-- Comparison Matrix Table matching screenshot style -->
            <div class="bg-white rounded-3xl border border-slate-200 shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs sm:text-sm">
                        <thead class="bg-slate-950 text-white font-bold">
                            <tr>
                                <th class="px-6 py-4 w-1/4">বিষয় / কাজের ক্ষেত্র</th>
                                <th class="px-6 py-4 w-3/8 text-rose-400">❌ সাধারণ এনালগ পদ্ধতি</th>
                                <th class="px-6 py-4 w-3/8 text-emerald-400">✨ PrintSheba আধুনিক সিস্টেম</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr class="hover:bg-slate-50/50">
                                <td class="px-6 py-4 font-bold text-slate-900">ফাইল রিসিভ</td>
                                <td class="px-6 py-4 text-rose-600 font-medium">হোয়াটসঅ্যাপ চ্যাটে শত মেসেজের ভিড়ে হারিয়ে যায়</td>
                                <td class="px-6 py-4 text-emerald-700 font-bold">১ ক্লিকে QR স্ক্যান ও ক্লাউডে অটো রিসিভ</td>
                            </tr>
                            <tr class="hover:bg-slate-50/50">
                                <td class="px-6 py-4 font-bold text-slate-900">পেনড্রাইভ ব্যবহার</td>
                                <td class="px-6 py-4 text-rose-600 font-medium">শর্টকাট ভাইরাস, পিসি স্লো ও হ্যাং হওয়া</td>
                                <td class="px-6 py-4 text-emerald-700 font-bold">১০০% পেনড্রাইভ মুক্ত — ০% ভাইরাস ঝুঁকি</td>
                            </tr>
                            <tr class="hover:bg-slate-50/50">
                                <td class="px-6 py-4 font-bold text-slate-900">টাকা সংগ্রহ ও বাকি</td>
                                <td class="px-6 py-4 text-rose-600 font-medium">'পরে টাকা দেব' বলে চলে যাওয়া, মাসে হাজার টাকা বাকি</td>
                                <td class="px-6 py-4 text-emerald-700 font-bold">আগে বিকাশ/নগদে পেমেন্ট, তারপর প্রিন্ট (০% বাকি)</td>
                            </tr>
                            <tr class="hover:bg-slate-50/50">
                                <td class="px-6 py-4 font-bold text-slate-900">সময় অপচয়</td>
                                <td class="px-6 py-4 text-rose-600 font-medium">প্রতি প্রিন্টে ফাইল খোঁজা ও ওপেন করতে ৪-৫ মিনিট</td>
                                <td class="px-6 py-4 text-emerald-700 font-bold">মাত্র ৩০ সেকেন্ডে কাস্টমার প্রিন্ট নিয়ে বের হয়ে যায়</td>
                            </tr>
                            <tr class="hover:bg-slate-50/50">
                                <td class="px-6 py-4 font-bold text-slate-900">কাস্টমার প্রাইভেসি</td>
                                <td class="px-6 py-4 text-rose-600 font-medium">ডাউনলোড ফোল্ডারে ব্যক্তিগত ফাইল জমে থাকে</td>
                                <td class="px-6 py-4 text-emerald-700 font-bold">প্রিন্ট হওয়ামাত্র সার্ভার ও পিসি থেকে চিরতরে ডিলিট</td>
                            </tr>
                            <tr class="hover:bg-slate-50/50">
                                <td class="px-6 py-4 font-bold text-slate-900">দোকানদারের শ্রম</td>
                                <td class="px-6 py-4 text-rose-600 font-medium">সারাদিন মাউস ও কিবোর্ডে চাপাচাপি</td>
                                <td class="px-6 py-4 text-emerald-700 font-bold">দোকানদার রিলাক্স — সিস্টেম নিজে প্রিন্ট ও হিসাব রাখে</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Numbers / ROI Banner (Exact match to reference bottom boxes) -->
            <div class="grid md:grid-cols-2 gap-6 mt-8">
                <!-- Dark Numbers Card -->
                <div class="p-6 sm:p-8 rounded-3xl bg-slate-950 text-white space-y-4 shadow-xl">
                    <span class="text-xs font-bold text-emerald-400 uppercase tracking-wider block">
                        📊 বাস্তব হিসেব — প্রতি মাসে আপনার লাভ
                    </span>
                    <div class="space-y-2 text-xs text-slate-300">
                        <div class="flex justify-between border-b border-slate-800 pb-2">
                            <span>দৈনিক গড় সময় সাশ্রয়:</span>
                            <span class="text-white font-bold font-en">২.৫+ ঘণ্টা সময়</span>
                        </div>
                        <div class="flex justify-between border-b border-slate-800 pb-2">
                            <span>অতিরিক্ত কাস্টমার সার্ভিস ক্যাপাসিটি:</span>
                            <span class="text-emerald-400 font-bold font-en">+৪০% বৃদ্ধি</span>
                        </div>
                        <div class="flex justify-between pt-1 text-sm font-bold">
                            <span>বাকি টাকা রোধে মাসিক সাশ্রয়:</span>
                            <span class="text-emerald-400 font-en text-base">৳ ৩,৫০০+ টাকা</span>
                        </div>
                    </div>
                </div>

                <!-- Gold / Light Tip Card -->
                <div class="p-6 sm:p-8 rounded-3xl bg-amber-50 border border-amber-200 text-amber-950 space-y-3 shadow-md flex flex-col justify-between">
                    <div>
                        <span class="text-xs font-bold text-amber-800 uppercase tracking-wider block">
                            💡 সাইবার ক্যাফে ব্যবসায়ীদের পরামর্শ
                        </span>
                        <p class="text-xs sm:text-sm text-slate-700 leading-relaxed mt-2">
                            কম্পিউটার দোকানের সবচেয়ে বড় পুঁজি হলো <strong>কাস্টমারের সময় ও দ্রুত সার্ভিস</strong>। কাস্টমার এসে নিজে স্ক্যান করে ফাইল প্রিন্ট নিয়ে গেলে দোকানের প্রতি তাদের ভরসা ১০ গুণ বেড়ে যায়।
                        </p>
                    </div>
                    <a href="/register" class="inline-flex items-center justify-center gap-2 py-3 px-5 rounded-2xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs shadow-md transition-all">
                        <span>আজই আপনার দোকানে শুরু করুন →</span>
                    </a>
                </div>
            </div>

        </div>
    </section>

    <!-- PRE-FOOTER CTA -->
    <section class="py-16 bg-gradient-to-br from-slate-900 via-slate-950 to-black text-white relative overflow-hidden border-t border-slate-800">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center space-y-4">
            <h2 class="text-3xl sm:text-4xl font-black text-white tracking-tight">
                আপনার সাইবার ক্যাফেকে আজই <span class="text-transparent bg-clip-text bg-gradient-to-r from-rose-400 to-pink-400">PrintSheba</span> তে রূপান্তর করুন
            </h2>
            <p class="text-slate-400 text-xs sm:text-sm max-w-xl mx-auto">
                হোয়াটসঅ্যাপ জটলা ও পেনড্রাইভের ভাইরাস থেকে মুক্তি পান। মাত্র ২ মিনিটে ফ্রি টেস্ট করুন।
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4 pt-3">
                <a href="tel:01644416378" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-2xl bg-rose-500 hover:bg-rose-600 text-white font-bold text-sm shadow-lg shadow-rose-500/30 transition-all hover:scale-105">
                    <span>📞 কল করুন: 01644416378</span>
                </a>
                <a href="https://wa.me/8801644416378?text=Hello%20PrintSheba%2C%20I%20want%20to%20know%20more" target="_blank" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-2xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm shadow-lg shadow-emerald-600/30 transition-all hover:scale-105">
                    <span>💬 হোয়াটসঅ্যাপে চ্যাট করুন</span>
                </a>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer id="contact" class="border-t border-slate-800 bg-slate-950 text-slate-300 py-16 text-xs relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-10 lg:gap-8 pb-12 border-b border-slate-800/80">
                
                <!-- Col 1: Brand & Status (4 Cols) -->
                <div class="lg:col-span-4 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-white/10 border border-slate-700 p-2 flex items-center justify-center">
                            <svg class="w-6 h-6 text-rose-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 14h12v8H6z" fill="#f43f5e" fill-opacity="0.3" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-xl font-black text-white font-en tracking-tight">
                                Print<span class="text-rose-500">Sheba</span>
                            </span>
                            <span class="text-[10px] tracking-widest text-slate-400 font-bold block -mt-0.5 uppercase">
                                স্মার্ট সেলফ-সার্ভিস প্রিন্ট
                            </span>
                        </div>
                    </div>

                    <p class="text-slate-400 leading-relaxed text-xs max-w-sm">
                        বাংলাদেশের সাইবার ক্যাফে, কম্পিউটার পয়েন্ট ও ফটোকপির দোকানের জন্য বিশেষায়িত অটোমেশন সফটওয়্যার। পেনড্রাইভ বা হোয়াটসঅ্যাপ ছাড়াই সেকেন্ডে পেজ বের করুন।
                    </p>

                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-slate-900 border border-slate-800 text-[11px] font-semibold text-slate-300">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span class="text-emerald-400 font-bold">সকল সিস্টেম সচল ও লাইভ</span>
                        <span class="text-slate-500">|</span>
                        <span>৯৯.৯% আপটাইম</span>
                    </div>
                </div>

                <!-- Col 2: Quick Links (2 Cols) -->
                <div class="lg:col-span-2 space-y-3">
                    <h3 class="text-white font-bold text-sm tracking-wide uppercase">
                        দ্রুত লিংক
                    </h3>
                    <ul class="space-y-2 text-xs text-slate-400">
                        <li><a href="/" class="hover:text-rose-400 transition-colors">হোমপেজ</a></li>
                        <li><a href="{{ route('features') }}" class="text-rose-400 font-bold">সকল ফিচারসমূহ</a></li>
                        <li><a href="{{ route('how_it_works') }}" class="hover:text-rose-400 transition-colors">কীভাবে কাজ করে</a></li>
                        <li><a href="{{ route('faq') }}" class="hover:text-rose-400 transition-colors">প্রশ্নোত্তর (FAQ)</a></li>
                        <li><a href="{{ route('contact') }}" class="hover:text-rose-400 transition-colors">যোগাযোগ</a></li>
                        <li><a href="/login" class="hover:text-rose-400 transition-colors font-bold text-slate-300">দোকানদার লগইন</a></li>
                    </ul>
                </div>

                <!-- Col 3: Key Features (3 Cols) -->
                <div class="lg:col-span-3 space-y-3">
                    <h3 class="text-white font-bold text-sm tracking-wide uppercase">
                        শক্তিশালী সলিউশন
                    </h3>
                    <ul class="space-y-2 text-xs text-slate-400">
                        <li class="flex items-center gap-1.5"><span class="text-rose-500">›</span> কিউআর কোড সেলফ প্রিন্টিং</li>
                        <li class="flex items-center gap-1.5"><span class="text-rose-500">›</span> ৪×৬ পাসপোর্ট ও স্ট্যাম্প সাইজ গ্রিড</li>
                        <li class="flex items-center gap-1.5"><span class="text-rose-500">›</span> বিকাশ ও নগদ এসএমএস অটো-রিলিজ</li>
                        <li class="flex items-center gap-1.5"><span class="text-rose-500">›</span> সাদাকালো ও কালার অটো-রাউটিং</li>
                        <li class="flex items-center gap-1.5"><span class="text-rose-500">›</span> অফলাইন প্রিন্ট কিউ ও রিকভারি</li>
                        <li class="flex items-center gap-1.5"><span class="text-rose-500">›</span> ১০০% জিরো-ডাটা প্রাইভেসি গার্ড</li>
                    </ul>
                </div>

                <!-- Col 4: Contact & MR Developer Info (3 Cols) -->
                <div class="lg:col-span-3 space-y-4">
                    <h3 class="text-white font-bold text-sm tracking-wide uppercase">
                        যোগাযোগ ও সাপোর্ট
                    </h3>
                    
                    <div class="space-y-2.5 text-xs text-slate-400">
                        <div class="flex items-start gap-2.5">
                            <div class="w-7 h-7 rounded-lg bg-rose-500/10 text-rose-400 flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            </div>
                            <div>
                                <span class="text-[10px] uppercase text-slate-500 block font-bold">হটলাইন / মোবাইল</span>
                                <a href="tel:01644416378" class="text-white font-mono font-bold hover:text-rose-400 transition-colors text-sm">
                                    01644416378
                                </a>
                            </div>
                        </div>

                        <div class="flex items-start gap-2.5">
                            <div class="w-7 h-7 rounded-lg bg-purple-500/10 text-purple-400 flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <span class="text-[10px] uppercase text-slate-500 block font-bold">ইমেইল ঠিকানা</span>
                                <a href="mailto:masud.info@gmail.com" class="text-slate-300 hover:text-white transition-colors">
                                    masud.info@gmail.com
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- DEVELOPED BY: MR DEVELOPER SPECIAL BADGE -->
                    <div class="p-4 rounded-2xl bg-gradient-to-br from-slate-900 to-slate-800/80 border border-slate-700/80 shadow-md space-y-2 mt-4">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] uppercase font-black tracking-wider text-rose-400">
                                DEVELOPED BY
                            </span>
                            <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        </div>
                        <div class="text-base font-black text-white font-en">
                            MR Developer
                        </div>
                        <p class="text-[11px] text-slate-400 leading-snug">
                            প্রফেশনাল সফটওয়্যার ডেভেলপমেন্ট ও অটোমেশন সলিউশন
                        </p>
                        <a href="https://mrdeveloper.xyz" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 text-xs font-bold text-rose-400 hover:text-rose-300 pt-1 group">
                            <span>https://mrdeveloper.xyz</span>
                            <svg class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                        </a>
                    </div>
                </div>

            </div>

            <!-- Bottom Copyright & Credit Bar -->
            <div class="pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-[11px] text-slate-500">
                <div class="text-center sm:text-left">
                    <span>© ২০২৬ PrintSheba (প্রিন্ট সেবা). সর্বস্বত্ব সংরক্ষিত।</span>
                </div>
                <div class="flex items-center gap-4 text-slate-400">
                    <span>Designed & Developed by <a href="https://mrdeveloper.xyz" target="_blank" rel="noopener noreferrer" class="text-rose-400 hover:text-rose-300 font-bold underline font-en">MR Developer</a></span>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
