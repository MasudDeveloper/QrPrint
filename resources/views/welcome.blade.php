<!DOCTYPE html>
<html lang="bn" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PrintSheba (প্রিন্ট সেবা) – সাইবার ক্যাফে ও প্রিন্ট শপের অটো প্রিন্ট সফটওয়্যার</title>
    <meta name="description" content="সাইবার ক্যাফে ও কম্পিউটার দোকানের জন্য: কাস্টমার QR স্ক্যান করে ফাইল পাঠাবে, পেমেন্ট করবে, প্রিন্ট নিজে বের হবে। WhatsApp বা পেনড্রাইভ ছাড়াই দ্রুত প্রিন্ট।">

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
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.4/dist/confetti.browser.min.js"></script>

    <style>
        body {
            background-color: #fafafa;
            color: #0f172a;
            font-family: 'Hind Siliguri', sans-serif;
            overflow-x: hidden;
            /* Precise dot grid background pattern like qrseprint.in */
            background-image: radial-gradient(#d1d5db 1.2px, transparent 1.2px);
            background-size: 24px 24px;
        }

        /* Continuous Laser Scan Animation */
        @keyframes laserSweep {
            0% { top: 12%; opacity: 0.2; }
            50% { top: 82%; opacity: 1; }
            100% { top: 12%; opacity: 0.2; }
        }
        .laser-line {
            position: absolute;
            left: 8%;
            width: 84%;
            height: 3px;
            background: linear-gradient(90deg, transparent, #ef4444, #f43f5e, #ef4444, transparent);
            box-shadow: 0 0 14px 3px rgba(239, 68, 68, 0.85);
            animation: laserSweep 2.4s ease-in-out infinite;
            border-radius: 9999px;
            pointer-events-none;
        }

        /* Continuous Paper Output Printing Animation */
        @keyframes printSlide {
            0% {
                transform: translateY(-40px);
                opacity: 0;
            }
            20% {
                transform: translateY(-15px);
                opacity: 1;
            }
            70% {
                transform: translateY(22px);
                opacity: 1;
            }
            90% {
                transform: translateY(28px);
                opacity: 0.8;
            }
            100% {
                transform: translateY(35px);
                opacity: 0;
            }
        }
        .spooling-paper {
            animation: printSlide 3.8s cubic-bezier(0.4, 0, 0.2, 1) infinite;
        }

        /* Floating elements animation */
        @keyframes floatSlow {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-7px); }
        }
        .animate-float {
            animation: floatSlow 4s ease-in-out infinite;
        }

        @keyframes floatDelayed {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(6px); }
        }
        .animate-float-delayed {
            animation: floatDelayed 4.5s ease-in-out infinite 1s;
        }

        /* Custom soft shadows */
        .card-shadow {
            box-shadow: 0 20px 45px -15px rgba(0, 0, 0, 0.08), 0 0 0 1px rgba(0, 0, 0, 0.04);
        }
        .printer-shadow {
            box-shadow: 0 30px 60px -15px rgba(15, 23, 42, 0.25);
        }
    </style>
    <!-- Favicon & Brand Icon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('logo.svg') }}">
