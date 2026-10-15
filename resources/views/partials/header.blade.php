<!-- Top Navbar Component -->
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
            <a href="/" class="{{ request()->is('/') ? 'text-rose-600 font-bold border-b-2 border-rose-500 pb-0.5' : 'hover:text-rose-600 transition-colors' }}">হোম</a>
            <a href="{{ route('features') }}" class="{{ request()->routeIs('features') ? 'text-rose-600 font-bold border-b-2 border-rose-500 pb-0.5' : 'hover:text-rose-600 transition-colors' }}">ফিচারসমূহ</a>
            <a href="{{ route('how_it_works') }}" class="{{ request()->routeIs('how_it_works') ? 'text-rose-600 font-bold border-b-2 border-rose-500 pb-0.5' : 'hover:text-rose-600 transition-colors' }}">কীভাবে কাজ করে</a>
            <a href="{{ route('faq') }}" class="{{ request()->routeIs('faq') ? 'text-rose-600 font-bold border-b-2 border-rose-500 pb-0.5' : 'hover:text-rose-600 transition-colors' }}">প্রশ্নোত্তর</a>
            <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'text-rose-600 font-bold border-b-2 border-rose-500 pb-0.5' : 'hover:text-rose-600 transition-colors' }}">যোগাযোগ</a>
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
