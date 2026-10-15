<!-- COMPREHENSIVE UNIFIED FOOTER: Developer Info & Full Details -->
<footer id="contact" class="border-t border-slate-800 bg-slate-950 text-slate-300 py-16 text-xs relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-10 lg:gap-8 pb-12 border-b border-slate-800/80">
            
            <!-- Col 1: Brand & Status (4 Cols) -->
            <div class="lg:col-span-4 space-y-4">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('logo.svg') }}" alt="PrintSheba Logo" class="w-10 h-10 rounded-2xl shadow-md">
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
                    <li><a href="/" class="hover:text-rose-400 transition-colors">হোমপেজ</a></li>
                    <li><a href="{{ route('features') }}" class="hover:text-rose-400 transition-colors">সকল ফিচারসমূহ</a></li>
                    <li><a href="{{ route('how_it_works') }}" class="hover:text-rose-400 transition-colors">কীভাবে কাজ করে</a></li>
                    <li><a href="{{ url('/#calculator') }}" class="hover:text-rose-400 transition-colors">আয়ের ক্যালকুলেটর</a></li>
                    <li><a href="{{ url('/#pricing') }}" class="hover:text-rose-400 transition-colors">প্যাকেজ ও রেট</a></li>
                    <li><a href="{{ route('faq') }}" class="hover:text-rose-400 transition-colors">প্রশ্নোত্তর (FAQ)</a></li>
                    <li><a href="{{ route('login') }}" class="hover:text-rose-400 transition-colors font-bold text-slate-300">দোকানদার লগইন</a></li>
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
