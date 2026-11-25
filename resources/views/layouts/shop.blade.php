<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'শপ ড্যাশবোর্ড') – PrintSheba</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('logo.svg') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('icon-192.png') }}">
    <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('icon-512.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">

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
                    <img src="{{ asset('logo.svg') }}" onerror="this.onerror=null;this.src='{{ asset('icon-192.png') }}';" alt="PrintSheba Logo" class="w-10 h-10 rounded-2xl shadow-lg shadow-rose-500/20 group-hover:scale-105 transition-transform duration-300">
                    <div>
                        <div class="flex items-center gap-1.5">
                            <span class="font-black text-white font-en text-lg tracking-tight">Print<span class="{{ (isset($shop) && $shop->is_office_mode) ? 'text-indigo-400' : 'text-rose-500' }}">Sheba</span></span>
                        </div>
                        @if(isset($shop) && $shop->is_office_mode)
                            <span class="text-[9px] uppercase tracking-widest px-2 py-0.5 rounded-full bg-indigo-500/20 text-indigo-300 font-extrabold border border-indigo-500/30">OFFICE PARTNER</span>
                        @else
                            <span class="text-[9px] uppercase tracking-widest px-2 py-0.5 rounded-full bg-rose-500/10 text-rose-400 font-extrabold border border-rose-500/20">SHOP PARTNER</span>
                        @endif
                    </div>
                </a>
            </div>

            <!-- Shop Hardware & App Dual Live Indicator -->
            <div class="p-3.5 mx-4 my-3 rounded-2xl bg-slate-950/80 border border-slate-800 space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-slate-300 truncate max-w-[140px]">{{ $shop->name ?? 'দোকানের নাম' }}</span>
                    <span class="text-[9px] font-mono text-slate-500">#{{ $shop->id ?? '---' }}</span>
                </div>

                @if(isset($shop) && !$shop->is_active)
                <div class="flex items-center justify-between pt-1 border-t border-red-500/30">
                    <div class="flex items-center gap-1.5">
                        <span class="text-xs">🚫</span>
                        <span class="text-[10px] text-red-400 font-bold">স্ট্যাটাস:</span>
                    </div>
                    <span class="inline-flex items-center gap-1 text-[9px] font-bold px-2 py-0.5 rounded-full text-red-300 bg-red-500/20 border border-red-500/40 animate-pulse">
                        <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span>
                        স্থগিত (Suspended)
                    </span>
                </div>
                @elseif(isset($shop) && !$shop->is_lifetime && !$shop->isSubscriptionActive())
                <div id="sidebarLicenseExpiredBlock" class="flex items-center justify-between pt-1 border-t border-rose-500/30">
                    <div class="flex items-center gap-1.5">
                        <span class="text-xs">⚠️</span>
                        <span class="text-[10px] text-rose-400 font-bold">লাইসেন্স:</span>
                    </div>
                    <span class="inline-flex items-center gap-1 text-[9px] font-bold px-2 py-0.5 rounded-full text-rose-300 bg-rose-500/20 border border-rose-500/40 animate-pulse">
                        <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span>
                        মেয়াদ শেষ
                    </span>
                </div>
                @endif

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

                @if(!isset($shop) || !$shop->is_office_mode)
                <!-- Android App Status (For Commercial Shops) -->
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
                @else
                <!-- Office Mode Badge -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-1.5">
                        <span class="text-xs">🏢</span>
                        <span class="text-[10px] text-slate-400">প্রিন্ট মোড:</span>
                    </div>
                    <span class="inline-flex items-center gap-1 text-[9px] font-bold px-2 py-0.5 rounded-full text-indigo-300 bg-indigo-500/10 border border-indigo-500/20">
                        সেন্ট্রাল নেটওয়ার্ক
                    </span>
                </div>
                @endif
            </div>

            <!-- Sidebar Navigation Menu -->
            <nav class="flex-1 px-4 py-2 space-y-1.5 overflow-y-auto">
                <div class="text-[10px] font-bold tracking-wider text-slate-500 uppercase px-3 py-1">প্রধান মেনু</div>

                @php
                    $isOffice = isset($shop) && $shop->is_office_mode;
                    $activeNavBg = $isOffice ? 'bg-gradient-to-r from-indigo-600 to-blue-600 text-white shadow-lg shadow-indigo-600/25' : 'bg-gradient-to-r from-rose-500 to-rose-600 text-white shadow-lg shadow-rose-500/25';
                @endphp

                <a href="{{ route('shop.dashboard') }}" 
                   class="flex items-center gap-3 px-3.5 py-3 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('shop.dashboard') ? $activeNavBg : 'text-slate-300 hover:bg-slate-800/70 hover:text-white' }}">
                    <span class="text-lg">📊</span>
                    <span>{{ $isOffice ? 'লাইভ প্রিন্ট কিউ' : 'লাইভ কিউ ও আয়' }}</span>
                </a>

                <a href="{{ route('shop.quick_print') }}" 
                   class="flex items-center justify-between px-3.5 py-3 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('shop.quick_print') ? 'bg-gradient-to-r from-emerald-500 to-teal-600 text-white shadow-lg shadow-emerald-500/25' : 'text-slate-300 hover:bg-slate-800/70 hover:text-white' }}">
                    <span class="flex items-center gap-3">
                        <span class="text-lg">⚡</span>
                        <span>ইনস্ট্যান্ট / শেয়ার প্রিন্ট</span>
                    </span>
                    <span class="text-[9px] font-black px-1.5 py-0.5 rounded-md bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">NEW</span>
                </a>

                <a href="{{ route('shop.reports') }}" 
                   class="flex items-center gap-3 px-3.5 py-3 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('shop.reports') ? $activeNavBg : 'text-slate-300 hover:bg-slate-800/70 hover:text-white' }}">
                    <span class="text-lg">📈</span>
                    <span>{{ $isOffice ? 'কর্মী ও পেপার অডিট' : 'আয় ও অডিট রিপোর্ট' }}</span>
                </a>

                <a href="{{ route('shop.qr_poster') }}" 
                   class="flex items-center gap-3 px-3.5 py-3 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('shop.qr_poster') ? $activeNavBg : 'text-slate-300 hover:bg-slate-800/70 hover:text-white' }}">
                    <span class="text-lg">🖼️</span>
                    <span>{{ $isOffice ? 'অফিস QR পোস্টার' : 'কাউন্টার QR পোস্টার' }}</span>
                </a>

                <a href="{{ route('shop.rates') }}" 
                   class="flex items-center gap-3 px-3.5 py-3 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('shop.rates') ? $activeNavBg : 'text-slate-300 hover:bg-slate-800/70 hover:text-white' }}">
                    <span class="text-lg">{{ $isOffice ? '⚙️' : '💰' }}</span>
                    <span>{{ $isOffice ? 'অফিস মোড সেটিংস' : 'রেট ও পেমেন্ট নম্বর' }}</span>
                </a>

                <a href="{{ route('shop.printers') }}" 
                   class="flex items-center gap-3 px-3.5 py-3 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('shop.printers') ? $activeNavBg : 'text-slate-300 hover:bg-slate-800/70 hover:text-white' }}">
                    <span class="text-lg">🖨️</span>
                    <span>প্রিন্টার সেটিংস</span>
                </a>

                <a href="{{ route('shop.agent_setup') }}" 
                   class="flex items-center gap-3 px-3.5 py-3 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('shop.agent_setup') ? $activeNavBg : 'text-slate-300 hover:bg-slate-800/70 hover:text-white' }}">
                    <span class="text-lg">💻</span>
                    <span>উইন্ডোজ এজেন্ট (.EXE)</span>
                </a>

                <a href="{{ route('shop.subscription') }}" 
                   class="flex items-center justify-between px-3.5 py-3 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('shop.subscription') ? $activeNavBg : 'text-slate-300 hover:bg-slate-800/70 hover:text-white' }}">
                    <span class="flex items-center gap-3">
                        <span class="text-lg">💎</span>
                        <span>{{ $isOffice ? 'কর্পোরেট লাইসেন্স' : 'প্যাকেজ ও বিলিং' }}</span>
                    </span>
                    @if(isset($shop) && $shop->is_lifetime)
                        <span id="sidebarSubNavBadge" class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-purple-500/20 text-purple-300 border border-purple-500/30">LIFETIME</span>
                    @elseif(isset($shop) && !$shop->isSubscriptionActive())
                        <span id="sidebarSubNavBadge" class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-rose-500/20 text-rose-400 border border-rose-500/30 animate-pulse">EXPIRED</span>
                    @else
                        <span id="sidebarSubNavBadge" class="hidden px-1.5 py-0.5 rounded text-[9px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">ACTIVE</span>
                    @endif
                </a>

                <div class="pt-4 text-[10px] font-bold tracking-wider text-slate-500 uppercase px-3 py-1">
                    {{ $isOffice ? 'কর্মীদের পোর্টাল' : 'কাস্টমার পোর্টাল' }}
                </div>

                @if(isset($shop))
                <a href="{{ $isOffice ? route('customer.office_upload', $shop->slug) : route('customer.upload', $shop->slug) }}" target="_blank" 
                   class="flex items-center justify-between px-3.5 py-3 rounded-xl text-sm font-semibold {{ $isOffice ? 'bg-indigo-500/10 text-indigo-400 border-indigo-500/20 hover:bg-indigo-500/20' : 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20 hover:bg-emerald-500/20' }} border transition-all">
                    <span class="flex items-center gap-3">
                        <span class="text-lg">{{ $isOffice ? '🏢' : '📱' }}</span>
                        <span>{{ $isOffice ? 'কর্মীদের স্ক্রিন দেখুন' : 'কাস্টমার স্ক্রিন দেখুন' }}</span>
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
                        <button type="submit" title="লগআউট" aria-label="সিস্টেম থেকে লগআউট করুন" class="p-2.5 rounded-xl text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 transition-colors min-h-[44px] min-w-[44px] flex items-center justify-center">
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
                    <img src="{{ asset('logo.svg') }}" onerror="this.onerror=null;this.src='{{ asset('icon-192.png') }}';" alt="PrintSheba Logo" class="w-8 h-8 rounded-xl shadow">
                    <span class="font-black text-white font-en text-base">Print<span class="{{ (isset($shop) && $shop->is_office_mode) ? 'text-indigo-400' : 'text-rose-500' }}">Sheba</span></span>
                </a>
                <div class="flex items-center gap-2">
                    @if(isset($shop))
                    <a href="{{ $shop->is_office_mode ? route('customer.office_upload', $shop->slug) : route('customer.upload', $shop->slug) }}" target="_blank" class="px-3 py-2 rounded-lg {{ $shop->is_office_mode ? 'bg-indigo-500/20 text-indigo-300 border-indigo-500/30' : 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30' }} text-xs font-bold border min-h-[44px] flex items-center justify-center">
                        {{ $shop->is_office_mode ? '🏢 অফিস পোর্টাল' : '📱 কাস্টমার পেজ' }}
                    </a>
                    @endif
                    <button id="mobileMenuToggle" aria-label="মোবাইল নেভিগেশন মেনু খুলুন" class="p-2.5 rounded-xl text-slate-300 hover:bg-slate-800 focus:outline-none min-h-[44px] min-w-[44px] flex items-center justify-center">
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
                                <img src="{{ asset('logo.svg') }}" onerror="this.onerror=null;this.src='{{ asset('icon-192.png') }}';" alt="Logo" class="w-8 h-8 rounded-xl">
                                <span class="font-black text-white font-en text-lg">Print<span class="text-rose-500">Sheba</span></span>
                            </div>
                            <button id="closeDrawer" aria-label="মেনু ড্রয়ার বন্ধ করুন" class="text-slate-400 hover:text-white text-xl font-bold p-2 min-h-[44px] min-w-[44px] flex items-center justify-center">&times;</button>
                        </div>
                        <nav class="mt-4 space-y-1.5">
                            @php
                                $mActiveBg = (isset($shop) && $shop->is_office_mode) ? 'bg-indigo-600 text-white' : 'bg-rose-500 text-white';
                            @endphp
                            <a href="{{ route('shop.dashboard') }}" class="block px-3.5 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('shop.dashboard') ? $mActiveBg : 'text-slate-300 hover:bg-slate-800' }}">📊 {{ (isset($shop) && $shop->is_office_mode) ? 'লাইভ প্রিন্ট কিউ' : 'লাইভ কিউ ও আয়' }}</a>
                            <a href="{{ route('shop.reports') }}" class="block px-3.5 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('shop.reports') ? $mActiveBg : 'text-slate-300 hover:bg-slate-800' }}">📈 {{ (isset($shop) && $shop->is_office_mode) ? 'কর্মী ও পেপার অডিট' : 'আয় ও অডিট রিপোর্ট' }}</a>
                            <a href="{{ route('shop.quick_print') }}" class="block px-3.5 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('shop.quick_print') ? 'bg-emerald-500 text-white' : 'text-slate-300 hover:bg-slate-800' }}">⚡ ইনস্ট্যান্ট / শেয়ার প্রিন্ট</a>
                            <a href="{{ route('shop.qr_poster') }}" class="block px-3.5 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('shop.qr_poster') ? $mActiveBg : 'text-slate-300 hover:bg-slate-800' }}">🖼️ {{ (isset($shop) && $shop->is_office_mode) ? 'অফিস QR পোস্টার' : 'কিউআর পোস্টার' }}</a>
                            <a href="{{ route('shop.rates') }}" class="block px-3.5 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('shop.rates') ? $mActiveBg : 'text-slate-300 hover:bg-slate-800' }}">{{ (isset($shop) && $shop->is_office_mode) ? '⚙️ অফিস মোড সেটিংস' : '💰 রেট ও পেমেন্ট নম্বর' }}</a>
                            <a href="{{ route('shop.printers') }}" class="block px-3.5 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('shop.printers') ? $mActiveBg : 'text-slate-300 hover:bg-slate-800' }}">🖨️ প্রিন্টার সেটিংস</a>
                            <a href="{{ route('shop.agent_setup') }}" class="block px-3.5 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('shop.agent_setup') ? $mActiveBg : 'text-slate-300 hover:bg-slate-800' }}">💻 উইন্ডোজ সফটওয়্যার</a>
                            <a href="{{ route('shop.subscription') }}" class="block px-3.5 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('shop.subscription') ? $mActiveBg : 'text-slate-300 hover:bg-slate-800' }}">💎 {{ (isset($shop) && $shop->is_office_mode) ? 'কর্পোরেট লাইসেন্স' : 'প্যাকেজ ও বিলিং' }}</a>
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
                @if(isset($shop) && !$shop->is_active)
                    <div class="mb-6 p-5 rounded-3xl bg-gradient-to-r from-red-950/90 via-red-900/40 to-slate-950 border-2 border-red-500 text-white flex flex-col sm:flex-row items-center justify-between gap-4 shadow-2xl backdrop-blur-sm">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-red-500/20 border border-red-500/40 text-red-400 flex items-center justify-center text-2xl shrink-0 animate-pulse">
                                🚫
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h4 class="text-sm sm:text-base font-black text-red-200">আপনার প্রতিষ্ঠানটি বর্তমানে স্থগিত (Suspended) রয়েছে!</h4>
                                    <span class="text-[9px] font-black uppercase tracking-wider px-2 py-0.5 rounded-full bg-red-500/30 text-red-300 border border-red-500/50">SUSPENDED</span>
                                </div>
                                <p class="text-xs text-red-300/90 mt-1 leading-relaxed">
                                    সুপার এডমিন কর্তৃক আপনার প্রতিষ্ঠানটির কার্যক্রমে সাময়িক বিরতি দেওয়া হয়েছে। উইন্ডোজ এজেন্ট সরাসরি কোনো প্রিন্ট অর্ডার প্রসেস করবে না। পুনরায় সচল করতে অবিলম্বে এডমিন সাপোর্টের সাথে যোগাযোগ করুন।
                                </p>
                            </div>
                        </div>
                        <a href="https://wa.me/8801644416378?text={{ urlencode('আমার প্রিন্টসেবা একাউন্টটি স্থগিত রয়েছে, পুনরায় সচল করতে সহায়তা চাই। শপ আইডি: #' . ($shop->id ?? '') . ' (' . ($shop->name ?? '') . ')') }}" target="_blank" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700 text-white font-bold text-xs shadow-lg shadow-red-500/20 shrink-0 transition-transform hover:scale-105 flex items-center gap-1.5">
                            <span>এডমিন সাপোর্ট (WhatsApp)</span> →
                        </a>
                    </div>
                @elseif(isset($shop) && !$shop->is_lifetime && !$shop->isSubscriptionActive() && !request()->routeIs('shop.subscription'))
                    <div id="topExpiredWarningBanner" class="mb-6 p-4 rounded-2xl bg-gradient-to-r from-rose-600/30 via-rose-500/20 to-rose-600/30 border border-rose-500/60 text-white flex flex-col sm:flex-row items-center justify-between gap-3 shadow-xl backdrop-blur-sm">
                        <div class="flex items-center gap-3">
                            <span class="w-10 h-10 rounded-xl bg-rose-500/30 text-rose-300 flex items-center justify-center text-xl shrink-0">⚠️</span>
                            <div>
                                <h4 class="text-sm font-black text-rose-300">{{ (isset($shop) && $shop->is_office_mode) ? 'আপনার অফিস লাইসেন্সের মেয়াদ শেষ হয়েছে!' : 'আপনার সফটওয়্যার সাবস্ক্রিপশনের মেয়াদ শেষ হয়েছে!' }}</h4>
                                <p class="text-xs text-slate-300 mt-0.5">{{ (isset($shop) && $shop->is_office_mode) ? 'অফিস কর্মীদের প্রিন্ট কিউ এবং ক্লাউড অটো-প্রিন্ট সচল রাখতে অনুগ্রহ করে কর্পোরেট লাইসেন্স রিনিউ করুন।' : 'কাস্টমার প্রিন্ট পোর্টাল এবং ব্যাকগ্রাউন্ড অটো-প্রিন্ট সাময়িকভাবে স্থগিত রয়েছে। আনলিমিটেড সার্ভিস সচল রাখতে এখনই প্যাকেজ রিনিউ করুন।' }}</p>
                            </div>
                        </div>
                        <a href="{{ route('shop.subscription') }}" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white font-bold text-xs shadow-md shrink-0 transition-transform hover:scale-105">
                            {{ (isset($shop) && $shop->is_office_mode) ? 'লাইসেন্স রিনিউ করুন →' : 'প্যাকেজ রিনিউ করুন →' }}
                        </a>
                    </div>
                @endif

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

        // Global Shop/Office Real-Time Subscription Status Poller (Updates without reload + Sound)
        @if(isset($shop))
        @php
            $initialLatestSub = \App\Models\ShopSubscription::where('shop_id', $shop->id)->latest()->first();
            $initialSubStatus = $initialLatestSub ? $initialLatestSub->status : 'none';
            $initialSubActive = ($shop->isSubscriptionActive() || $shop->is_lifetime) ? 'true' : 'false';
        @endphp
        let shopAudioCtx = null;
        function unlockShopAudio() {
            try {
                const AudioCtx = window.AudioContext || window.webkitAudioContext;
                if (AudioCtx && !shopAudioCtx) shopAudioCtx = new AudioCtx();
                if (shopAudioCtx && shopAudioCtx.state === 'suspended') shopAudioCtx.resume();
            } catch (e) {}
        }
        document.addEventListener('click', unlockShopAudio, { once: true });
        document.addEventListener('keydown', unlockShopAudio, { once: true });

        function playShopSubChime(isSuccess) {
            try {
                const AudioCtx = window.AudioContext || window.webkitAudioContext;
                if (!AudioCtx) return;
                const ctx = shopAudioCtx || new AudioCtx();
                if (ctx.state === 'suspended') ctx.resume();
                const now = ctx.currentTime;
                const notes = isSuccess
                    ? [
                        { freq: 523.25, start: 0, dur: 0.2 },    // C5
                        { freq: 659.25, start: 0.1, dur: 0.2 },  // E5
                        { freq: 783.99, start: 0.2, dur: 0.25 }, // G5
                        { freq: 1046.5, start: 0.32, dur: 0.45 } // C6
                      ]
                    : [
                        { freq: 440, start: 0, dur: 0.25 },
                        { freq: 349.23, start: 0.18, dur: 0.35 }
                      ];
                notes.forEach(n => {
                    const osc = ctx.createOscillator();
                    const gain = ctx.createGain();
                    osc.type = 'sine';
                    osc.frequency.setValueAtTime(n.freq, now + n.start);
                    gain.gain.setValueAtTime(0.15, now + n.start);
                    gain.gain.exponentialRampToValueAtTime(0.001, now + n.start + n.dur);
                    osc.connect(gain);
                    gain.connect(ctx.destination);
                    osc.start(now + n.start);
                    osc.stop(now + n.start + n.dur);
                });
            } catch (e) {}
        }

        function showShopSubLiveToast(title, body, isSuccess) {
            let toast = document.getElementById('shopSubLiveToast');
            if (!toast) {
                toast = document.createElement('div');
                toast.id = 'shopSubLiveToast';
                toast.className = 'fixed top-5 right-5 z-[100] max-w-sm w-full transition-all duration-300';
                document.body.appendChild(toast);
            }
            const borderClass = isSuccess ? 'border-emerald-500/70' : 'border-rose-500/70';
            const iconBg = isSuccess ? 'bg-emerald-500/20 text-emerald-400' : 'bg-rose-500/20 text-rose-400';
            const titleColor = isSuccess ? 'text-emerald-300' : 'text-rose-300';
            toast.innerHTML = `
                <div class="bg-slate-900 border-2 ${borderClass} text-white p-4 rounded-2xl shadow-2xl flex items-start gap-3 backdrop-blur-xl">
                    <div class="w-10 h-10 rounded-xl ${iconBg} flex items-center justify-center text-xl shrink-0">${isSuccess ? '🎉' : '❌'}</div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-black ${titleColor}">${title}</p>
                        <p class="text-xs text-slate-200 mt-0.5">${body}</p>
                    </div>
                    <button type="button" onclick="this.closest('#shopSubLiveToast').remove()" class="text-slate-400 hover:text-white text-sm font-bold">&times;</button>
                </div>`;
            setTimeout(() => { if (toast && toast.parentNode) toast.remove(); }, 9000);
        }

        let lastGlobalSubStatus = '{{ $initialSubStatus }}';
        let wasGlobalSubActive = {{ $initialSubActive }};

        window.pollShopSubscriptionStatus = function() {
            fetch('{{ route("shop.subscription.status") }}', {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.json())
            .then(data => {
                // Update global layout badges/banners if subscription became active
                if (data.is_active) {
                    const expiredBlock = document.getElementById('sidebarLicenseExpiredBlock');
                    if (expiredBlock) expiredBlock.classList.add('hidden');

                    const topBanner = document.getElementById('topExpiredWarningBanner');
                    if (topBanner) topBanner.classList.add('hidden');

                    const navBadge = document.getElementById('sidebarSubNavBadge');
                    if (navBadge) {
                        if (data.is_lifetime) {
                            navBadge.className = 'px-1.5 py-0.5 rounded text-[9px] font-bold bg-purple-500/20 text-purple-300 border border-purple-500/30';
                            navBadge.innerText = 'LIFETIME';
                        } else {
                            navBadge.className = 'px-1.5 py-0.5 rounded text-[9px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30';
                            navBadge.innerText = 'ACTIVE';
                        }
                    }
                }

                // Trigger sound + toast when status transitions to approved or active
                const newlyApproved = (data.status === 'approved' && lastGlobalSubStatus !== 'approved') ||
                                      (data.is_active && !wasGlobalSubActive);
                const newlyRejected = (data.status === 'rejected' && lastGlobalSubStatus === 'pending');

                if (newlyApproved) {
                    playShopSubChime(true);
                    showShopSubLiveToast('সাবস্ক্রিপশন অনুমোদিত ও সক্রিয়!', 'অভিনন্দন! আপনার প্যাকেজটি সফলভাবে চালু হয়েছে।', true);
                } else if (newlyRejected) {
                    playShopSubChime(false);
                    showShopSubLiveToast('আবেদন বাতিল হয়েছে', data.rejection_reason || 'অনুগ্রহ করে সঠিক TrxID দিয়ে আবার চেষ্টা করুন।', false);
                }

                lastGlobalSubStatus = data.status;
                wasGlobalSubActive = !!data.is_active;

                window.dispatchEvent(new CustomEvent('shop:subscription-updated', { detail: data }));
            })
            .catch(() => {});
        };

        setInterval(window.pollShopSubscriptionStatus, 5000);
        @endif

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