</head>
<body class="selection:bg-pink-500 selection:text-white">

    <!-- Top Navbar -->
    <header class="sticky top-0 z-50 backdrop-blur-md bg-white/90 border-b border-slate-200/80 transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <!-- Brand Logo -->
            <div class="flex items-center gap-3">
                <a href="/" class="flex items-center gap-3 group">
                    <img src="{{ asset('logo.svg') }}" alt="PrintSheba Logo" class="w-11 h-11 rounded-2xl shadow-md group-hover:scale-105 transition-transform duration-300">
                    <div>
                        <div class="text-2xl font-black text-slate-900 font-en tracking-tight flex items-center">
                            <span>Print</span><span class="text-rose-500">Sheba</span>
                        </div>
                        <span class="text-[10px] tracking-widest text-slate-400 font-bold uppercase block -mt-1">
                            Scan • Upload • Print
                        </span>
                    </div>
                </a>

                <!-- Dotted separator dots -->
                <div class="hidden xl:flex items-center gap-1.5 ml-4 opacity-30">
                    <span class="w-1 h-1 rounded-full bg-slate-400"></span>
                    <span class="w-1 h-1 rounded-full bg-slate-400"></span>
                    <span class="w-1 h-1 rounded-full bg-slate-400"></span>
                    <span class="w-1 h-1 rounded-full bg-slate-400"></span>
                </div>
            </div>

            <!-- Nav Links -->
            <nav class="hidden lg:flex items-center gap-7 text-sm font-semibold text-slate-600">
                <a href="/" class="text-rose-600 font-bold">হোম</a>
                <a href="{{ route('features') }}" class="hover:text-rose-600 transition-colors">ফিচারসমূহ</a>
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

    <!-- HERO SECTION -->
    <section id="hero" class="relative pt-8 pb-16 lg:pt-12 lg:pb-24 px-4 sm:px-6 lg:px-8 overflow-hidden">
        <!-- Ambient subtle pink & purple glow behind the hero -->
        <div class="absolute top-1/4 right-1/4 w-[500px] h-[500px] bg-gradient-to-br from-pink-200/40 via-purple-200/30 to-transparent rounded-full blur-3xl pointer-events-none -z-10"></div>
        <div class="absolute top-1/3 left-10 w-[350px] h-[350px] bg-gradient-to-tr from-emerald-100/50 to-cyan-100/30 rounded-full blur-3xl pointer-events-none -z-10"></div>

        <div class="max-w-7xl mx-auto grid lg:grid-cols-12 gap-12 items-center">
            
            <!-- LEFT COLUMN: Typography & Value Proposition -->
            <div class="lg:col-span-7 space-y-7 text-left">
                
                <!-- Privacy Badge -->
                <div class="inline-flex items-center gap-2.5 px-4 py-1.5 rounded-full bg-slate-900 text-slate-100 text-xs font-medium shadow-md">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="font-bold text-emerald-400 uppercase tracking-wider text-[11px]">PRIVACY BY DESIGN</span>
                    <span class="text-slate-300 text-[11px] hidden sm:inline">| প্রিন্ট হওয়ামাত্র সার্ভার ও পিসি থেকে ফাইল স্থায়ীভাবে ডিলিট</span>
                </div>

                <!-- Brand Sub-Badge -->
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white border border-slate-200 p-1.5 shadow-sm flex items-center justify-center">
                        <svg class="w-6 h-6 text-rose-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 14h12v8H6z" fill="#f43f5e" fill-opacity="0.2" />
                        </svg>
                    </div>
                    <div>
                        <span class="text-2xl font-black text-slate-900 font-en tracking-tight">
                            Print<span class="text-rose-500">Sheba</span>
                        </span>
                        <span class="text-[11px] tracking-widest text-slate-400 font-bold block -mt-1 uppercase">
                            — SCAN • UPLOAD • PAY • PRINT —
                        </span>
                    </div>
                </div>

                <!-- Bold Impactful Headlines -->
                <div class="space-y-1">
                    <h1 class="text-4xl sm:text-6xl font-black text-slate-950 tracking-tight leading-[1.1]">
                        কাস্টমারের মোবাইল থেকে
                    </h1>
                    <div class="text-4xl sm:text-6xl font-black tracking-tight leading-[1.1] text-transparent bg-clip-text bg-gradient-to-r from-rose-500 via-pink-500 to-purple-600">
                        আপনার প্রিন্টারে। নিমিষেই।
                    </div>
                </div>

                <!-- Value Tagline with colorful dots -->
                <div class="flex items-center gap-3 text-sm sm:text-base font-bold text-slate-700">
                    <span>হোয়াটসঅ্যাপ নেই</span>
                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                    <span>পেনড্রাইভ নেই</span>
                    <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span>
                    <span>ফাইল খোঁজাখুঁজি নেই</span>
                </div>

                <!-- 4 Step Circular Icon Badges (Identical to reference) -->
                <div class="grid grid-cols-4 gap-3 sm:gap-4 max-w-lg pt-2">
                    <!-- Step 1: Scan -->
                    <div class="flex flex-col items-center text-center group cursor-pointer">
                        <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-rose-50 border border-rose-200/80 flex items-center justify-center text-rose-500 group-hover:bg-rose-500 group-hover:text-white transition-all shadow-sm">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                        </div>
                        <span class="text-xs font-black text-slate-900 mt-2 uppercase tracking-wider">১. SCAN</span>
                        <span class="text-[10px] text-slate-500 font-semibold">QR Code</span>
                    </div>

                    <!-- Step 2: Upload -->
                    <div class="flex flex-col items-center text-center group cursor-pointer">
                        <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-purple-50 border border-purple-200/80 flex items-center justify-center text-purple-600 group-hover:bg-purple-600 group-hover:text-white transition-all shadow-sm">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                        </div>
                        <span class="text-xs font-black text-slate-900 mt-2 uppercase tracking-wider">২. UPLOAD</span>
                        <span class="text-[10px] text-slate-500 font-semibold">Document</span>
                    </div>

                    <!-- Step 3: Pay -->
                    <div class="flex flex-col items-center text-center group cursor-pointer">
                        <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-pink-50 border border-pink-200/80 flex items-center justify-center text-pink-600 group-hover:bg-pink-600 group-hover:text-white transition-all shadow-sm">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                        </div>
                        <span class="text-xs font-black text-slate-900 mt-2 uppercase tracking-wider">৩. PAY</span>
                        <span class="text-[10px] text-slate-500 font-semibold">বিকাশ / নগদ</span>
                    </div>

                    <!-- Step 4: Auto Print -->
                    <div class="flex flex-col items-center text-center group cursor-pointer">
                        <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-emerald-50 border border-emerald-200/80 flex items-center justify-center text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-all shadow-sm">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        </div>
                        <span class="text-xs font-black text-slate-900 mt-2 uppercase tracking-wider">৪. PRINT</span>
                        <span class="text-[10px] text-slate-500 font-semibold">অটোমেটিক</span>
                    </div>
                </div>

                <!-- Action CTAs -->
                <div class="flex flex-col sm:flex-row items-center gap-4 pt-3">
                    <a href="#pricing" class="w-full sm:w-auto px-8 py-4 rounded-full text-sm font-black text-white bg-gradient-to-r from-rose-500 via-pink-500 to-rose-600 hover:from-rose-600 hover:to-pink-600 shadow-xl shadow-rose-500/25 transition-all hover:scale-105 flex items-center justify-center gap-2">
                        <span>💰 সেটআপ ও প্যাকেজ রেট দেখুন ⬇</span>
                    </a>
                    
                    <a href="#demo-live" class="w-full sm:w-auto px-8 py-4 rounded-full text-sm font-black text-rose-600 bg-white hover:bg-rose-50 border-2 border-rose-500 shadow-sm transition-all hover:scale-105 flex items-center justify-center gap-2">
                        <span>🎯 ফ্রি ডেমো টেস্ট করুন</span>
                    </a>
                </div>
            </div>

            <!-- RIGHT COLUMN: THE LIVE SCANNING QR & 3D PRINTER SPOOLING ANIMATION -->
            <div class="lg:col-span-5 relative flex flex-col items-center justify-center pt-6 lg:pt-0">
                
                <!-- Floating "Auto Print" Badge -->
                <div class="absolute -top-3 right-4 sm:right-10 z-20 px-3.5 py-1 rounded-full bg-amber-400 text-slate-950 font-black text-xs shadow-lg animate-float flex items-center gap-1.5">
                    <span>⚡ Auto Print</span>
                </div>

                <!-- Floating "NO FILE STORED" badge -->
                <div class="absolute top-8 -right-2 sm:right-0 z-20 p-3 rounded-2xl bg-slate-900/95 text-white border border-slate-700/80 shadow-2xl max-w-[190px] animate-float-delayed text-left">
                    <div class="text-[10px] font-black uppercase text-emerald-400 tracking-wider flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                        <span>NO FILE STORED</span>
                    </div>
                    <div class="text-[11px] font-bold text-slate-200 mt-1 leading-snug">
                        প্রিন্ট হওয়ামাত্র সার্ভার ও দোকানদারের পিসি থেকে ডিলিট
                    </div>
                </div>

                <!-- Floating Phone Frame with QR Code & Moving Laser Scanner -->
                <div class="w-64 sm:w-72 bg-white rounded-[32px] p-4 card-shadow border border-slate-200 relative z-10">
                    <!-- Top Phone Bar -->
                    <div class="flex items-center justify-between text-[11px] font-bold text-slate-500 px-2 pb-2">
                        <span class="text-rose-500">● PrintSheba</span>
                        <span>📶 98%</span>
                    </div>

                    <!-- Inner QR Box with Real Red Laser Scanning Line -->
                    <div class="relative bg-slate-50 rounded-2xl p-4 border border-slate-200 overflow-hidden text-center flex flex-col items-center justify-center min-h-[200px]">
                        <!-- Moving Laser Line -->
                        <div class="laser-line"></div>

                        <!-- Real QR Graphic -->
                        <div class="w-36 h-36 bg-white p-2.5 rounded-xl shadow-inner border border-slate-200">
                            <svg class="w-full h-full text-slate-900" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M2 2h8v8H2V2zm2 2v4h4V4H4zm-2 8h8v8H2v-8zm2 2v4h4v-4H4zm8-12h8v8h-8V2zm2 2v4h4V4h-4zm2 8h2v2h-2v-2zm-2 2h2v2h-2v-2zm4 0h2v2h-2v-2zm-2 2h2v2h-2v-2zm2 2h2v2h-2v-2zm-4 0h2v2h-2v-2zm4-6h2v2h-2v-2zm-4 0h2v2h-2v-2zm0-2h2v2h-2v-2z"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Bottom Floating Transaction Tags -->
                    <div class="flex items-center justify-between pt-3 px-1 text-[11px] font-bold">
                        <span class="text-emerald-600 flex items-center gap-1">
                            ✓ File Sent
                        </span>
                        <span class="bg-pink-100 text-pink-700 px-2 py-0.5 rounded-md font-mono">
                            💳 Paid ৳25
                        </span>
                        <span class="text-slate-500">
                            📄 Doc.pdf
                        </span>
                    </div>
                </div>

                <!-- 3D Desktop Printer Hardware with Output Tray -->
                <div class="w-80 sm:w-96 -mt-8 relative z-20">
                    <!-- Printer Main Body -->
                    <div class="bg-gradient-to-b from-slate-900 via-slate-950 to-black rounded-3xl p-5 border-t border-slate-700 printer-shadow text-center relative overflow-visible">
                        
                        <!-- Top Input Paper Slots -->
                        <div class="w-48 mx-auto -mt-8 mb-2 h-4 bg-slate-800 rounded-t-lg border-t border-slate-600 flex justify-center gap-1">
                            <div class="w-36 h-3 bg-white/90 rounded-t-sm shadow-sm"></div>
                        </div>

                        <!-- Status Indicators -->
                        <div class="flex items-center justify-between px-3 text-[11px] font-mono text-slate-400 pb-2">
                            <div class="flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                                <span class="text-rose-400 font-bold uppercase tracking-wider">PRINTING</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                            </div>
                        </div>

                        <!-- Paper Spooler Tray Exit Slot -->
                        <div class="h-2 w-full bg-slate-950 rounded border border-slate-800 my-1"></div>

                        <!-- Continuous Moving Printed Sheet (Spooling out) -->
                        <div class="relative min-h-[75px] flex justify-center items-start pt-1 overflow-hidden">
                            <div class="spooling-paper w-52 bg-white rounded-lg p-3 text-left shadow-xl border border-slate-200">
                                <div class="flex items-center justify-between pb-1 border-b border-slate-200">
                                    <span class="text-[9px] font-bold text-slate-800 font-mono">PrintSheba #Job109</span>
                                    <span class="text-[8px] bg-emerald-100 text-emerald-800 px-1 rounded font-bold">PAID</span>
                                </div>
                                <div class="space-y-1 mt-1.5">
                                    <div class="w-full h-1 bg-slate-200 rounded"></div>
                                    <div class="w-3/4 h-1 bg-slate-200 rounded"></div>
                                    <div class="w-5/6 h-1 bg-slate-200 rounded"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- FLOATING BOTTOM METRICS RIBBON (Exact match to reference) -->
        <div class="max-w-7xl mx-auto mt-16 bg-white rounded-3xl p-5 sm:p-7 card-shadow border border-slate-200">
            <div class="grid grid-cols-2 md:grid-cols-5 gap-6 items-center">
                
                <!-- Col 1 -->
                <div class="col-span-2 md:col-span-1 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-purple-100 text-purple-700 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-slate-800 leading-snug">
                            স্মার্ট প্রিন্টিং সলিউশন:
                        </div>
                        <div class="text-[11px] font-bold text-purple-700">
                            প্রিন্ট শপ, সাইবার ক্যাফে ও কম্পিউটার পয়েন্ট
                        </div>
                    </div>
                </div>

                <!-- Col 2 -->
                <div class="flex items-center gap-3 border-l border-slate-100 pl-4">
                    <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    </div>
                    <div>
                        <div class="text-xs font-black text-slate-900">All Printer</div>
                        <div class="text-[10px] text-slate-500 font-semibold">HP, Canon, Epson, Brother</div>
                    </div>
                </div>

                <!-- Col 3 -->
                <div class="flex items-center gap-3 border-l border-slate-100 pl-4">
                    <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636a9 9 0 010 12.728m0 0l-2.829-2.829m2.829 2.829L21 21M15.536 8.464a5 5 0 010 7.072m0 0l-2.829-2.829m-4.243 4.243a9 9 0 01-2.828-5.657m0 0l2.828 2.828M3 3l18 18"></path></svg>
                    </div>
                    <div>
                        <div class="text-xs font-black text-slate-900">No Wi-Fi Printer</div>
                        <div class="text-[10px] text-slate-500 font-semibold">ওয়াইফাই ছাড়াই চলবে</div>
                    </div>
                </div>

                <!-- Col 4 -->
                <div class="flex items-center gap-3 border-l border-slate-100 pl-4">
                    <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path></svg>
                    </div>
                    <div>
                        <div class="text-xs font-black text-slate-900">B&W & Color</div>
                        <div class="text-[10px] text-slate-500 font-semibold">আলাদা প্রিন্টার ম্যাপিং</div>
                    </div>
                </div>

                <!-- Col 5 -->
                <div class="flex items-center gap-3 border-l border-slate-100 pl-4">
                    <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <div class="text-xs font-black text-slate-900">Setup in 2 Mins</div>
                        <div class="text-[10px] text-slate-500 font-semibold">সহজ সফটওয়্যার ইনস্টল</div>
                    </div>
                </div>

            </div>
        </div>

        <!-- DARK PURPLE STATS BAR (Exact match to reference bottom footer ribbon) -->
        <div class="max-w-7xl mx-auto mt-4 bg-slate-950 text-white rounded-3xl p-5 sm:p-6 card-shadow">
            <div class="flex flex-col sm:flex-row items-center justify-around gap-6 text-center sm:text-left">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">🏪</span>
                    <div>
                        <div class="text-2xl font-black font-en">300+</div>
                        <div class="text-xs text-slate-400 font-semibold">Shops Onboarded in BD</div>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <span class="text-2xl">📄</span>
                    <div>
                        <div class="text-2xl font-black font-en">75,000+</div>
                        <div class="text-xs text-slate-400 font-semibold">Documents Printed</div>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <span class="text-2xl">😊</span>
                    <div>
                        <div class="text-2xl font-black font-en">99%+</div>
                        <div class="text-xs text-slate-400 font-semibold">Happy Shop Owners</div>
                    </div>
                </div>

                <div class="flex items-center gap-2 bg-slate-900 border border-slate-800 px-4 py-2 rounded-full">
                    <span class="text-xs font-bold text-emerald-400">🇧🇩 Made in Bangladesh</span>
                </div>
            </div>
        </div>
    </section>

    <!-- DETAILED FEATURES SECTION: শক্তিশালী ফিচারসমূহ যা আপনার কাজকে সহজ করবে -->
    <section id="features" class="py-20 bg-slate-50/70 border-t border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16 space-y-3">
                <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full bg-rose-100 text-rose-700 text-xs font-black uppercase tracking-wider">
                    ⚡ অত্যাধুনিক প্রযুক্তি
                </span>
                <h2 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">
                    প্রিন্ট সেবার শক্তিশালী ফিচারসমূহ
                </h2>
                <p class="text-sm sm:text-base text-slate-600 font-medium">
                    প্রতিটি ফিচার তৈরি করা হয়েছে বাংলাদেশের সাইবার ক্যাফে ও কম্পিউটার দোকানের বাস্তব অভিজ্ঞতা ও সমস্যা সমাধানের জন্য
                </p>
            </div>

            <!-- 8 Rich Feature Cards Grid -->
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <!-- Feature 1: Mobile Crop & Adjust -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/90 card-shadow hover:border-rose-400 hover:-translate-y-1 transition-all duration-200 flex flex-col justify-between group">
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded bg-rose-100 text-rose-700">ফটোশপ মুক্ত</span>
                            <span class="text-[10px] font-bold text-slate-400">মোবাইল টুল</span>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 mb-2">
                            মোবাইলে ক্রপ ও ব্রাইটনেস
                        </h3>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            কাস্টমার তার নিজের ফোন থেকেই ডকুমেন্টের আঁকাবাঁকা অংশ সোজা করা, অপ্রয়োজনীয় বর্ডার কাটা ও লাইট বাড়ানো সম্পন্ন করতে পারে। দোকানদারের ফটোশপ খুলে সময় নষ্ট করতে হয় না।
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 text-[11px] font-bold text-rose-600 flex items-center gap-1">
                        ✓ সময় বাঁচে গড়ে ৩ মিনিট
                    </div>
                </div>

                <!-- Feature 2: 4x6 Passport Photo Grid -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/90 card-shadow hover:border-purple-400 hover:-translate-y-1 transition-all duration-200 flex flex-col justify-between group">
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"></path></svg>
                        </div>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded bg-purple-100 text-purple-700">১-ক্লিক পাসপোর্ট</span>
                            <span class="text-[10px] font-bold text-slate-400">৪×৬ গ্রিড</span>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 mb-2">
                            পাসপোর্ট ও স্ট্যাম্প সাইজ অটো-গ্রিড
                        </h3>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            ১ ক্লিকে ৪ কপি বা ৮ কপি পাসপোর্ট ও স্ট্যাম্প সাইজ ছবি কাটিং বর্ডার সহ ৪×৬ ফটো পেপারে নিখুঁতভাবে সাজিয়ে নেয়। কোনো পেপার ওয়েস্টেজ নেই।
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 text-[11px] font-bold text-purple-600 flex items-center gap-1">
                        ✓ নিমিষেই ৮ কপি ছবি প্রিন্ট
                    </div>
                </div>

                <!-- Feature 3: Smart Dual Printer Routing -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/90 card-shadow hover:border-emerald-400 hover:-translate-y-1 transition-all duration-200 flex flex-col justify-between group">
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                        </div>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded bg-emerald-100 text-emerald-700">অটো রাউটিং</span>
                            <span class="text-[10px] font-bold text-slate-400">ডুয়েল প্রিন্টার</span>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 mb-2">
                            সাদাকালো ও কালার অটো-রাউটিং
                        </h3>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            সাদা-কালো ডকুমেন্ট নিজে থেকেই দ্রুতগতির লেজার প্রিন্টারে চলে যাবে, আর রঙিন ছবি চলে যাবে ইঙ্কজেট কালার প্রিন্টারে। ম্যানুয়াল প্রিন্টার সিলেক্টের কোনো ঝামেলা নেই।
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 text-[11px] font-bold text-emerald-600 flex items-center gap-1">
                        ✓ ভুল প্রিন্টার সিলেকশন ০%
                    </div>
                </div>

                <!-- Feature 4: bKash & Nagad Auto SMS -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/90 card-shadow hover:border-pink-400 hover:-translate-y-1 transition-all duration-200 flex flex-col justify-between group">
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-pink-50 text-pink-600 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        </div>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded bg-pink-100 text-pink-700">০% বাকি</span>
                            <span class="text-[10px] font-bold text-slate-400">অটো এসএমএস</span>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 mb-2">
                            বিকাশ ও নগদ অটো-ভেরিফিকেশন
                        </h3>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            দোকানদারের ফোনে বিকাশ/নগদে টাকা আসামাত্র ব্যাকগ্রাউন্ড এসএমএস রিডার রিয়েল-টাইমে TrxID মিলিয়ে প্রিন্ট রিলিজ করে দেয়। কোনো কাস্টমার দাঁড়িয়ে থাকা বা ভুয়া স্ক্রিনশটের ভয় নেই।
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 text-[11px] font-bold text-pink-600 flex items-center gap-1">
                        ✓ শতভাগ নিখুঁত পেমেন্ট
                    </div>
                </div>

                <!-- Feature 5: Offline Spooling -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/90 card-shadow hover:border-amber-400 hover:-translate-y-1 transition-all duration-200 flex flex-col justify-between group">
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded bg-amber-100 text-amber-800">অফলাইন ব্যাকআপ</span>
                            <span class="text-[10px] font-bold text-slate-400">লোকাল কিউ</span>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 mb-2">
                            বিদ্যুৎ বা নেট গেলে অটো-রিকভারি
                        </h3>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            দোকানে সাময়িক ইন্টারনেট বা বিদ্যুৎ চলে গেলেও পেমেন্ট হওয়া কাজ মুছে যায় না; সংযোগ ফিরে আসতেই লোকাল কিউ থেকে অটোমেটিক প্রিন্ট সম্পন্ন হয়।
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 text-[11px] font-bold text-amber-700 flex items-center gap-1">
                        ✓ কাজ হারানোর ভয় নেই
                    </div>
                </div>

                <!-- Feature 6: Zero Data Retention Privacy -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/90 card-shadow hover:border-blue-400 hover:-translate-y-1 transition-all duration-200 flex flex-col justify-between group">
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded bg-blue-100 text-blue-700">১০০% প্রাইভেসি</span>
                            <span class="text-[10px] font-bold text-slate-400">অটো ওয়াইপ</span>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 mb-2">
                            জিরো-ফাইল রিটেনশন পলিসি
                        </h3>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            প্রিন্টারের রোলার থেকে পেজ বের হওয়ামাত্র সার্ভার ও দোকানদারের পিসি থেকে ফাইল চিরতরে মুছে ফেলা হয়। জাতীয় পরিচয়পত্র বা ব্যাংক স্টেটমেন্ট থাকে শতভাগ সুরক্ষিত।
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 text-[11px] font-bold text-blue-600 flex items-center gap-1">
                        ✓ কাস্টমারের সর্বোচ্চ বিশ্বাস
                    </div>
                </div>

                <!-- Feature 7: Custom Page Range & Duplex -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/90 card-shadow hover:border-indigo-400 hover:-translate-y-1 transition-all duration-200 flex flex-col justify-between group">
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded bg-indigo-100 text-indigo-700">ডুপ্লেক্স ও রেঞ্জ</span>
                            <span class="text-[10px] font-bold text-slate-400">কাস্টম সেটিংস</span>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 mb-2">
                            পেজ রেঞ্জ ও ডুপ্লেক্স কন্ট্রোল
                        </h3>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            কাস্টমার তার প্রয়োজন অনুযায়ী নির্দিষ্ট পেজ (যেমন: ৫ থেকে ১২) সিলেক্ট করতে পারবে এবং একপিঠ নাকি এপিঠ-ওপিঠ (Duplex) প্রিন্ট হবে তা ফোন থেকেই সহজে বেছে নিতে পারবে।
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 text-[11px] font-bold text-indigo-600 flex items-center gap-1">
                        ✓ ভুল পেজ প্রিন্ট রোধ
                    </div>
                </div>

                <!-- Feature 8: Real-time Reports & Billing -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/90 card-shadow hover:border-cyan-400 hover:-translate-y-1 transition-all duration-200 flex flex-col justify-between group">
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-cyan-50 text-cyan-600 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        </div>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded bg-cyan-100 text-cyan-700">দৈনিক হিসাব</span>
                            <span class="text-[10px] font-bold text-slate-400">লাইভ অ্যানালিটিক্স</span>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 mb-2">
                            দৈনিক আয় ও রসিদ ড্যাশবোর্ড
                        </h3>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            আজকের দিনে কত কপি সাদাকালো, কত কপি রঙিন প্রিন্ট হলো এবং কত টাকা ক্যাশ বা বিকাশে জমা হলো—প্রতিদিনের রিয়েল-টাইম লাভ-ক্ষতির পূর্ণ হিসাব এক ক্লিকে পাওয়া যায়।
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 text-[11px] font-bold text-cyan-600 flex items-center gap-1">
                        ✓ হিসেব গরমিল শূন্য
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- HOW TO SETUP (কীভাবে ২ মিনিটে সেটআপ করবেন) -->
    <section id="setup-guide" class="py-20 bg-white border-t border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16 space-y-3">
                <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-black uppercase tracking-wider">
                    🚀 সহজ ও দ্রুত
                </span>
                <h2 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">
                    মাত্র ৩টি ধাপে আপনার দোকানে শুরু করুন
                </h2>
                <p class="text-sm sm:text-base text-slate-600">
                    কোনো নতুন দামি প্রিন্টার বা টেকনিক্যাল অভিজ্ঞতার প্রয়োজন নেই। সময় লাগবে মাত্র ২ মিনিট!
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 relative">
                <!-- Connecting dotted line for desktop -->
                <div class="hidden md:block absolute top-1/2 left-1/4 right-1/4 h-0.5 border-t-2 border-dashed border-slate-200 -z-0"></div>

                <!-- Step 1 -->
                <div class="bg-slate-50 rounded-3xl p-8 border border-slate-200/90 relative z-10 text-center hover:bg-white hover:shadow-lg transition-all">
                    <div class="w-16 h-16 rounded-2xl bg-rose-500 text-white font-black text-2xl flex items-center justify-center mx-auto mb-6 shadow-lg shadow-rose-500/30">
                        ১
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">
                        উইন্ডোজ এজেন্ট চালু করুন
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mb-4">
                        আমাদের মাত্র ৩ মেগাবাইটের হালকা প্রিন্ট এজেন্ট আপনার কম্পিউটার বা ল্যাপটপে ওপেন করুন। বর্তমান প্রিন্টারের সাথে অটো কানেক্ট হবে।
                    </p>
                    <span class="inline-block px-3 py-1 bg-white text-slate-700 text-[11px] font-bold rounded-full border border-slate-200">
                        💻 All Windows 7/10/11
                    </span>
                </div>

                <!-- Step 2 -->
                <div class="bg-slate-50 rounded-3xl p-8 border border-slate-200/90 relative z-10 text-center hover:bg-white hover:shadow-lg transition-all">
                    <div class="w-16 h-16 rounded-2xl bg-purple-600 text-white font-black text-2xl flex items-center justify-center mx-auto mb-6 shadow-lg shadow-purple-600/30">
                        ২
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">
                        QR পোস্টারটি ঝুলিয়ে দিন
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mb-4">
                        আপনার দোকানের নামের রেডিমেড কিউআর কোড পোস্টার প্রিন্ট করে দোকানের দেওয়ালে বা কাঁচের কাউন্টারে সাঁটিয়ে দিন।
                    </p>
                    <span class="inline-block px-3 py-1 bg-white text-slate-700 text-[11px] font-bold rounded-full border border-slate-200">
                        🖼️ রেডি প্রিন্টেবল ডিজাইন
                    </span>
                </div>

                <!-- Step 3 -->
                <div class="bg-slate-50 rounded-3xl p-8 border border-slate-200/90 relative z-10 text-center hover:bg-white hover:shadow-lg transition-all">
                    <div class="w-16 h-16 rounded-2xl bg-emerald-500 text-white font-black text-2xl flex items-center justify-center mx-auto mb-6 shadow-lg shadow-emerald-500/30">
                        ৩
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">
                        কাস্টমার প্রিন্ট করবে নিজে নিজে
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mb-4">
                        কাস্টমার স্ক্যান করবে, ডকুমেন্ট দিবে, বিকাশ বা নগদে টাকা দেবে আর প্রিন্টার অটোমেটিক পেজ বের করবে। আপনি আরামসে ক্যাশ সংগ্রহ করুন!
                    </p>
                    <span class="inline-block px-3 py-1 bg-white text-slate-700 text-[11px] font-bold rounded-full border border-slate-200">
                        ✨ ০% দোকানদারের শ্রম
                    </span>
                </div>
            </div>

            @if(!empty($videoUrl))
                @php
                    $embedUrl = $videoUrl;
                    if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $videoUrl, $ytMatch)) {
                        $embedUrl = 'https://www.youtube.com/embed/' . $ytMatch[1] . '?rel=0&modestbranding=1';
                    }
                @endphp
                <!-- DYNAMIC VIDEO PRESENTATION SECTION (Controlled from Super Admin) -->
                <div id="video-demo" class="mt-16 pt-12 border-t border-slate-200/90 max-w-4xl mx-auto">
                    <div class="text-center mb-8 space-y-2">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-rose-100 text-rose-700 text-xs font-black uppercase tracking-wider">
                            🎬 লাইভ ভিডিও ডেমো
                        </span>
                        <h3 class="text-2xl sm:text-3xl font-black text-slate-900">
                            {{ $videoTitle ?: 'কীভাবে ২ মিনিটে কাস্টমার নিজে নিজে প্রিন্ট করবে' }}
                        </h3>
                        <p class="text-xs sm:text-sm text-slate-500">
                            নিচের ভিডিওটি দেখে সরাসরি পুরো অটোমেটিক প্রিন্টিং প্রক্রিয়াটি লাইভ দেখুন
                        </p>
                    </div>

                    <!-- Video Player Container with Glowing Shadow -->
                    <div class="relative bg-black rounded-3xl overflow-hidden card-shadow border-4 border-white shadow-2xl aspect-video">
                        <iframe 
                            class="w-full h-full"
                            src="{{ $embedUrl }}" 
                            title="PrintSheba Demo Video" 
                            frameborder="0" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                            allowfullscreen>
                        </iframe>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- REAL LIFE COMPARISON: দোকানে প্রতিদিন কী ঘটে বনাম PrintSheba -->
    <section class="py-20 bg-slate-50/70 border-t border-slate-200">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-14 space-y-2">
                <h2 class="text-3xl font-black text-slate-900">
                    😤 এখন আপনার দোকানে কী সমস্যা হয়?
                </h2>
                <p class="text-sm text-slate-600">
                    সনাতন এনালগ পদ্ধতির কারণে প্রতিদিন যে অযথা ভোগান্তি হয়
                </p>
            </div>

            <div class="grid md:grid-cols-2 gap-8">
                <!-- Left: Problems -->
                <div class="p-8 rounded-3xl bg-red-50/60 border border-red-200/80 space-y-5">
                    <h3 class="text-lg font-bold text-red-950 flex items-center gap-2">
                        <span>❌ এনালগ পদ্ধতির ভোগান্তি</span>
                    </h3>
                    <ul class="space-y-4 text-xs sm:text-sm text-slate-700">
                        <li class="flex items-start gap-3">
                            <span class="text-red-500 font-bold text-base shrink-0">✕</span>
                            <div><strong>পার্সোনাল হোয়াটসঅ্যাপ নাম্বার:</strong> প্রতি কাস্টমারকে দিতে হয়। চ্যাটে ফাইল হারিয়ে যায়, 'ভাই আমার ফাইলটা পাইলেন?' খুঁজতে খুঁজতে দিন শেষ।</div>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="text-red-500 font-bold text-base shrink-0">✕</span>
                            <div><strong>পেনড্রাইভের ভাইরাস:</strong> কাস্টমারের পেনড্রাইভে ভাইরাস থাকে, পিসি হ্যাং বা উইন্ডোজ নষ্ট হয়।</div>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="text-red-500 font-bold text-base shrink-0">✕</span>
                            <div><strong>বকেয়া ও হিসেব গরমিল:</strong> কে টাকা দিল, কে পরে দেব বলে গেল—খাতায় লিখে রাখলেও মাস শেষে হিসেব মেলে না।</div>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="text-red-500 font-bold text-base shrink-0">✕</span>
                            <div><strong>কাউন্টারে দীর্ঘ লাইন:</strong> ফাইল খোলো, পেজ গুণো, প্রিন্ট দাও—একজনের জন্য বাকিরা দাঁড়িয়ে বিরক্ত হয়।</div>
                        </li>
                    </ul>
                </div>

                <!-- Right: Solution -->
                <div class="p-8 rounded-3xl bg-emerald-50/70 border border-emerald-200 space-y-5">
                    <h3 class="text-lg font-bold text-emerald-950 flex items-center gap-2">
                        <span>✨ PrintSheba ব্যবহারের পর</span>
                    </h3>
                    <ul class="space-y-4 text-xs sm:text-sm text-slate-700">
                        <li class="flex items-start gap-3">
                            <span class="text-emerald-600 font-bold text-base shrink-0">✓</span>
                            <div><strong>কাউন্টারে বসে থাকুন:</strong> ফাইল নেওয়া, ওপেন করা বা প্রিন্ট বাটনে চাপ দেওয়ার কোনো প্রয়োজন নেই।</div>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="text-emerald-600 font-bold text-base shrink-0">✓</span>
                            <div><strong>বিকাশ ও নগদ অটো ভেরিফাই:</strong> কাস্টমার সেন্ড মানি করামাত্র এসএমএস রিড করে প্রিন্টারে অটোমেটিক পেজ বের হবে।</div>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="text-emerald-600 font-bold text-base shrink-0">✓</span>
                            <div><strong>০% ভাইরাস ঝুঁকি:</strong> ক্লাউডের মাধ্যমে সরাসরি স্পুল হওয়ায় কোনো পেনড্রাইভ ঢুকানোর দরকার নেই।</div>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="text-emerald-600 font-bold text-base shrink-0">✓</span>
                            <div><strong>শতভাগ গোপনীয়তা:</strong> প্রিন্ট শেষ হওয়ামাত্র সার্ভার ও পিসি থেকে ফাইল স্থায়ীভাবে মুছে যায়।</div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- LIVE PROFIT CALCULATOR SECTION -->
    <section id="calculator" class="py-20 bg-slate-50 border-t border-slate-200">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white p-8 sm:p-12 rounded-3xl card-shadow border border-slate-200 space-y-8">
                <div class="text-center space-y-2">
                    <span class="text-xs font-bold text-emerald-600 uppercase tracking-wider">লাভের হিসাব</span>
                    <h2 class="text-2xl sm:text-3xl font-black text-slate-900">
                        আপনার দোকানের মাসিক আয় ও সময় সাশ্রয়
                    </h2>
                </div>

                <div class="grid md:grid-cols-2 gap-8 items-center pt-4">
                    <div class="space-y-6">
                        <div>
                            <div class="flex justify-between text-xs sm:text-sm font-bold mb-2">
                                <span class="text-slate-600">দৈনিক গড়ে প্রিন্ট ও ফটোকপি পেজ:</span>
                                <span id="dailyPrintVal" class="text-emerald-600 font-black text-base">150 টি</span>
                            </div>
                            <input id="dailyPrintRange" type="range" min="30" max="600" step="10" value="150" class="w-full accent-emerald-500 h-2 bg-slate-200 rounded-lg cursor-pointer">
                        </div>

                        <div>
                            <div class="flex justify-between text-xs sm:text-sm font-bold mb-2">
                                <span class="text-slate-600">গড়ে প্রতি পেজ প্রিন্ট রেট (টাকা):</span>
                                <span id="rateVal" class="text-rose-500 font-black text-base">৳ 6</span>
                            </div>
                            <input id="rateRange" type="range" min="3" max="25" step="1" value="6" class="w-full accent-rose-500 h-2 bg-slate-200 rounded-lg cursor-pointer">
                        </div>

                        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-xs text-slate-700">
                            💡 প্রতি প্রিন্টে গড়ে ১.৫ মিনিট বাঁচলে, মাসে আপনার বাঁচবে <strong id="hoursSaved" class="text-emerald-700 font-bold">113 ঘণ্টা সময়</strong>!
                        </div>
                    </div>

                    <!-- Output Showcase -->
                    <div class="p-8 rounded-3xl bg-slate-900 text-white text-center space-y-3 shadow-xl">
                        <span class="text-xs uppercase tracking-wider font-bold text-slate-400">মাসিক আনুমানিক রেভিনিউ</span>
                        <div id="monthlyIncome" class="text-4xl sm:text-5xl font-black text-emerald-400 font-en">
                            ৳ 27,000
                        </div>
                        <p class="text-xs text-slate-400 pb-2">
                            (১০০% টাকা আপনার নিজস্ব একাউন্টে, ০% কমিশন)
                        </p>
                        <a href="#pricing" class="w-full inline-block py-3.5 px-6 rounded-xl bg-gradient-to-r from-emerald-400 to-cyan-400 text-slate-950 font-black text-xs hover:from-emerald-300 hover:to-cyan-300 transition-all shadow-md">
                            আজই সফটওয়্যারটি টেস্ট করুন
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- PRICING TIERS: Exact BDT Rates -->
    <section id="pricing" class="py-20 bg-white border-t border-slate-200">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-14 space-y-2">
                <span class="text-xs font-bold text-rose-500 uppercase tracking-wider">💰 সাশ্রয়ী প্যাকেজ</span>
                <h2 class="text-3xl font-black text-slate-900">
                    আপনার দোকানের জন্য প্ল্যান বেছে নিন
                </h2>
                <p class="text-sm text-slate-500">
                    কোনো প্রতি-প্রিন্ট কমিশন নেই। কাস্টমারের সম্পূর্ণ টাকা আপনার ওয়ালেটে।
                </p>
            </div>

            <div class="grid md:grid-cols-4 gap-6">
                <!-- Free Demo -->
                <div class="bg-slate-50 p-6 rounded-3xl border border-slate-200 flex flex-col justify-between">
                    <div>
                        <h3 class="text-base font-bold text-slate-900">ফ্রি ডেমো</h3>
                        <p class="text-[11px] text-slate-500 mt-0.5">টেস্ট করার জন্য সম্পূর্ণ ফ্রি</p>
                        <div class="my-5">
                            <span class="text-3xl font-black text-slate-900 font-en">৳ ০</span>
                            <span class="text-xs text-slate-500 ml-1">/ ২৪ ঘণ্টা</span>
                        </div>
                        <ul class="space-y-2.5 text-xs text-slate-600 border-t border-slate-200 pt-5">
                            <li>✓ ২০টি ফ্রি টেস্ট প্রিন্ট</li>
                            <li>✓ পার্সোনালাইজড QR পোস্টার</li>
                            <li>✓ উইন্ডোজ এজেন্ট ইনস্টলার</li>
                        </ul>
                    </div>
                    <button class="mt-6 w-full py-2.5 text-xs font-bold bg-white hover:bg-slate-100 text-slate-900 rounded-xl border border-slate-300 transition-all">
                        ফ্রি ট্রায়াল শুরু করুন
                    </button>
                </div>

                <!-- Monthly Starter -->
                <div class="bg-slate-50 p-6 rounded-3xl border border-slate-200 flex flex-col justify-between">
                    <div>
                        <h3 class="text-base font-bold text-slate-900">মান্থলি স্টার্টার</h3>
                        <p class="text-[11px] text-slate-500 mt-0.5">ছোট সাইবার ক্যাফের জন্য</p>
                        <div class="my-5">
                            <span class="text-3xl font-black text-slate-900 font-en">৳ ৯৯</span>
                            <span class="text-xs text-slate-500 ml-1">/ মাস</span>
                        </div>
                        <ul class="space-y-2.5 text-xs text-slate-600 border-t border-slate-200 pt-5">
                            <li>✓ আনলিমিটেড প্রিন্ট</li>
                            <li>✓ ২ প্রিন্টার কানেক্ট (B&W + কালার)</li>
                            <li>✓ বিকাশ/নগদ পেমেন্ট সাপোর্ট</li>
                            <li>✓ হোয়াটসঅ্যাপ সাপোর্ট</li>
                        </ul>
                    </div>
                    <button class="mt-6 w-full py-2.5 text-xs font-bold bg-slate-900 hover:bg-slate-800 text-white rounded-xl transition-all">
                        প্যাকেজ একটিভ করুন
                    </button>
                </div>

                <!-- Yearly Pro (Featured) -->
                <div class="bg-white p-6 rounded-3xl border-2 border-rose-500 relative flex flex-col justify-between shadow-xl shadow-rose-500/10">
                    <div class="absolute -top-3 left-1/2 -translate-x-1/2 px-3 py-0.5 rounded-full bg-rose-500 text-white text-[10px] font-black uppercase tracking-wider">
                        সবচেয়ে জনপ্রিয়
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900">ইয়ারলি প্রো</h3>
                        <p class="text-[11px] text-slate-500 mt-0.5">ব্যস্ত সাইবার ক্যাফে ও সেন্টারের জন্য</p>
                        <div class="my-5">
                            <span class="text-3xl font-black text-rose-600 font-en">৳ ৭৯৯</span>
                            <span class="text-xs text-slate-500 ml-1">/ ১ বছর</span>
                        </div>
                        <ul class="space-y-2.5 text-xs text-slate-700 border-t border-slate-200 pt-5 font-medium">
                            <li>✓ আনলিমিটেড প্রিন্ট</li>
                            <li>✓ অ্যান্ড্রয়েড SMS অটো-ম্যাচিং</li>
                            <li>✓ ৪x৬ পাসপোর্ট সাইজ ফটো মেকার</li>
                            <li>✓ ৫টি প্রিন্টার পর্যন্ত কানেকশন</li>
                            <li>✓ AnyDesk ও ফোন প্রায়োরিটি সাপোর্ট</li>
                        </ul>
                    </div>
                    <button class="mt-6 w-full py-2.5 text-xs font-black bg-rose-500 hover:bg-rose-600 text-white rounded-xl transition-all shadow-md">
                        প্রো প্ল্যান নিন
                    </button>
                </div>

                <!-- Lifetime -->
                <div class="bg-slate-50 p-6 rounded-3xl border border-slate-200 flex flex-col justify-between">
                    <div>
                        <h3 class="text-base font-bold text-slate-900">লাইফটাইম</h3>
                        <p class="text-[11px] text-slate-500 mt-0.5">এককালীন আজীবনের লাইসেন্স</p>
                        <div class="my-5">
                            <span class="text-3xl font-black text-purple-700 font-en">৳ ১,৯৯৯</span>
                            <span class="text-xs text-slate-500 ml-1">/ এককালীন</span>
                        </div>
                        <ul class="space-y-2.5 text-xs text-slate-600 border-t border-slate-200 pt-5">
                            <li>✓ কখনো আর কোনো বিল দিতে হবে না</li>
                            <li>✓ সব অ্যাডভান্স ফিচার আজীবন আনলক</li>
                            <li>✓ ভবিষ্যতের সব আপডেট ফ্রি</li>
                            <li>✓ ভিআইপি প্রায়োরিটি সাপোর্ট</li>
                        </ul>
                    </div>
                    <button class="mt-6 w-full py-2.5 text-xs font-bold bg-white hover:bg-slate-100 text-slate-900 rounded-xl border border-slate-300 transition-all">
                        লাইফটাইম লাইসেন্স নিন
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ SECTION: সচরাচর জিজ্ঞাসিত প্রশ্নাবলী -->
    <section id="faq" class="py-20 bg-slate-50/80 border-t border-slate-200">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-14 space-y-2">
                <span class="text-xs font-bold text-rose-500 uppercase tracking-wider">❓ সাধারণ জিজ্ঞাসা</span>
                <h2 class="text-3xl font-black text-slate-900">
                    সচরাচর জিজ্ঞাসিত প্রশ্ন ও উত্তর
                </h2>
                <p class="text-sm text-slate-600">
                    দোকানদার ভাইদের মনে সচরাচর যেসব প্রশ্ন আসে
                </p>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <!-- Q1 -->
                <div class="bg-white p-6 sm:p-7 rounded-3xl border border-slate-200 card-shadow space-y-2.5">
                    <h3 class="text-base font-bold text-slate-900 flex items-start gap-2">
                        <span class="text-rose-500 font-black">Q.</span>
                        <span>আমার দোকানে কি নতুন দামি ওয়াইফাই প্রিন্টার কেনা লাগবে?</span>
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed pl-6">
                        <strong>একদমই না!</strong> আপনার দোকানে বর্তমানে যে ইউএসবি ক্যাবলযুক্ত প্রিন্টার (HP, Canon, Epson, Brother) আছে, সেটির সাথেই আমাদের উইন্ডোজ সফটওয়্যার নিখুঁতভাবে কাজ করবে। নতুন কোনো হার্ডওয়্যার কেনা লাগবে না।
                    </p>
                </div>

                <!-- Q2 -->
                <div class="bg-white p-6 sm:p-7 rounded-3xl border border-slate-200 card-shadow space-y-2.5">
                    <h3 class="text-base font-bold text-slate-900 flex items-start gap-2">
                        <span class="text-rose-500 font-black">Q.</span>
                        <span>বিকাশ বা নগদের পেমেন্ট কি সরাসরি আমার একাউন্টে আসবে?</span>
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed pl-6">
                        <strong>হ্যাঁ, ১০০% সরাসরি!</strong> কাস্টমারের টাকা সরাসরি আপনার ব্যক্তিগত বিকাশ বা নগদ একাউন্টে সেন্ড মানি হবে। আমরা কোনো মধ্যস্বত্বভোগী নই এবং কোনো ট্রানজেকশন ফি বা কমিশন কাটি না।
                    </p>
                </div>

                <!-- Q3 -->
                <div class="bg-white p-6 sm:p-7 rounded-3xl border border-slate-200 card-shadow space-y-2.5">
                    <h3 class="text-base font-bold text-slate-900 flex items-start gap-2">
                        <span class="text-rose-500 font-black">Q.</span>
                        <span>ইন্টারনেট বা বিদ্যুৎ সাময়িক চলে গেলে কী হবে?</span>
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed pl-6">
                        আমাদের সফটওয়্যারে রয়েছে <strong>ফল্ট-টলারেন্ট অফলাইন কিউ</strong>। বিদ্যুৎ বা নেট ডিসকানেক্ট হলেও কাস্টমারের পাঠানো প্রিন্ট টাস্ক ড্রপ হয় না; বিদ্যুৎ আসতেই স্বয়ংক্রিয়ভাবে সিরিয়ালে প্রিন্ট বের হতে শুরু করবে।
                    </p>
                </div>

                <!-- Q4 -->
                <div class="bg-white p-6 sm:p-7 rounded-3xl border border-slate-200 card-shadow space-y-2.5">
                    <h3 class="text-base font-bold text-slate-900 flex items-start gap-2">
                        <span class="text-rose-500 font-black">Q.</span>
                        <span>কাস্টমারের ব্যক্তিগত ফাইল ও তথ্যের নিরাপত্তা কেমন?</span>
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed pl-6">
                        প্রিন্ট সেবা কঠোরভাবে <strong>জিরো-ডাটা রিটেনশন</strong> নীতি অনুসরণ করে। প্রিন্টারের পেপার ট্রে থেকে পেজ বের হওয়ামাত্র সার্ভার ও দোকানদারের পিসি মেমোরি থেকে ফাইল চিরতরে মুছে ফেলা হয়।
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- PRE-FOOTER CALL TO ACTION BANNER -->
    <section class="py-16 bg-gradient-to-br from-slate-900 via-slate-950 to-black text-white relative overflow-hidden border-t border-slate-800">
        <!-- Ambient subtle glow circles -->
        <div class="absolute -top-24 -left-24 w-80 h-80 bg-rose-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-80 h-80 bg-purple-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="bg-slate-900/80 backdrop-blur-md rounded-3xl border border-slate-800 p-8 sm:p-12 card-shadow flex flex-col lg:flex-row items-center justify-between gap-8 text-center lg:text-left">
                <div class="space-y-3 max-w-2xl">
                    <span class="px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-400 text-xs font-bold uppercase tracking-wider inline-block">
                        ⚡ এখনই শুরু করুন
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-black text-white tracking-tight">
                        আজই আপনার দোকানে <span class="text-transparent bg-clip-text bg-gradient-to-r from-rose-400 to-pink-400">PrintSheba</span> চালু করুন!
                    </h2>
                    <p class="text-slate-400 text-xs sm:text-sm leading-relaxed">
                        হোয়াটসঅ্যাপ জটলা, পেনড্রাইভের ভাইরাস আর বাকি টাকার ঝামেলা থেকে আজই মুক্তি পান। ফ্রি ট্রায়াল বা লাইভ ডেমো দেখতে এখনই আমাদের সাথে যোগাযোগ করুন।
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row gap-4 shrink-0 w-full lg:w-auto">
                    <a href="tel:01644416378" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-2xl bg-rose-500 hover:bg-rose-600 text-white font-bold text-sm shadow-lg shadow-rose-500/30 transition-all hover:scale-105">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        <span>কল করুন: 01644416378</span>
                    </a>
                    <a href="https://wa.me/8801644416378?text=Hello%20PrintSheba%2C%20I%20want%20to%20know%20more" target="_blank" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-2xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm shadow-lg shadow-emerald-600/30 transition-all hover:scale-105">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                        <span>হোয়াটসঅ্যাপ চ্যাট</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- COMPREHENSIVE REDESIGNED FOOTER: Developer Info & Full Details -->
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

                    <!-- Live System Status Badge -->
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-slate-900 border border-slate-800 text-[11px] font-semibold text-slate-300">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span class="text-emerald-400 font-bold">সকল সিস্টেম সচল ও লাইভ</span>
                        <span class="text-slate-500">|</span>
                        <span>৯৯.৯% আপটাইম</span>
                    </div>

                    <div class="pt-2 text-[11px] text-slate-500">
                        সাপোর্টেড প্রিন্টার: HP • Canon • Epson • Brother • Pantum
                    </div>
                </div>

                <!-- Col 2: Quick Links (2 Cols) -->
                <div class="lg:col-span-2 space-y-3">
                    <h3 class="text-white font-bold text-sm tracking-wide uppercase">
                        দ্রুত লিংক
                    </h3>
                    <ul class="space-y-2 text-xs text-slate-400">
                        <li><a href="#hero" class="hover:text-rose-400 transition-colors">হোমপেজ</a></li>
                        <li><a href="#features" class="hover:text-rose-400 transition-colors">সকল ফিচারসমূহ</a></li>
                        <li><a href="#setup-guide" class="hover:text-rose-400 transition-colors">কীভাবে কাজ করে</a></li>
                        <li><a href="#calculator" class="hover:text-rose-400 transition-colors">আয়ের ক্যালকুলেটর</a></li>
                        <li><a href="#pricing" class="hover:text-rose-400 transition-colors">প্যাকেজ ও রেট</a></li>
                        <li><a href="#faq" class="hover:text-rose-400 transition-colors">প্রশ্নোত্তর (FAQ)</a></li>
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
                        <li class="flex items-center gap-1.5"><span class="text-rose-500">›</span> পেজ রেঞ্জ ও ডুপ্লেক্স কন্ট্রোল</li>
                    </ul>
                </div>

                <!-- Col 4: Contact & MR Developer Info (3 Cols) -->
                <div class="lg:col-span-3 space-y-4">
                    <h3 class="text-white font-bold text-sm tracking-wide uppercase">
                        যোগাযোগ ও সাপোর্ট
                    </h3>
                    
                    <div class="space-y-2.5 text-xs text-slate-400">
                        <!-- Phone / Hotline -->
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

                        <!-- Email -->
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

                        <!-- Support Hours -->
                        <div class="flex items-start gap-2.5">
                            <div class="w-7 h-7 rounded-lg bg-emerald-500/10 text-emerald-400 flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <span class="text-[10px] uppercase text-slate-500 block font-bold">সাপোর্ট আওয়ার্স</span>
                                <span class="text-slate-300">সকাল ৯:০০ - রাত ১১:০০ (প্রতিদিন)</span>
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
                    <span class="hidden sm:inline"> • </span>
                    <span class="block sm:inline">মেড উইথ ❤️ সাইবার ক্যাফে ও প্রিন্টিং ব্যবসার জন্য।</span>
                </div>

                <div class="flex items-center gap-4 text-slate-400">
                    <span>Designed & Developed by <a href="https://mrdeveloper.xyz" target="_blank" rel="noopener noreferrer" class="text-rose-400 hover:text-rose-300 font-bold underline font-en">MR Developer</a></span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Interactive Simulator Scripts -->
    <script>
        // Calculator Logic
        const dailyRange = document.getElementById('dailyPrintRange');
        const rateRange = document.getElementById('rateRange');
        const dailyPrintVal = document.getElementById('dailyPrintVal');
        const rateVal = document.getElementById('rateVal');
        const monthlyIncome = document.getElementById('monthlyIncome');
        const hoursSaved = document.getElementById('hoursSaved');

        function updateCalc() {
            const prints = parseInt(dailyRange.value);
            const rate = parseInt(rateRange.value);
            dailyPrintVal.innerText = prints + ' টি';
            rateVal.innerText = '৳ ' + rate;
            const total = prints * rate * 30;
            monthlyIncome.innerText = '৳ ' + total.toLocaleString('en-US');
            const hours = Math.round((prints * 1.5 * 30) / 60);
            hoursSaved.innerText = hours + ' ঘণ্টা সময়';
        }

        dailyRange.addEventListener('input', updateCalc);
        rateRange.addEventListener('input', updateCalc);

        // Simulator button celebration
        const simBtn = document.getElementById('simBtn');
        if (simBtn) {
            simBtn.addEventListener('click', () => {
                confetti({
                    particleCount: 80,
                    spread: 70,
                    origin: { y: 0.6 }
                });
            });
        }
    </script>
</body>
</html>
