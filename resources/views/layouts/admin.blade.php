<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'সুপার এডমিন কন্ট্রোল') – PrintSheba</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('logo.svg') }}">
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Hind Siliguri', sans-serif;
            background-color: #030712;
        }
        .font-en { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #090d16;
        }
        ::-webkit-scrollbar-thumb {
            background: #1f2937;
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #ef4444;
        }
    </style>
</head>
<body class="min-h-screen bg-slate-950 text-slate-100 flex flex-col antialiased">

    <div class="flex-1 flex w-full">
        <!-- ADMIN SIDEBAR (DESKTOP) -->
        <aside class="hidden lg:flex flex-col w-72 bg-slate-900 border-r border-slate-800 shrink-0 sticky top-0 h-screen z-40">
            <!-- Brand Logo Area -->
            <div class="p-6 border-b border-slate-800 flex items-center justify-between">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 group">
                    <img src="{{ asset('logo.svg') }}" alt="PrintSheba Logo" class="w-10 h-10 rounded-2xl shadow-xl shadow-rose-500/20 group-hover:scale-105 transition-transform duration-300">
                    <div>
                        <div class="flex items-center gap-1.5">
                            <span class="font-black text-white font-en text-lg tracking-tight">Print<span class="text-rose-500">Sheba</span></span>
                        </div>
                        <span class="text-[9px] uppercase tracking-widest px-2 py-0.5 rounded-full bg-rose-500/20 text-rose-400 font-black border border-rose-500/30">SUPER ADMIN</span>
                    </div>
                </a>
            </div>

            <!-- Quick Status Badge -->
            <div class="px-5 py-3 mx-4 my-3 rounded-2xl bg-slate-950 border border-slate-800 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-xs font-bold text-slate-300">সেন্ট্রাল সার্ভার সক্রিয়</span>
                </div>
                <span class="text-[10px] font-mono text-slate-500">v2.4</span>
            </div>

            <!-- Admin Navigation Menu -->
            <nav class="flex-1 px-4 py-2 space-y-1.5 overflow-y-auto">
                <div class="text-[10px] font-bold tracking-wider text-slate-500 uppercase px-3 py-1">এডমিন কন্ট্রোল</div>

                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center gap-3 px-3.5 py-3 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-gradient-to-r from-rose-600 to-rose-700 text-white shadow-lg shadow-rose-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <span class="text-lg">📊</span>
                    <span>সেন্ট্রাল ড্যাশবোর্ড</span>
                </a>

                <a href="{{ route('admin.shops') }}" 
                   class="flex items-center gap-3 px-3.5 py-3 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('admin.shops') ? 'bg-gradient-to-r from-rose-600 to-rose-700 text-white shadow-lg shadow-rose-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <span class="text-lg">🏪</span>
                    <span>দোকানসমূহ (Shops)</span>
                </a>

                <a href="{{ route('admin.subscriptions') }}" 
                   class="flex items-center justify-between px-3.5 py-3 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('admin.subscriptions') ? 'bg-gradient-to-r from-rose-600 to-rose-700 text-white shadow-lg shadow-rose-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <span class="flex items-center gap-3">
                        <span class="text-lg">💎</span>
                        <span>সাবস্ক্রিপশন কিউ</span>
                    </span>
                    @php
                        $pendingSubs = \App\Models\ShopSubscription::where('status', 'pending')->count();
                        $latestPendingSubId = \App\Models\ShopSubscription::where('status', 'pending')->max('id') ?? 0;
                    @endphp
                    <span id="adminPendingSubBadge" class="px-2 py-0.5 rounded-full text-[10px] font-black bg-rose-500 text-white animate-pulse {{ $pendingSubs > 0 ? '' : 'hidden' }}">
                        {{ $pendingSubs }}
                    </span>
                </a>

                <a href="{{ route('admin.jobs') }}" 
                   class="flex items-center gap-3 px-3.5 py-3 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('admin.jobs') ? 'bg-gradient-to-r from-rose-600 to-rose-700 text-white shadow-lg shadow-rose-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <span class="text-lg">📄</span>
                    <span>সকল প্রিন্ট টাস্ক (Jobs)</span>
                </a>

                <a href="{{ route('admin.settings') }}" 
                   class="flex items-center gap-3 px-3.5 py-3 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('admin.settings') ? 'bg-gradient-to-r from-rose-600 to-rose-700 text-white shadow-lg shadow-rose-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <span class="text-lg">🎬</span>
                    <span>ভিডিও ও সাইট সেটিংস</span>
                </a>

                <a href="{{ route('admin.logs') }}" 
                   class="flex items-center justify-between px-3.5 py-3 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('admin.logs*') ? 'bg-gradient-to-r from-rose-600 to-rose-700 text-white shadow-lg shadow-rose-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <span class="flex items-center gap-3">
                        <span class="text-lg">⚠️</span>
                        <span>এরর ও ক্র্যাশ লগ</span>
                    </span>
                    @php
                        $unresolvedLogsCount = \App\Models\SystemErrorLog::where('status', 'unresolved')->count();
                    @endphp
                    @if($unresolvedLogsCount > 0)
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-amber-500 text-slate-950 animate-pulse">
                            {{ $unresolvedLogsCount }}
                        </span>
                    @endif
                </a>

                <div class="pt-4 text-[10px] font-bold tracking-wider text-slate-500 uppercase px-3 py-1">ওয়েবসাইট লিংক</div>

                <a href="/" target="_blank" 
                   class="flex items-center justify-between px-3.5 py-3 rounded-xl text-sm font-semibold bg-slate-950 text-slate-300 border border-slate-800 hover:bg-slate-800 transition-all">
                    <span class="flex items-center gap-3">
                        <span class="text-lg">🌐</span>
                        <span>পাবলিক ওয়েবসাইট</span>
                    </span>
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                </a>
            </nav>

            <!-- Bottom User & Logout -->
            <div class="p-4 border-t border-slate-800 bg-slate-950/60">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2.5 overflow-hidden">
                        <div class="w-8 h-8 rounded-full bg-rose-600 text-white font-black flex items-center justify-center text-xs shrink-0">
                            SA
                        </div>
                        <div class="truncate">
                            <p class="text-xs font-bold text-white truncate">{{ auth()->user()->name ?? 'Super Admin' }}</p>
                            <p class="text-[10px] text-slate-400">MR Developer</p>
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

        <!-- MAIN CONTENT AREA -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Mobile Admin Header -->
            <header class="lg:hidden bg-slate-900 border-b border-slate-800 px-4 py-3 flex items-center justify-between sticky top-0 z-50">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                    <img src="{{ asset('logo.svg') }}" alt="Logo" class="w-8 h-8 rounded-xl shadow">
                    <span class="font-black text-white font-en text-base">Print<span class="text-rose-500">Sheba</span></span>
                    <span class="text-[8px] uppercase tracking-wider px-1.5 py-0.5 rounded bg-rose-500/20 text-rose-400 font-bold ml-1">ADMIN</span>
                </a>
                <button id="adminMobileMenuBtn" class="p-2 rounded-xl text-slate-300 hover:bg-slate-800 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                </button>
            </header>

            <!-- Mobile Admin Drawer -->
            <div id="adminMobileDrawer" class="fixed inset-0 bg-black/75 backdrop-blur-sm z-50 hidden lg:hidden">
                <div class="w-72 bg-slate-900 h-full p-5 flex flex-col justify-between overflow-y-auto">
                    <div>
                        <div class="flex items-center justify-between pb-4 border-b border-slate-800">
                            <div class="flex items-center gap-2.5">
                                <img src="{{ asset('logo.svg') }}" alt="Logo" class="w-8 h-8 rounded-xl">
                                <span class="font-black text-white font-en text-lg">Admin<span class="text-rose-500">Panel</span></span>
                            </div>
                            <button id="closeAdminDrawer" class="text-slate-400 hover:text-white text-xl font-bold">&times;</button>
                        </div>
                        <nav class="mt-4 space-y-1.5">
                            <a href="{{ route('admin.dashboard') }}" class="block px-3.5 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('admin.dashboard') ? 'bg-rose-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">📊 সেন্ট্রাল ড্যাশবোর্ড</a>
                            <a href="{{ route('admin.shops') }}" class="block px-3.5 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('admin.shops') ? 'bg-rose-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">🏪 দোকানসমূহ (Shops)</a>
                            <a href="{{ route('admin.subscriptions') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('admin.subscriptions') ? 'bg-rose-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">
                                <span>💎 সাবস্ক্রিপশন কিউ</span>
                                <span id="adminMobilePendingSubBadge" class="px-2 py-0.5 rounded-full text-[10px] font-black bg-rose-500 text-white animate-pulse {{ $pendingSubs > 0 ? '' : 'hidden' }}">
                                    {{ $pendingSubs }}
                                </span>
                            </a>
                            <a href="{{ route('admin.jobs') }}" class="block px-3.5 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('admin.jobs') ? 'bg-rose-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">📄 সকল প্রিন্ট টাস্ক</a>
                            <a href="{{ route('admin.settings') }}" class="block px-3.5 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('admin.settings') ? 'bg-rose-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">🎬 ভিডিও ও সাইট সেটিংস</a>
                            <a href="{{ route('admin.logs') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('admin.logs*') ? 'bg-rose-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">
                                <span>⚠️ এরর ও ক্র্যাশ লগ</span>
                                @if(isset($unresolvedLogsCount) && $unresolvedLogsCount > 0)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-amber-500 text-slate-950">
                                        {{ $unresolvedLogsCount }}
                                    </span>
                                @endif
                            </a>
                        </nav>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" class="pt-4 border-t border-slate-800">
                        @csrf
                        <button type="submit" class="w-full py-2.5 rounded-xl text-xs font-bold bg-rose-500/20 text-rose-400 hover:bg-rose-600 hover:text-white transition-all text-center">
                            লগআউট
                        </button>
                    </form>
                </div>
            </div>

            <!-- Page Content Container -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8 max-w-7xl w-full mx-auto bg-slate-950">
                @if(session('success'))
                    <div class="mb-6 p-4 rounded-2xl bg-emerald-500/15 border border-emerald-500/30 text-emerald-300 text-xs sm:text-sm font-bold flex items-center justify-between">
                        <span>✓ {{ session('success') }}</span>
                        <span class="text-xs text-emerald-400 bg-emerald-500/20 px-2 py-0.5 rounded-md">সফল</span>
                    </div>
                @endif

                @yield('content')
            </main>

            <!-- Admin Footer -->
            <footer class="border-t border-slate-800 px-6 py-4 text-center text-xs text-slate-500">
                PrintSheba Super Admin Panel • Developed by <a href="https://mrdeveloper.xyz" target="_blank" class="text-rose-400 font-bold hover:underline">MR Developer</a>
            </footer>
        </div>
    </div>

    <!-- Live Admin Subscription Toast -->
    <div id="adminSubToast" class="fixed top-5 right-5 z-[100] max-w-sm w-full transform translate-y-[-140px] opacity-0 transition-all duration-300 pointer-events-none">
        <div class="bg-slate-900 border-2 border-rose-500/70 text-white p-4 rounded-2xl shadow-2xl flex items-start gap-3 backdrop-blur-xl pointer-events-auto">
            <div class="w-10 h-10 rounded-xl bg-rose-500/20 text-rose-400 flex items-center justify-center text-xl shrink-0 animate-bounce">
                💎
            </div>
            <div class="flex-1 min-w-0">
                <p id="adminSubToastTitle" class="text-xs font-black text-rose-300">নতুন সাবস্ক্রিপশন রিকোয়েস্ট!</p>
                <p id="adminSubToastBody" class="text-xs text-slate-200 mt-0.5 truncate"></p>
                <div class="mt-2 flex items-center gap-2">
                    <a href="{{ route('admin.subscriptions') }}" class="px-3 py-1 rounded-lg bg-rose-600 hover:bg-rose-500 text-white text-[11px] font-bold transition">
                        এখনই দেখুন →
                    </a>
                    <button type="button" onclick="hideAdminSubToast()" class="px-2 py-1 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-400 text-[11px] font-bold">
                        বন্ধ করুন
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const adminBtn = document.getElementById('adminMobileMenuBtn');
        const adminDrawer = document.getElementById('adminMobileDrawer');
        const adminClose = document.getElementById('closeAdminDrawer');
        if (adminBtn && adminDrawer && adminClose) {
            adminBtn.addEventListener('click', () => adminDrawer.classList.remove('hidden'));
            adminClose.addEventListener('click', () => adminDrawer.classList.add('hidden'));
            adminDrawer.addEventListener('click', (e) => {
                if (e.target === adminDrawer) adminDrawer.classList.add('hidden');
            });
        }

        // Global AudioContext & Real-Time Admin Subscription Poller
        let adminAudioCtx = null;
        function unlockAdminAudio() {
            try {
                const AudioCtx = window.AudioContext || window.webkitAudioContext;
                if (AudioCtx && !adminAudioCtx) {
                    adminAudioCtx = new AudioCtx();
                }
                if (adminAudioCtx && adminAudioCtx.state === 'suspended') {
                    adminAudioCtx.resume();
                }
            } catch (e) {}
        }
        document.addEventListener('click', unlockAdminAudio, { once: true });
        document.addEventListener('keydown', unlockAdminAudio, { once: true });

        function playAdminSubscriptionSound() {
            try {
                const AudioCtx = window.AudioContext || window.webkitAudioContext;
                if (!AudioCtx) return;
                const ctx = adminAudioCtx || new AudioCtx();
                if (ctx.state === 'suspended') ctx.resume();
                const now = ctx.currentTime;

                const notes = [
                    { freq: 659.25, start: 0, dur: 0.22 },    // E5
                    { freq: 880.00, start: 0.12, dur: 0.25 }, // A5
                    { freq: 1046.50, start: 0.24, dur: 0.40 } // C6
                ];
                notes.forEach(n => {
                    const osc = ctx.createOscillator();
                    const gain = ctx.createGain();
                    osc.type = 'sine';
                    osc.frequency.setValueAtTime(n.freq, now + n.start);
                    gain.gain.setValueAtTime(0.16, now + n.start);
                    gain.gain.exponentialRampToValueAtTime(0.001, now + n.start + n.dur);
                    osc.connect(gain);
                    gain.connect(ctx.destination);
                    osc.start(now + n.start);
                    osc.stop(now + n.start + n.dur);
                });
            } catch (e) {}
        }

        let adminToastTimeout = null;
        function showAdminSubToast(title, body) {
            const toast = document.getElementById('adminSubToast');
            const titleEl = document.getElementById('adminSubToastTitle');
            const bodyEl = document.getElementById('adminSubToastBody');
            if (!toast || !titleEl || !bodyEl) return;
            titleEl.innerText = title;
            bodyEl.innerText = body;
            toast.classList.remove('translate-y-[-140px]', 'opacity-0', 'pointer-events-none');
            toast.classList.add('translate-y-0', 'opacity-100');
            if (adminToastTimeout) clearTimeout(adminToastTimeout);
            adminToastTimeout = setTimeout(hideAdminSubToast, 8000);
        }

        function hideAdminSubToast() {
            const toast = document.getElementById('adminSubToast');
            if (!toast) return;
            toast.classList.add('translate-y-[-140px]', 'opacity-0', 'pointer-events-none');
            toast.classList.remove('translate-y-0', 'opacity-100');
        }

        let lastAdminPendingCount = {{ $pendingSubs }};
        let lastAdminLatestPendingId = {{ $latestPendingSubId }};

        window.pollAdminSubscriptions = function() {
            fetch('{{ route("admin.subscriptions.live") }}', {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {
                // Update sidebar & mobile badges
                const deskBadge = document.getElementById('adminPendingSubBadge');
                const mobBadge = document.getElementById('adminMobilePendingSubBadge');
                [deskBadge, mobBadge].forEach(b => {
                    if (!b) return;
                    b.innerText = data.pending_count;
                    if (data.pending_count > 0) b.classList.remove('hidden');
                    else b.classList.add('hidden');
                });

                // Detect new pending request
                const hasNew = (data.latest_pending_id > lastAdminLatestPendingId) ||
                               (data.pending_count > lastAdminPendingCount);

                if (hasNew && data.pending_count > 0) {
                    playAdminSubscriptionSound();
                    const info = data.latest_pending;
                    const typeIcon = info && info.is_office_mode ? '🏢' : '🏬';
                    const msg = info
                        ? `${typeIcon} ${info.shop_name} • ${info.plan_name} (৳${info.amount})`
                        : `নতুন ${data.pending_count}টি রিকোয়েস্ট অপেক্ষমান`;
                    showAdminSubToast('নতুন সাবস্ক্রিপশন রিকোয়েস্ট!', msg);
                }

                lastAdminPendingCount = data.pending_count;
                lastAdminLatestPendingId = data.latest_pending_id;

                window.dispatchEvent(new CustomEvent('admin:subscriptions-updated', { detail: data }));
            })
            .catch(() => {});
        };

        setInterval(window.pollAdminSubscriptions, 4000);
    </script>
</body>
</html>
