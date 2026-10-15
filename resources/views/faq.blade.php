<!DOCTYPE html>
<html lang="bn" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>প্রশ্নোত্তর (FAQ) – PrintSheba (প্রিন্ট সেবা) | সাইবার ক্যাফে অটোমেশন প্রশ্নোত্তর</title>
    <meta name="description" content="PrintSheba সম্পর্কিত সাধারণ জিজ্ঞাসা ও উত্তর: বিকাশ/নগদ পেমেন্ট, প্রিন্টার কানেকশন, ভাইরাস প্রটেকশন ও প্যাকেজ রেট।">

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

        /* Question bounce animation */
        @keyframes questionBounce {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-6px) scale(1.05); }
        }
        .anim-question {
            animation: questionBounce 3s ease-in-out infinite;
        }

        /* Glowing pulse */
        @keyframes glowPulse {
            0%, 100% { opacity: 0.6; transform: scale(1); }
            50% { opacity: 1; transform: scale(1.08); }
        }
        .anim-glow {
            animation: glowPulse 2.5s ease-in-out infinite;
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
                <span class="anim-question">❓</span> সাধারণ জিজ্ঞাসা ও সমাধান
            </div>
            
            <h1 class="text-3xl sm:text-5xl font-black text-slate-900 tracking-tight leading-tight">
                আপনার মনে কি কোনো <span class="bg-gradient-to-r from-rose-600 to-pink-500 bg-clip-text text-transparent">প্রশ্ন আছে?</span>
            </h1>

            <p class="text-base sm:text-lg text-slate-600 max-w-2xl mx-auto leading-relaxed font-medium">
                সাইবার ক্যাফে ও কম্পিউটার দোকান মালিকদের করা সর্বাধিক সাধারণ প্রশ্নগুলোর বিস্তারিত ও স্পষ্ট উত্তর এখানে দেওয়া হলো।
            </p>

            <!-- Search Filter Bar -->
            <div class="max-w-xl mx-auto pt-2">
                <div class="relative">
                    <input type="text" id="faqSearch" onkeyup="filterFaqs()" placeholder="যেকোনো প্রশ্ন লিখে খুঁজুন (উদাঃ বিকাশ, প্রিন্টার, খরচ)..." class="w-full px-5 py-3.5 pl-12 rounded-2xl bg-white border-2 border-slate-200 shadow-md text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-rose-500 transition-colors">
                    <span class="absolute left-4 top-4 text-slate-400 text-base">🔍</span>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ ACCORDION CONTAINER -->
    <section class="py-16 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">

            <!-- Category 1: সাধারণ প্রশ্ন -->
            <div class="faq-group space-y-4">
                <div class="flex items-center gap-2 pb-2 border-b-2 border-slate-100">
                    <span class="text-xl">🌟</span>
                    <h2 class="text-xl font-black text-slate-900">সাধারণ প্রশ্নাবলি</h2>
                </div>

                <!-- Q1 -->
                <div class="faq-item rounded-2xl border border-slate-200 bg-slate-50/50 hover:border-slate-300 transition-all overflow-hidden">
                    <button type="button" onclick="toggleFaq(this)" class="w-full p-5 text-left flex items-center justify-between gap-4 font-bold text-slate-900 hover:text-rose-600">
                        <span class="text-base">১. কাস্টমারকে কি কোনো আলাদা অ্যাপ ডাউনলোড করতে হবে?</span>
                        <span class="faq-arrow text-slate-400 text-lg transition-transform">▼</span>
                    </button>
                    <div class="faq-answer hidden px-5 pb-5 text-slate-600 text-sm leading-relaxed border-t border-slate-100/80 pt-3">
                        <strong>একদমই না!</strong> কাস্টমারের ফোনে কোনো অ্যাপ বা সফটওয়্যার ইনস্টল করতে হবে না। কাস্টমার শুধু তার মোবাইলের সাধারণ ক্যামেরা বা বিকাশ/যেকোনো QR স্ক্যানার দিয়ে স্ক্যান করলেই সরাসরি মোবাইল ব্রাউজারে সুন্দর একটি পোর্টাল খুলে যাবে।
                    </div>
                </div>

                <!-- Q2 -->
                <div class="faq-item rounded-2xl border border-slate-200 bg-slate-50/50 hover:border-slate-300 transition-all overflow-hidden">
                    <button type="button" onclick="toggleFaq(this)" class="w-full p-5 text-left flex items-center justify-between gap-4 font-bold text-slate-900 hover:text-rose-600">
                        <span class="text-base">২. দোকানে ইন্টারনেট সংযোগ স্লো থাকলে কি কাজ করবে?</span>
                        <span class="faq-arrow text-slate-400 text-lg transition-transform">▼</span>
                    </button>
                    <div class="faq-answer hidden px-5 pb-5 text-slate-600 text-sm leading-relaxed border-t border-slate-100/80 pt-3">
                        হ্যাঁ! আমাদের উইন্ডোজ ব্যাকগ্রাউন্ড কানেক্টরটি অত্যন্ত হালকা ও অপ্টিমাইজড (লো-ব্যান্ডউইথ)। সাধারণ মোবাইল হটস্পট বা টু-জি/থ্রি-জি ইন্টারনেটেও এটি মুহূর্তের মধ্যে সিগন্যাল আদান-প্রদান করতে পারে। ফাইল ডাউনলোড ও প্রিন্ট খুব দ্রুত সম্পন্ন হয়।
                    </div>
                </div>

                <!-- Q3 -->
                <div class="faq-item rounded-2xl border border-slate-200 bg-slate-50/50 hover:border-slate-300 transition-all overflow-hidden">
                    <button type="button" onclick="toggleFaq(this)" class="w-full p-5 text-left flex items-center justify-between gap-4 font-bold text-slate-900 hover:text-rose-600">
                        <span class="text-base">৩. একসাথে ৫-১০ জন কাস্টমার স্ক্যান করলে কী হবে?</span>
                        <span class="faq-arrow text-slate-400 text-lg transition-transform">▼</span>
                    </button>
                    <div class="faq-answer hidden px-5 pb-5 text-slate-600 text-sm leading-relaxed border-t border-slate-100/80 pt-3">
                        সিস্টেম স্বয়ংক্রিয়ভাবে সিরিয়াল (Print Queue) মেইনটেইন করবে। যে কাস্টমার আগে পেমেন্ট সম্পন্ন করবে তার প্রিন্ট আগে বের হবে, এরপর ক্রমান্বয়ে বাকিদেরটা প্রিন্ট হবে। কোনো ওভারল্যাপ বা ভুল ফাইল প্রিন্ট হওয়ার বিন্দুমাত্র সম্ভাবনা নেই।
                    </div>
                </div>
            </div>

            <!-- Category 2: পেমেন্ট ও বিকাশ/নগদ -->
            <div class="faq-group space-y-4">
                <div class="flex items-center gap-2 pb-2 border-b-2 border-slate-100">
                    <span class="text-xl">💳</span>
                    <h2 class="text-xl font-black text-slate-900">পেমেন্ট ও লেনদেন সংক্রান্ত</h2>
                </div>

                <!-- Q4 -->
                <div class="faq-item rounded-2xl border border-slate-200 bg-slate-50/50 hover:border-slate-300 transition-all overflow-hidden">
                    <button type="button" onclick="toggleFaq(this)" class="w-full p-5 text-left flex items-center justify-between gap-4 font-bold text-slate-900 hover:text-rose-600">
                        <span class="text-base">৪. কাস্টমারের দেওয়া টাকা কার কাছে যাবে?</span>
                        <span class="faq-arrow text-slate-400 text-lg transition-transform">▼</span>
                    </button>
                    <div class="faq-answer hidden px-5 pb-5 text-slate-600 text-sm leading-relaxed border-t border-slate-100/80 pt-3">
                        টাকা সরাসরি <strong>আপনার নিজস্ব বিকাশ/নগদ বা ব্যাংক একাউন্টে</strong> জমা হবে। PrintSheba কোনো ট্রানজেকশন ফান্ড হোল্ড করে না। কাস্টমার বিল পে করার সাথে সাথেই আপনার একাউন্টে টাকা ঢুকে যাবে।
                    </div>
                </div>

                <!-- Q5 -->
                <div class="faq-item rounded-2xl border border-slate-200 bg-slate-50/50 hover:border-slate-300 transition-all overflow-hidden">
                    <button type="button" onclick="toggleFaq(this)" class="w-full p-5 text-left flex items-center justify-between gap-4 font-bold text-slate-900 hover:text-rose-600">
                        <span class="text-base">৫. বাকি পড়ার কি কোনো সুযোগ আছে?</span>
                        <span class="faq-arrow text-slate-400 text-lg transition-transform">▼</span>
                    </button>
                    <div class="faq-answer hidden px-5 pb-5 text-slate-600 text-sm leading-relaxed border-t border-slate-100/80 pt-3">
                        <strong>০% বাকি!</strong> সিস্টেমের নিয়ম হলো "পেমেন্ট আগে, প্রিন্ট পরে"। কাস্টমার টাকা কনফার্ম না করা পর্যন্ত প্রিন্টার ড্রাইভার পেপার রিলিজ করবে না। ফলে পরিচিত-অপরিচিত কারও কাছেই এক টাকারও বকেয়া থাকার সুযোগ নেই।
                    </div>
                </div>
            </div>

            <!-- Category 3: প্রিন্টার ও টেকনিক্যাল -->
            <div class="faq-group space-y-4">
                <div class="flex items-center gap-2 pb-2 border-b-2 border-slate-100">
                    <span class="text-xl">🖨️</span>
                    <h2 class="text-xl font-black text-slate-900">প্রিন্টার ও টেকনিক্যাল সাপোর্ট</h2>
                </div>

                <!-- Q6 -->
                <div class="faq-item rounded-2xl border border-slate-200 bg-slate-50/50 hover:border-slate-300 transition-all overflow-hidden">
                    <button type="button" onclick="toggleFaq(this)" class="w-full p-5 text-left flex items-center justify-between gap-4 font-bold text-slate-900 hover:text-rose-600">
                        <span class="text-base">৬. আমার দোকানে Canon LBP 2900 বা Epson EcoTank আছে, এটি কি সাপোর্ট করবে?</span>
                        <span class="faq-arrow text-slate-400 text-lg transition-transform">▼</span>
                    </button>
                    <div class="faq-answer hidden px-5 pb-5 text-slate-600 text-sm leading-relaxed border-t border-slate-100/80 pt-3">
                        <strong>হ্যাঁ, শতভাগ সাপোর্ট করবে!</strong> বাংলাদেশে বহুল ব্যবহৃত Canon, Epson (L3110/L3210/L805), HP LaserJet, Brother সহ যেকোনো ব্র্যান্ডের প্রিন্টার সরাসরি উইন্ডোজ স্পুলারের মাধ্যমে কানেক্ট হয়।
                    </div>
                </div>

                <!-- Q7 -->
                <div class="faq-item rounded-2xl border border-slate-200 bg-slate-50/50 hover:border-slate-300 transition-all overflow-hidden">
                    <button type="button" onclick="toggleFaq(this)" class="w-full p-5 text-left flex items-center justify-between gap-4 font-bold text-slate-900 hover:text-rose-600">
                        <span class="text-base">৭. কাস্টমার কি কালার ও সাদাকালো প্রিন্ট আলাদাভাবে বেছে নিতে পারবে?</span>
                        <span class="faq-arrow text-slate-400 text-lg transition-transform">▼</span>
                    </button>
                    <div class="faq-answer hidden px-5 pb-5 text-slate-600 text-sm leading-relaxed border-t border-slate-100/80 pt-3">
                        হ্যাঁ। কাস্টমার ফাইল আপলোড করার পর অপশন পাবে সে সাদাকালো করবে নাকি কালার করবে, কত কপি প্রিন্ট নিবে এবং পেজ সাইজ (A4 বা Legal)। আপনি ড্যাশবোর্ডে যে রেট সেট করে রাখবেন সেই অনুযায়ী বিল ক্যালকুলেট হবে।
                    </div>
                </div>
            </div>

            <!-- Category 4: নিরাপত্তা ও গোপনীয়তা -->
            <div class="faq-group space-y-4">
                <div class="flex items-center gap-2 pb-2 border-b-2 border-slate-100">
                    <span class="text-xl">🔒</span>
                    <h2 class="text-xl font-black text-slate-900">নিরাপত্তা ও ভাইরাস প্রটেকশন</h2>
                </div>

                <!-- Q8 -->
                <div class="faq-item rounded-2xl border border-slate-200 bg-slate-50/50 hover:border-slate-300 transition-all overflow-hidden">
                    <button type="button" onclick="toggleFaq(this)" class="w-full p-5 text-left flex items-center justify-between gap-4 font-bold text-slate-900 hover:text-rose-600">
                        <span class="text-base">৮. কাস্টমারের পার্সোনাল ডকুমেন্টের গোপনীয়তা কেমন থাকবে?</span>
                        <span class="faq-arrow text-slate-400 text-lg transition-transform">▼</span>
                    </button>
                    <div class="faq-answer hidden px-5 pb-5 text-slate-600 text-sm leading-relaxed border-t border-slate-100/80 pt-3">
                        আমাদের সিস্টেমে ফাইলগুলো সম্পূর্ণ এনক্রিপ্টেড থাকে। প্রিন্ট শেষ হওয়ার সাথে সাথেই সার্ভার থেকে কাস্টমারের ফাইল নিরাপদভাবে পার্মানেন্ট ডিলিট হয়ে যায়। কোনো তৃতীয় ব্যক্তি বা অন্য কাস্টমার ফাইল দেখার সুযোগ নেই।
                    </div>
                </div>

                <!-- Q9 -->
                <div class="faq-item rounded-2xl border border-slate-200 bg-slate-50/50 hover:border-slate-300 transition-all overflow-hidden">
                    <button type="button" onclick="toggleFaq(this)" class="w-full p-5 text-left flex items-center justify-between gap-4 font-bold text-slate-900 hover:text-rose-600">
                        <span class="text-base">৯. দোকানদারের কম্পিউটারে কি কোনো ভাইরাস ঢুকতে পারে?</span>
                        <span class="faq-arrow text-slate-400 text-lg transition-transform">▼</span>
                    </button>
                    <div class="faq-answer hidden px-5 pb-5 text-slate-600 text-sm leading-relaxed border-t border-slate-100/80 pt-3">
                        <strong>একদমই না!</strong> যেহেতু কাস্টমার কোনো পেনড্রাইভ বা মেমোরি কার্ড পিসিতে প্রবেশ করাচ্ছে না, তাই কম্পিউটারে শর্টকাট ভাইরাস বা ম্যালওয়্যার ঢোকার কোনো প্রশ্নই আসে না। আপনার পিসি থাকবে ১০০% সুরক্ষিত ও ফাস্ট।
                    </div>
                </div>
            </div>

            <!-- Category 5: প্যাকেজ ও খরচ -->
            <div class="faq-group space-y-4">
                <div class="flex items-center gap-2 pb-2 border-b-2 border-slate-100">
                    <span class="text-xl">💰</span>
                    <h2 class="text-xl font-black text-slate-900">খরচ ও প্যাকেজ</h2>
                </div>

                <!-- Q10 -->
                <div class="faq-item rounded-2xl border border-slate-200 bg-slate-50/50 hover:border-slate-300 transition-all overflow-hidden">
                    <button type="button" onclick="toggleFaq(this)" class="w-full p-5 text-left flex items-center justify-between gap-4 font-bold text-slate-900 hover:text-rose-600">
                        <span class="text-base">১০. রেজিস্ট্রেশন ফি কত এবং কীভাবে শুরু করব?</span>
                        <span class="faq-arrow text-slate-400 text-lg transition-transform">▼</span>
                    </button>
                    <div class="faq-answer hidden px-5 pb-5 text-slate-600 text-sm leading-relaxed border-t border-slate-100/80 pt-3">
                        রেজিস্ট্রেশন সম্পূর্ণ <strong>ফ্রি!</strong> নতুন শপ ওনারদের জন্য প্রথম ৫০টি প্রিন্ট সম্পূর্ণ ফ্রি ট্রায়াল দেওয়া হয়। এরপর আপনার পছন্দমতো সাশ্রয়ী মাসিক বা পে-পার-প্রিন্ট প্যাকেজ বেছে নিতে পারেন। কোনো হিডেন চার্জ নেই।
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- STILL HAVE QUESTIONS? CALL HOTLINE BANNER -->
    <section class="py-14 bg-slate-50 border-t border-slate-200">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-6">
            <div class="w-16 h-16 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center text-2xl mx-auto shadow-sm">
                💬
            </div>
            <h3 class="text-2xl font-black text-slate-900">আপনার প্রশ্নের উত্তর কি এখানে নেই?</h3>
            <p class="text-sm text-slate-600 max-w-xl mx-auto">
                আমাদের কাস্টমার কেয়ার টিম সপ্তাহের ৭ দিনই আপনার যেকোনো জিজ্ঞাসা বা টেকনিক্যাল সহযোগিতার জন্য প্রস্তুত।
            </p>
            <div class="flex flex-wrap items-center justify-center gap-4 pt-2">
                <a href="tel:01644416378" class="px-6 py-3 rounded-full text-xs font-bold text-white bg-slate-900 hover:bg-slate-800 shadow-md transition-all">
                    📞 কল করুন: 01644416378
                </a>
                <a href="https://wa.me/8801644416378" target="_blank" class="px-6 py-3 rounded-full text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-md transition-all">
                    🟢 হোয়াটসঅ্যাপ মেসেজ পাঠান
                </a>
                <a href="{{ route('contact') }}" class="px-6 py-3 rounded-full text-xs font-bold text-rose-600 bg-rose-50 hover:bg-rose-100 border border-rose-200 transition-all">
                    ✉️ যোগাযোগ পেইজে যান
                </a>
            </div>
        </div>
    </section>

    <!-- Global Footer -->
    @include('partials.footer')

    <!-- Interactive Accordion & Filter Script -->
    <script>
        function toggleFaq(btn) {
            const answer = btn.nextElementSibling;
            const arrow = btn.querySelector('.faq-arrow');
            const isHidden = answer.classList.contains('hidden');
            
            // Close all open answers in the same group or page
            document.querySelectorAll('.faq-answer').forEach(ans => ans.classList.add('hidden'));
            document.querySelectorAll('.faq-arrow').forEach(arr => arr.style.transform = 'rotate(0deg)');
            
            if (isHidden) {
                answer.classList.remove('hidden');
                arrow.style.transform = 'rotate(180deg)';
                arrow.classList.add('text-rose-500');
            }
        }

        function filterFaqs() {
            const query = document.getElementById('faqSearch').value.toLowerCase().trim();
            const items = document.querySelectorAll('.faq-item');
            
            items.forEach(item => {
                const text = item.innerText.toLowerCase();
                if (text.includes(query)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }
    </script>

</body>
</html>
