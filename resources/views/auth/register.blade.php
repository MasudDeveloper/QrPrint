<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>রেজিস্ট্রেশন – PrintSheba (প্রিন্ট সেবা)</title>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Hind Siliguri', sans-serif;
            background-color: #fafafa;
            background-image: radial-gradient(#d1d5db 1.2px, transparent 1.2px);
            background-size: 24px 24px;
        }
        .font-en { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
    <link rel="icon" type="image/svg+xml" href="{{ asset('logo.svg') }}">
</head>
<body class="min-h-screen flex flex-col justify-center items-center p-4 py-8">
    <div class="w-full max-w-lg">
        <!-- Brand Header -->
        <div class="text-center mb-6">
            <a href="/" class="inline-flex items-center gap-3 group">
                <img src="{{ asset('logo.svg') }}" alt="PrintSheba Logo" class="w-12 h-12 rounded-2xl shadow-lg group-hover:scale-105 transition-transform duration-300">
                <div class="text-left">
                    <div class="text-2xl font-black text-slate-900 font-en tracking-tight">Print<span class="text-rose-500">Sheba</span></div>
                    <span id="brandSubTag" class="text-[10px] tracking-widest text-slate-400 font-bold block uppercase -mt-1">
                        {{ ($selectedType ?? 'shop') === 'office' ? 'অফিস সেন্ট্রাল প্রিন্ট নেটওয়ার্ক' : 'সাইবার ক্যাফে অটোমেশন' }}
                    </span>
                </div>
            </a>
            <h1 id="pageHeading" class="text-2xl font-black text-slate-900 mt-5">
                {{ ($selectedType ?? 'shop') === 'office' ? '🏢 নতুন অফিস / কর্পোরেট রেজিস্ট্রেশন' : '🏬 নতুন দোকান রেজিস্ট্রেশন' }}
            </h1>
            <p id="pageSubHeading" class="text-xs text-slate-500 mt-1">
                {{ ($selectedType ?? 'shop') === 'office' ? 'কর্মীদের জন্য ঝামেলাহীন জিরো-পেমেন্ট সেন্ট্রাল প্রিন্টিং সিস্টেম চালু করুন' : 'মাত্র ১ মিনিটে অ্যাকাউন্ট খুলে আপনার দোকানের কিউআর কোড তৈরি করুন' }}
            </p>
        </div>

        <!-- Registration Type Segmented Tab Switcher -->
        <div class="bg-slate-200/80 p-1.5 rounded-2xl flex items-center gap-1.5 mb-5 shadow-inner">
            <button type="button" onclick="switchAccountType('shop')" id="tab_shop" 
                class="flex-1 py-3 px-3 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-2 cursor-pointer shadow-sm bg-white text-slate-900">
                <span class="text-base">🏬</span>
                <span>প্রিন্ট / ফটোকপির দোকান</span>
            </button>
            <button type="button" onclick="switchAccountType('office')" id="tab_office" 
                class="flex-1 py-3 px-3 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-2 cursor-pointer text-slate-600 hover:text-slate-900">
                <span class="text-base">🏢</span>
                <span>অফিস / কর্পোরেট</span>
            </button>
        </div>

        <!-- Registration Card -->
        <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-xl shadow-slate-200/50 relative overflow-hidden">
            
            <!-- Dynamic Feature Badge Indicator -->
            <div id="featureBadgeContainer" class="mb-5 p-3 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold flex items-center gap-2">
                <span id="featureBadgeIcon" class="text-base">⚡</span>
                <span id="featureBadgeText">কাস্টমার সেলফ-সার্ভিস কিউআর ও বিকাশ/নগদে ১০০% অটো-কালেকশন</span>
            </div>

            @if($errors->any())
                <div class="mb-5 p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-semibold">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST" class="space-y-4" id="registrationForm">
                @csrf
                <input type="hidden" name="account_type" id="accountTypeInput" value="{{ old('account_type', $selectedType ?? 'shop') }}">

                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label for="userNameInput" class="block text-xs font-bold text-slate-700 mb-1.5" id="nameLabel">আপনার নাম</label>
                        <input type="text" id="userNameInput" name="name" value="{{ old('name') }}" required autocomplete="name"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20"
                            placeholder="মো: আরিফুল ইসলাম">
                    </div>

                    <div>
                        <label for="establishmentInput" class="block text-xs font-bold text-slate-700 mb-1.5" id="establishmentLabel">দোকানের নাম</label>
                        <input type="text" name="shop_name" id="establishmentInput" value="{{ old('shop_name', old('office_name')) }}" required 
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20"
                            placeholder="যেমন: রহিম কম্পিউটার্স & ফটোকপি">
                    </div>
                </div>

                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label for="userPhoneInput" class="block text-xs font-bold text-slate-700 mb-1.5" id="phoneLabel">মোবাইল / বিকাশ নম্বর</label>
                        <input type="tel" id="userPhoneInput" name="phone" value="{{ old('phone') }}" required autocomplete="tel"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20"
                            placeholder="017XXXXXXXX">
                    </div>

                    <div>
                        <label for="userEmailInput" class="block text-xs font-bold text-slate-700 mb-1.5">ইমেইল ঠিকানা</label>
                        <input type="email" id="userEmailInput" name="email" value="{{ old('email') }}" required autocomplete="email"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20"
                            placeholder="admin@example.com">
                    </div>
                </div>

                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label for="userPasswordInput" class="block text-xs font-bold text-slate-700 mb-1.5">পাসওয়ার্ড</label>
                        <input type="password" id="userPasswordInput" name="password" required autocomplete="new-password"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20"
                            placeholder="কমপক্ষে ৬ অক্ষর">
                    </div>

                    <div>
                        <label for="userPasswordConfirmInput" class="block text-xs font-bold text-slate-700 mb-1.5">পাসওয়ার্ড নিশ্চিত করুন</label>
                        <input type="password" id="userPasswordConfirmInput" name="password_confirmation" required autocomplete="new-password"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20"
                            placeholder="পুনরায় পাসওয়ার্ড লিখুন">
                    </div>
                </div>

                <button type="submit" id="submitButton" class="w-full min-h-[48px] py-3.5 px-6 rounded-xl bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white font-bold text-sm shadow-lg shadow-rose-500/25 transition-all hover:scale-[1.01] mt-2 cursor-pointer flex items-center justify-center">
                    দোকান চালু করুন (ফ্রি ট্রায়াল) →
                </button>
            </form>

            <div class="mt-6 pt-6 border-t border-slate-100 text-center text-xs text-slate-500">
                ইতিমধ্যে একাউন্ট আছে? 
                <a href="{{ route('login') }}" class="font-bold text-rose-600 hover:underline">লগইন করুন</a>
            </div>
        </div>
    </div>

    <script>
        function switchAccountType(type) {
            const tabShop = document.getElementById('tab_shop');
            const tabOffice = document.getElementById('tab_office');
            const accountTypeInput = document.getElementById('accountTypeInput');
            const brandSubTag = document.getElementById('brandSubTag');
            const pageHeading = document.getElementById('pageHeading');
            const pageSubHeading = document.getElementById('pageSubHeading');
            const establishmentLabel = document.getElementById('establishmentLabel');
            const establishmentInput = document.getElementById('establishmentInput');
            const phoneLabel = document.getElementById('phoneLabel');
            const featureBadgeContainer = document.getElementById('featureBadgeContainer');
            const featureBadgeIcon = document.getElementById('featureBadgeIcon');
            const featureBadgeText = document.getElementById('featureBadgeText');
            const submitButton = document.getElementById('submitButton');

            accountTypeInput.value = type;

            if (type === 'office') {
                // Tab styling
                tabOffice.className = "flex-1 py-3 px-3 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-2 cursor-pointer shadow-sm bg-white text-indigo-900 border border-indigo-200";
                tabShop.className = "flex-1 py-3 px-3 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-2 cursor-pointer text-slate-600 hover:text-slate-900";

                // Content changes
                brandSubTag.textContent = "অফিস সেন্ট্রাল প্রিন্ট নেটওয়ার্ক";
                pageHeading.textContent = "🏢 নতুন অফিস / কর্পোরেট রেজিস্ট্রেশন";
                pageSubHeading.textContent = "কর্মীদের জন্য ঝামেলাহীন জিরো-পেমেন্ট সেন্ট্রাল প্রিন্টিং সিস্টেম চালু করুন";
                establishmentLabel.textContent = "প্রতিষ্ঠানের নাম / কোম্পানি";
                establishmentInput.name = "office_name";
                establishmentInput.placeholder = "যেমন: মেঘনা গ্রুপ / ক্রিয়েটিভ আইটি";
                phoneLabel.textContent = "অফিসিয়াল যোগাযোগ নম্বর";

                // Badge styling
                featureBadgeContainer.className = "mb-5 p-3 rounded-2xl bg-indigo-50 border border-indigo-200 text-indigo-900 text-xs font-semibold flex items-center gap-2";
                featureBadgeIcon.textContent = "🏢";
                featureBadgeText.textContent = "কর্মীদের জন্য জিরো-পেমেন্ট ডাইরেক্ট অটো-প্রিন্ট • পেপার অডিট ট্র্যাকিং";

                // Button styling
                submitButton.className = "w-full py-3.5 px-6 rounded-xl bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white font-bold text-sm shadow-lg shadow-indigo-600/25 transition-all hover:scale-[1.01] mt-2 cursor-pointer";
                submitButton.textContent = "অফিস প্রিন্ট সিস্টেম চালু করুন (ফ্রি ট্রায়াল) →";

                // Update URL parameter without reload
                if (window.history.replaceState) {
                    const newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?type=office';
                    window.history.replaceState({path:newUrl}, '', newUrl);
                }
            } else {
                // Tab styling
                tabShop.className = "flex-1 py-3 px-3 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-2 cursor-pointer shadow-sm bg-white text-slate-900 border border-slate-200";
                tabOffice.className = "flex-1 py-3 px-3 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-2 cursor-pointer text-slate-600 hover:text-slate-900";

                // Content changes
                brandSubTag.textContent = "সাইবার ক্যাফে অটোমেশন";
                pageHeading.textContent = "🏬 নতুন দোকান রেজিস্ট্রেশন";
                pageSubHeading.textContent = "মাত্র ১ মিনিটে অ্যাকাউন্ট খুলে আপনার দোকানের কিউআর কোড তৈরি করুন";
                establishmentLabel.textContent = "দোকানের নাম";
                establishmentInput.name = "shop_name";
                establishmentInput.placeholder = "যেমন: রহিম কম্পিউটার্স & ফটোকপি";
                phoneLabel.textContent = "মোবাইল / বিকাশ নম্বর (যেখানে কাস্টমার টাকা দেবে)";

                // Badge styling
                featureBadgeContainer.className = "mb-5 p-3 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold flex items-center gap-2";
                featureBadgeIcon.textContent = "⚡";
                featureBadgeText.textContent = "কাস্টমার সেলফ-সার্ভিস কিউআর ও বিকাশ/নগদে ১০০% অটো-কালেকশন";

                // Button styling
                submitButton.className = "w-full py-3.5 px-6 rounded-xl bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white font-bold text-sm shadow-lg shadow-rose-500/25 transition-all hover:scale-[1.01] mt-2 cursor-pointer";
                submitButton.textContent = "দোকান চালু করুন (ফ্রি ট্রায়াল) →";

                // Update URL parameter without reload
                if (window.history.replaceState) {
                    const newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?type=shop';
                    window.history.replaceState({path:newUrl}, '', newUrl);
                }
            }
        }

        // Initialize based on server-rendered type
        document.addEventListener('DOMContentLoaded', function() {
            const initialType = "{{ old('account_type', $selectedType ?? 'shop') }}";
            if (initialType === 'office') {
                switchAccountType('office');
            }
        });
    </script>
</body>
</html>
