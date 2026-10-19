<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'শপ ড্যাশবোর্ড') – PrintSheba</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('logo.svg') }}">
    <link rel="apple-touch-icon" href="{{ asset('icon-192.png') }}">

    <!-- PWA Web App Manifest & Mobile Integration -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#0b0f19">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="PrintSheba">

    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Hind Siliguri', sans-serif;
            background-color: #0b0f19;
        }
        .font-en { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        /* Modern subtle scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: rgba(15, 23, 42, 0.6);
        }
        ::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #f43f5e;
        }

        /* Print Media Styles: Hide all navigation, sidebars, headers & backgrounds */
        @media print {
            aside, header, footer, #mobileDrawer, .print\:hidden {
                display: none !important;
            }
            body, html {
                background: #ffffff !important;
                color: #000000 !important;
                margin: 0 !important;
                padding: 0 !important;
                min-height: auto !important;
            }
            main {
                background: #ffffff !important;
                padding: 0 !important;
                margin: 0 !important;
                max-width: 100% !important;
                width: 100% !important;
            }
            .min-h-screen {
                min-height: auto !important;
            }
        }
    </style>
</head>
<body class="min-h-screen bg-slate-950 text-slate-100 flex flex-col antialiased">

    <div class="flex-1 flex w-full">
        <!-- SIDEBAR (DESKTOP) -->
        <aside class="hidden lg:flex flex-col w-72 bg-slate-900/90 border-r border-slate-800/80 backdrop-blur-xl shrink-0 sticky top-0 h-screen z-40">
            <!-- Brand Logo Area -->
            <div class="p-6 border-b border-slate-800/80 flex items-center justify-between">
                <a href="{{ route('shop.dashboard') }}" class="flex items-center gap-3 group">
                    <img src="{{ asset('logo.svg') }}" alt="PrintSheba Logo" class="w-10 h-10 rounded-2xl shadow-lg shadow-rose-500/20 group-hover:scale-105 transition-transform duration-300">
                    <div>
                        <div class="flex items-center gap-1.5">
                            <span class="font-black text-white font-en text-lg tracking-tight">Print<span class="text-rose-500">Sheba</span></span>
                        </div>
                        <span class="text-[9px] uppercase tracking-widest px-2 py-0.5 rounded-full bg-rose-500/10 text-rose-400 font-extrabold border border-rose-500/20">SHOP PARTNER</span>
                    </div>
                </a>
            </div>

            <!-- Shop Hardware & App Dual Live Indicator -->
            <div class="p-3.5 mx-4 my-3 rounded-2xl bg-slate-950/80 border border-slate-800 space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-slate-300 truncate max-w-[140px]">{{ $shop->name ?? 'দোকানের নাম' }}</span>
                    <span class="text-[9px] font-mono text-slate-500">#{{ $shop->id ?? '---' }}</span>
                </div>

                <!-- Windows Agent Status -->
                <div class="flex items-center justify-between pt-1 border-t border-slate-800/80">
                    <div class="flex items-center gap-1.5">
                        <span class="text-xs">💻</span>
                        <span class="text-[10px] text-slate-400">উইন্ডোজ এজেন্ট:</span>
                    </div>
                    @php
                        $isAgentOnline = $shop && $shop->last_agent_heartbeat_at && $shop->last_agent_heartbeat_at->gte(now()->subMinutes(3));
                    @endphp
                    <span id="agentStatusBadge" class="inline-flex items-center gap-1 text-[9px] font-bold px-2 py-0.5 rounded-full {{ $isAgentOnline ? 'text-emerald-400 bg-emerald-500/10 border border-emerald-500/20' : 'text-amber-400 bg-amber-500/10 border border-amber-500/20' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $isAgentOnline ? 'bg-emerald-400 animate-ping' : 'bg-amber-400' }}"></span>
                        {{ $isAgentOnline ? 'অনলাইন' : 'অফলাইন' }}
                    </span>
                </div>

                <!-- Android App Status -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-1.5">
                        <span class="text-xs">📱</span>
                        <span class="text-[10px] text-slate-400">এসএমএস অ্যাপ:</span>
                    </div>
                    @php
                        $isAppOnline = $shop && $shop->last_app_heartbeat_at && $shop->last_app_heartbeat_at->gte(now()->subMinutes(3));
                    @endphp
                    <span id="appStatusBadge" class="inline-flex items-center gap-1 text-[9px] font-bold px-2 py-0.5 rounded-full {{ $isAppOnline ? 'text-emerald-400 bg-emerald-500/10 border border-emerald-500/20' : 'text-slate-400 bg-slate-800 border border-slate-700' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $isAppOnline ? 'bg-emerald-400 animate-ping' : 'bg-slate-500' }}"></span>
                        {{ $isAppOnline ? 'কানেক্টেড' : 'ডিসকানেক্ট' }}
                    </span>
                </div>
            </div>

            <!-- Sidebar Navigation Menu -->
            <nav class="flex-1 px-4 py-2 space-y-1.5 overflow-y-auto">
                <div class="text-[10px] font-bold tracking-wider text-slate-500 uppercase px-3 py-1">প্রধান মেনু</div>

                <a href="{{ route('shop.dashboard') }}" 
                   class="flex items-center gap-3 px-3.5 py-3 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('shop.dashboard') ? 'bg-gradient-to-r from-rose-500 to-rose-600 text-white shadow-lg shadow-rose-500/25' : 'text-slate-300 hover:bg-slate-800/70 hover:text-white' }}">
                    <span class="text-lg">📊</span>
                    <span>লাইভ কিউ ও আয়</span>
                </a>

                <a href="{{ route('shop.quick_print') }}" 
                   class="flex items-center justify-between px-3.5 py-3 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('shop.quick_print') ? 'bg-gradient-to-r from-emerald-500 to-teal-600 text-white shadow-lg shadow-emerald-500/25' : 'text-slate-300 hover:bg-slate-800/70 hover:text-white' }}">
                    <span class="flex items-center gap-3">
                        <span class="text-lg">⚡</span>
                        <span>ইনস্ট্যান্ট / শেয়ার প্রিন্ট</span>
                    </span>
                    <span class="text-[9px] font-black px-1.5 py-0.5 rounded-md bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">NEW</span>
                </a>

                <a href="{{ route('shop.qr_poster') }}" 
                   class="flex items-center gap-3 px-3.5 py-3 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('shop.qr_poster') ? 'bg-gradient-to-r from-rose-500 to-rose-600 text-white shadow-lg shadow-rose-500/25' : 'text-slate-300 hover:bg-slate-800/70 hover:text-white' }}">
                    <span class="text-lg">🖼️</span>
                    <span>কাউন্টার QR পোস্টার</span>
                </a>

                <a href="{{ route('shop.rates') }}" 
                   class="flex items-center gap-3 px-3.5 py-3 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('shop.rates') ? 'bg-gradient-to-r from-rose-500 to-rose-600 text-white shadow-lg shadow-rose-500/25' : 'text-slate-300 hover:bg-slate-800/70 hover:text-white' }}">
                    <span class="text-lg">💰</span>
                    <span>রেট ও পেমেন্ট নম্বর</span>
                </a>

                <a href="{{ route('shop.printers') }}" 
                   class="flex items-center gap-3 px-3.5 py-3 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('shop.printers') ? 'bg-gradient-to-r from-rose-500 to-rose-600 text-white shadow-lg shadow-rose-500/25' : 'text-slate-300 hover:bg-slate-800/70 hover:text-white' }}">
                    <span class="text-lg">🖨️</span>
                    <span>প্রিন্টার সেটিংস</span>
                </a>

                <a href="{{ route('shop.agent_setup') }}" 
                   class="flex items-center gap-3 px-3.5 py-3 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('shop.agent_setup') ? 'bg-gradient-to-r from-rose-500 to-rose-600 text-white shadow-lg shadow-rose-500/25' : 'text-slate-300 hover:bg-slate-800/70 hover:text-white' }}">
                    <span class="text-lg">💻</span>
                    <span>উইন্ডোজ এজেন্ট (.EXE)</span>
                </a>

                <a href="{{ route('shop.subscription') }}" 
                   class="flex items-center justify-between px-3.5 py-3 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('shop.subscription') ? 'bg-gradient-to-r from-rose-500 to-rose-600 text-white shadow-lg shadow-rose-500/25' : 'text-slate-300 hover:bg-slate-800/70 hover:text-white' }}">
                    <span class="flex items-center gap-3">
                        <span class="text-lg">💎</span>
                        <span>প্যাকেজ ও বিলিং</span>
                    </span>
                    @if(isset($shop) && $shop->is_lifetime)
                        <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-purple-500/20 text-purple-300 border border-purple-500/30">LIFETIME</span>
                    @elseif(isset($shop) && !$shop->isSubscriptionActive())
                        <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-rose-500/20 text-rose-400 border border-rose-500/30 animate-pulse">EXPIRED</span>
                    @endif
                </a>

                <div class="pt-4 text-[10px] font-bold tracking-wider text-slate-500 uppercase px-3 py-1">কাস্টমার পোর্টাল</div>

                @if(isset($shop))
                <a href="{{ route('customer.upload', $shop->slug) }}" target="_blank" 
                   class="flex items-center justify-between px-3.5 py-3 rounded-xl text-sm font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 hover:bg-emerald-500/20 transition-all">
                    <span class="flex items-center gap-3">
                        <span class="text-lg">📱</span>
                        <span>কাস্টমার স্ক্রিন দেখুন</span>
                    </span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                </a>
                @endif
            </nav>

            <!-- Bottom User & Logout -->
            <div class="p-4 border-t border-slate-800/80 bg-slate-950/40">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2.5 overflow-hidden">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-rose-500 to-pink-500 text-white font-bold flex items-center justify-center text-xs shrink-0">
                            {{ mb_substr(auth()->user()->name ?? 'U', 0, 1) }}
                        </div>
                        <div class="truncate">
                            <p class="text-xs font-bold text-white truncate">{{ auth()->user()->name ?? 'দোকানদার' }}</p>
                            <p class="text-[10px] text-slate-400 truncate">{{ auth()->user()->email ?? '' }}</p>
                        </div>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" title="লগআউট" class="p-2 rounded-xl text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- MAIN LAYOUT AREA -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Mobile Header -->
            <header class="lg:hidden bg-slate-900 border-b border-slate-800 px-4 py-3 flex items-center justify-between sticky top-0 z-50">
                <a href="{{ route('shop.dashboard') }}" class="flex items-center gap-2">
                    <img src="{{ asset('logo.svg') }}" alt="PrintSheba Logo" class="w-8 h-8 rounded-xl shadow">
                    <span class="font-black text-white font-en text-base">Print<span class="text-rose-500">Sheba</span></span>
                </a>
                <div class="flex items-center gap-2">
                    @if(isset($shop))
                    <a href="{{ route('customer.upload', $shop->slug) }}" target="_blank" class="px-2.5 py-1 rounded-lg bg-emerald-500/20 text-emerald-400 text-xs font-bold border border-emerald-500/30">
                        📱 কাস্টমার পেজ
                    </a>
                    @endif
                    <button id="mobileMenuToggle" class="p-2 rounded-xl text-slate-300 hover:bg-slate-800 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                    </button>
                </div>
            </header>

            <!-- Mobile Drawer Menu -->
            <div id="mobileDrawer" class="fixed inset-0 bg-black/70 backdrop-blur-sm z-50 hidden lg:hidden">
                <div class="w-72 bg-slate-900 h-full p-5 flex flex-col justify-between overflow-y-auto">
                    <div>
                        <div class="flex items-center justify-between pb-4 border-b border-slate-800">
                            <div class="flex items-center gap-2.5">
                                <img src="{{ asset('logo.svg') }}" alt="Logo" class="w-8 h-8 rounded-xl">
                                <span class="font-black text-white font-en text-lg">Print<span class="text-rose-500">Sheba</span></span>
                            </div>
                            <button id="closeDrawer" class="text-slate-400 hover:text-white text-xl font-bold">&times;</button>
                        </div>
                        <nav class="mt-4 space-y-1.5">
                            <a href="{{ route('shop.dashboard') }}" class="block px-3.5 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('shop.dashboard') ? 'bg-rose-500 text-white' : 'text-slate-300 hover:bg-slate-800' }}">📊 লাইভ কিউ ও আয়</a>
                            <a href="{{ route('shop.quick_print') }}" class="block px-3.5 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('shop.quick_print') ? 'bg-emerald-500 text-white' : 'text-slate-300 hover:bg-slate-800' }}">⚡ ইনস্ট্যান্ট / শেয়ার প্রিন্ট</a>
                            <a href="{{ route('shop.qr_poster') }}" class="block px-3.5 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('shop.qr_poster') ? 'bg-rose-500 text-white' : 'text-slate-300 hover:bg-slate-800' }}">🖼️ কিউআর পোস্টার</a>
                            <a href="{{ route('shop.rates') }}" class="block px-3.5 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('shop.rates') ? 'bg-rose-500 text-white' : 'text-slate-300 hover:bg-slate-800' }}">💰 রেট ও পেমেন্ট নম্বর</a>
                            <a href="{{ route('shop.printers') }}" class="block px-3.5 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('shop.printers') ? 'bg-rose-500 text-white' : 'text-slate-300 hover:bg-slate-800' }}">🖨️ প্রিন্টার সেটিংস</a>
                            <a href="{{ route('shop.agent_setup') }}" class="block px-3.5 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('shop.agent_setup') ? 'bg-rose-500 text-white' : 'text-slate-300 hover:bg-slate-800' }}">💻 উইন্ডোজ সফটওয়্যার</a>
                            <a href="{{ route('shop.subscription') }}" class="block px-3.5 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('shop.subscription') ? 'bg-rose-500 text-white' : 'text-slate-300 hover:bg-slate-800' }}">💎 প্যাকেজ ও বিলিং</a>
                        </nav>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" class="pt-4 border-t border-slate-800">
                        @csrf
                        <button type="submit" class="w-full py-2.5 rounded-xl text-xs font-bold bg-rose-500/20 text-rose-400 hover:bg-rose-500 hover:text-white transition-all text-center">
                            লগআউট করুন
                        </button>
                    </form>
                </div>
            </div>

            <!-- Page Content Container -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8 max-w-7xl w-full mx-auto bg-slate-950">
                @if(session('impersonator_id'))
                    <!-- Super Admin Impersonation Notice Bar -->
                    <div class="mb-6 p-4 rounded-2xl bg-gradient-to-r from-amber-500/20 via-rose-500/20 to-amber-500/20 border-2 border-amber-500/50 text-white flex flex-col sm:flex-row items-center justify-between gap-3 shadow-xl">
                        <div class="flex items-center gap-3">
                            <span class="text-2xl animate-bounce">⚡</span>
                            <div>
                                <span class="font-black text-amber-300 text-sm block">সুপার এডমিন লাইভ মোড (Super Admin Impersonation)</span>
                                <span class="text-xs text-slate-300">আপনি বর্তমানে দোকানদার হিসেবে সিস্টেমটি পরীক্ষা করছেন।</span>
                            </div>
                        </div>
                        <a href="{{ route('admin.revert_impersonation') }}" class="px-5 py-2 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-black text-xs shadow-lg transition-all flex items-center gap-1.5 shrink-0">
                            <span>🔙 সুপার এডমিনে ফিরে যান</span>
                        </a>
                    </div>
                @endif

                @if(session('success'))
                    <div class="mb-6 p-4 rounded-2xl bg-emerald-500/15 border border-emerald-500/30 text-emerald-300 text-xs sm:text-sm font-bold flex items-center justify-between">
                        <span>✓ {{ session('success') }}</span>
                        <span class="text-xs text-emerald-400 bg-emerald-500/20 px-2 py-0.5 rounded-md">সফল</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 rounded-2xl bg-rose-500/15 border border-rose-500/30 text-rose-300 text-xs sm:text-sm font-bold flex items-center justify-between">
                        <span>⚠ {{ session('error') }}</span>
                        <span class="text-xs text-rose-400 bg-rose-500/20 px-2 py-0.5 rounded-md">ত্রুটি</span>
                    </div>
                @endif

                @yield('content')
            </main>

            <!-- Minimal Dashboard Footer -->
            <footer class="border-t border-slate-800/80 px-6 py-4 text-center text-xs text-slate-500">
                PrintSheba Platform • Powered by <a href="https://mrdeveloper.xyz" target="_blank" class="text-rose-400 font-bold hover:underline">MR Developer</a>
            </footer>
        </div>
    </div>

    <script>
        const mobileBtn = document.getElementById('mobileMenuToggle');
        const drawer = document.getElementById('mobileDrawer');
        const closeBtn = document.getElementById('closeDrawer');
        if (mobileBtn && drawer && closeBtn) {
            mobileBtn.addEventListener('click', () => drawer.classList.remove('hidden'));
            closeBtn.addEventListener('click', () => drawer.classList.add('hidden'));
            drawer.addEventListener('click', (e) => {
                if (e.target === drawer) drawer.classList.add('hidden');
            });
        }

        // Live Device Sync Poller (Checks Windows Agent & Android App status)
        function pollSyncStatus() {
            fetch('{{ route("shop.sync_status") }}')
                .then(r => r.json())
                .then(d => {
                    const agentBadge = document.getElementById('agentStatusBadge');
                    const appBadge = document.getElementById('appStatusBadge');

                    if (agentBadge) {
                        if (d.agent_online) {
                            agentBadge.className = 'inline-flex items-center gap-1 text-[9px] font-bold px-2 py-0.5 rounded-full text-emerald-400 bg-emerald-500/10 border border-emerald-500/20';
                            agentBadge.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-ping"></span>অনলাইন';
                        } else {
                            agentBadge.className = 'inline-flex items-center gap-1 text-[9px] font-bold px-2 py-0.5 rounded-full text-amber-400 bg-amber-500/10 border border-amber-500/20';
                            agentBadge.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>অফলাইন';
                        }
                    }

                    if (appBadge) {
                        if (d.app_online) {
                            appBadge.className = 'inline-flex items-center gap-1 text-[9px] font-bold px-2 py-0.5 rounded-full text-emerald-400 bg-emerald-500/10 border border-emerald-500/20';
                            appBadge.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-ping"></span>কানেক্টেড';
                        } else {
                            appBadge.className = 'inline-flex items-center gap-1 text-[9px] font-bold px-2 py-0.5 rounded-full text-slate-400 bg-slate-800 border border-slate-700';
                            appBadge.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span>ডিসকানেক্ট';
                        }
                    }
                })
                .catch(() => {});
        }

        setInterval(pollSyncStatus, 5000);

        // Register PWA Service Worker for Instant Web Share Target
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then((reg) => console.log('PrintSheba PWA SW active:', reg.scope))
                    .catch((err) => console.warn('PWA SW registration failed:', err));
            });
        }

        // PWA Install Prompt Capture
        let deferredPrompt;
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            const pwaBanner = document.getElementById('pwaInstallBanner');
            if (pwaBanner) {
                pwaBanner.classList.remove('hidden');
            }
        });

        window.installPrintShebaPwa = function() {
            if (deferredPrompt) {
                deferredPrompt.prompt();
                deferredPrompt.userChoice.then((choice) => {
                    if (choice.outcome === 'accepted') {
                        console.log('User installed PrintSheba PWA');
                    }
                    deferredPrompt = null;
                    const pwaBanner = document.getElementById('pwaInstallBanner');
                    if (pwaBanner) pwaBanner.classList.add('hidden');
                });
            } else {
                alert('আপনার ব্রাউজারের মেনু (তিন ডট ⋮) থেকে "Add to Home screen" বা "অ্যাপ ইনস্টল করুন" সিলেক্ট করুন।');
            }
        };
    </script>
</body>
</html>
