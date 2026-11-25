@extends('layouts.shop')

@section('title', ($shop->is_office_mode ? 'কর্পোরেট প্যাকেজ ও লাইসেন্স' : 'সাবস্ক্রিপশন ও প্যাকেজ প্ল্যান') . ' – ' . $shop->name)

@section('content')
<div class="space-y-8 max-w-6xl mx-auto pb-24 md:pb-12" id="subscriptionPage">
    
    <!-- Top Header Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-gradient-to-r {{ $shop->is_office_mode ? 'from-slate-900 via-indigo-950 to-slate-900 border-indigo-900/60' : 'from-slate-900 via-slate-950 to-slate-900 border-slate-800' }} p-6 sm:p-8 rounded-3xl text-white border shadow-2xl relative overflow-hidden">
        <div class="absolute -top-24 -left-24 w-72 h-72 {{ $shop->is_office_mode ? 'bg-indigo-500/10' : 'bg-rose-500/10' }} rounded-full blur-3xl pointer-events-none"></div>
        <div class="space-y-2 z-10">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full {{ $shop->is_office_mode ? 'bg-indigo-500/20 text-indigo-300 border border-indigo-500/30' : 'bg-rose-500/15 text-rose-400 border border-rose-500/30' }} text-xs font-bold">
                <span class="w-2 h-2 rounded-full {{ $shop->is_office_mode ? 'bg-indigo-400' : 'bg-rose-400' }} animate-pulse"></span>
                <span>{{ $shop->is_office_mode ? '🏢 অফিস ও কর্পোরেট লাইসেন্স পোর্টাল' : '🏪 দোকানদার প্যাকেজ ও লাইসেন্স পোর্টাল' }}</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black">
                {{ $shop->is_office_mode ? 'কর্পোরেট প্যাকেজ ও সেন্ট্রাল লাইসেন্স' : 'প্যাকেজ প্ল্যান ও লাইসেন্স রিনিউ' }}
            </h1>
            <p class="text-xs sm:text-sm text-slate-400">
                {{ $shop->is_office_mode 
                    ? 'আপনার অফিস প্রিন্ট সার্ভার লাইসেন্সের মেয়াদ চেক করুন এবং বিকাশ/নগদ/রকেটের মাধ্যমে সহজে রিনিউ বা কর্পোরেট প্ল্যানে আপগ্রেড করুন' 
                    : 'আপনার সফটওয়্যার লাইসেন্সের মেয়াদ চেক করুন এবং বিকাশ/নগদ/রকেটের মাধ্যমে মাত্র ৪টি সহজ ধাপে রিনিউ বা আপগ্রেড করুন' }}
            </p>
        </div>

        <!-- Current Active Plan Card on Header -->
        <div id="headerPlanCard" class="p-4 rounded-2xl bg-slate-900/90 border border-slate-800 text-left sm:text-right shrink-0 z-10 space-y-1">
            <span class="text-[10px] text-slate-400 uppercase tracking-wider block font-bold">বর্তমান প্যাকেজ:</span>
            <div id="headerPlanLabel" class="text-lg font-black {{ $shop->is_office_mode ? 'text-indigo-400' : 'text-rose-400' }}">{{ $shop->plan_label }}</div>
            
            @if($shop->is_lifetime)
                <div id="headerSubStatusWrapper">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-purple-500/20 text-purple-300 text-[10px] font-bold border border-purple-500/30">
                        👑 আজীবন ফ্রি লাইসেন্স
                    </span>
                </div>
                <div id="headerExpiresAt" class="text-[11px] text-slate-400 hidden"></div>
            @else
                @php
                    $isActive = $shop->isSubscriptionActive();
                @endphp
                <div id="headerSubStatusWrapper" class="flex items-center sm:justify-end gap-1.5 pt-1">
                    @if($isActive)
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                        <span class="text-xs font-bold text-emerald-400">{{ $shop->time_remaining_label }}</span>
                    @else
                        <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                        <span class="text-xs font-bold text-rose-400">মেয়াদ উত্তীর্ণ (Expired)</span>
                    @endif
                </div>
                <div id="headerExpiresAt" class="text-[11px] text-slate-400">
                    মেয়াদ শেষ: <span class="font-bold text-slate-300">{{ $shop->formatted_expires_at }}</span>
                </div>
            @endif
        </div>
    </div>

    <!-- Toast Notification Container -->
    <div id="copyToast" class="fixed top-5 right-5 z-50 transform translate-y-[-100px] opacity-0 transition-all duration-300 pointer-events-none">
        <div class="bg-emerald-600 text-white px-5 py-3 rounded-2xl shadow-2xl flex items-center gap-3 border border-emerald-400/40 text-sm font-bold">
            <span class="text-lg">✓</span>
            <span id="copyToastText">কপি হয়েছে!</span>
        </div>
    </div>

    <!-- Expired Warning Banner -->
    @if(!$shop->isSubscriptionActive() && !$shop->is_lifetime)
        <div id="subPageExpiredBanner" class="p-5 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-300 text-xs sm:text-sm font-bold flex items-start gap-3">
            <span class="text-2xl mt-0.5">⚠️</span>
            <div>
                <span class="font-black text-white text-base block mb-0.5">আপনার সাবস্ক্রিপশনের মেয়াদ শেষ হয়েছে!</span>
                {{ $shop->is_office_mode 
                    ? 'কর্মীদের নিরবচ্ছিন্ন জিরো-পেমেন্ট প্রিন্টিং বজায় রাখতে নিচের ৪টি সহজ ধাপে কর্পোরেট প্যাকেজ বেছে নিয়ে লাইসেন্স রিনিউ করুন।' 
                    : 'কাস্টমার কিউ ও স্বয়ংক্রিয় প্রিন্ট সার্ভিস সচল রাখতে নিচের ৪টি সহজ ধাপে প্যাকেজ বেছে নিয়ে বিকাশ, নগদ বা রকেটে পেমেন্ট করুন।' }}
            </div>
        </div>
    @endif

    <!-- LIVE PENDING / VERIFICATION STATUS CARD (If user has pending subscription or submitted) -->
    @if($pendingSubscription)
        <div id="pendingStatusBox" class="bg-gradient-to-br from-slate-900 via-slate-950 to-slate-900 rounded-3xl border-2 border-amber-500/40 p-6 sm:p-8 shadow-2xl relative overflow-hidden">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-800">
                <div class="space-y-1">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-500/20 text-amber-300 border border-amber-500/40 text-xs font-bold">
                        <span class="w-2 h-2 rounded-full bg-amber-400 animate-ping"></span>
                        <span>লাইভ যাচাই প্রক্রিয়া চলছে (Real-Time Verification)</span>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-black text-white">পেমেন্ট যাচাইয়ের অনুরোধ পর্যালোচনা করা হচ্ছে</h2>
                    <p class="text-xs sm:text-sm text-slate-400">এডমিন পেমেন্ট চেক করার সাথে সাথে এই পেজটি স্বয়ংক্রিয়ভাবে সক্রিয় হয়ে যাবে (পেজ রিফ্রেশ করতে হবে না)</p>
                </div>
                <button type="button" onclick="checkLiveStatus(true)" class="self-start sm:self-auto px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-bold flex items-center gap-2 transition cursor-pointer">
                    <span id="refreshIcon" class="inline-block transition-transform">🔄</span>
                    <span>স্ট্যাটাস রিফ্রেশ করুন</span>
                </button>
            </div>

            <!-- 3-Stage Animated Stepper for Status -->
            <div class="py-8">
                <div class="grid grid-cols-3 gap-2 sm:gap-4 relative">
                    <!-- Progress Line -->
                    <div class="absolute top-1/2 left-0 w-full h-1 bg-slate-800 -translate-y-1/2 z-0"></div>
                    <div id="liveProgressFill" class="absolute top-1/2 left-0 h-1 bg-gradient-to-r from-emerald-500 to-amber-500 -translate-y-1/2 z-0 transition-all duration-700" style="width: 50%;"></div>

                    <!-- Step 1: Submitted -->
                    <div class="relative z-10 flex flex-col items-center text-center">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-emerald-500 text-white flex items-center justify-center font-black text-sm sm:text-base shadow-lg shadow-emerald-500/30 ring-4 ring-slate-900">
                            ✓
                        </div>
                        <span class="text-xs sm:text-sm font-bold text-emerald-400 mt-2">জমা হয়েছে</span>
                        <span class="text-[10px] text-slate-400 hidden sm:block">আবেদন সম্পন্ন</span>
                    </div>

                    <!-- Step 2: Verifying -->
                    <div class="relative z-10 flex flex-col items-center text-center">
                        <div id="verifyingCircle" class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-amber-500 text-white flex items-center justify-center font-black text-sm sm:text-base shadow-lg shadow-amber-500/40 ring-4 ring-slate-900 animate-pulse">
                            ⏳
                        </div>
                        <span id="verifyingLabel" class="text-xs sm:text-sm font-bold text-amber-400 mt-2">যাচাই চলছে...</span>
                        <span class="text-[10px] text-slate-400 hidden sm:block">এডমিন ট্রানজেকশন মিলাচ্ছে</span>
                    </div>

                    <!-- Step 3: Active -->
                    <div class="relative z-10 flex flex-col items-center text-center">
                        <div id="activeCircle" class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-slate-800 text-slate-500 flex items-center justify-center font-black text-sm sm:text-base ring-4 ring-slate-900 transition-all">
                            👑
                        </div>
                        <span id="activeLabel" class="text-xs sm:text-sm font-bold text-slate-500 mt-2">প্যাকেজ সক্রিয়</span>
                        <span class="text-[10px] text-slate-500 hidden sm:block">ব্যবহারের জন্য প্রস্তুত</span>
                    </div>
                </div>
            </div>

            <!-- Pending Request Info Card -->
            <div class="p-4 sm:p-5 rounded-2xl bg-slate-950/80 border border-slate-800 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs w-full md:w-auto">
                    <div>
                        <span class="text-slate-400 block text-[11px]">প্যাকেজ:</span>
                        <span class="font-bold text-white text-sm">{{ $pendingSubscription->plan_name }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[11px]">টাকার পরিমাণ:</span>
                        <span class="font-bold text-emerald-400 text-sm font-en">৳ {{ number_format($pendingSubscription->amount, 0) }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[11px]">পেমেন্ট মাধ্যম:</span>
                        <span class="font-bold uppercase text-white text-sm">{{ $pendingSubscription->payment_method }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[11px]">TrxID:</span>
                        <span class="font-bold font-mono text-amber-300 text-sm">{{ $pendingSubscription->transaction_id }}</span>
                    </div>
                </div>

                <div class="flex items-center gap-2 w-full md:w-auto justify-end">
                    <button type="button" onclick="toggleStepperVisibility()" class="text-xs text-slate-400 hover:text-white underline cursor-pointer">
                        নতুন রিকোয়েস্ট পাঠাতে চান?
                    </button>
                </div>
            </div>

            <!-- Approved Success Banner (Initially Hidden, shown dynamically on approval) -->
            <div id="approvedSuccessContainer" class="hidden mt-6 p-6 rounded-2xl bg-emerald-500/15 border-2 border-emerald-500 text-white space-y-4">
                <div class="flex items-center gap-3">
                    <span class="text-3xl">🎉</span>
                    <div>
                        <h3 class="text-lg font-black text-emerald-300">অভিনন্দন! আপনার সাবস্ক্রিপশন সক্রিয় হয়েছে!</h3>
                        <p class="text-xs text-slate-300">পেমেন্ট সফলভাবে অনুমোদিত হয়েছে এবং আপনার অ্যাকাউন্টের মেয়াদ বাড়ানো হয়েছে।</p>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-3 pt-2">
                    <button type="button" onclick="openReceiptModal()" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs flex items-center gap-2 shadow-lg cursor-pointer">
                        <span>🧾 মানি রিসিট / ইনভয়েস প্রিন্ট করুন</span>
                    </button>
                    <a href="{{ route('shop.dashboard') }}" class="px-5 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs flex items-center gap-2 cursor-pointer">
                        <span>🚀 ড্যাশবোর্ডে প্রবেশ করুন</span>
                    </a>
                </div>
            </div>

            <!-- Rejected Banner (Initially Hidden) -->
            <div id="rejectedNoticeContainer" class="hidden mt-6 p-6 rounded-2xl bg-rose-500/15 border-2 border-rose-500 text-white space-y-3">
                <div class="flex items-center gap-3">
                    <span class="text-3xl">✕</span>
                    <div>
                        <h3 class="text-lg font-black text-rose-300">পেমেন্ট যাচাই বাতিল হয়েছে (Rejected)</h3>
                        <p id="rejectionReasonText" class="text-xs text-slate-300">প্রদত্ত TrxID বা নম্বরের সাথে পেমেন্ট মিল পাওয়া যায়নি।</p>
                    </div>
                </div>
                <button type="button" onclick="showStepperAgain()" class="px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs cursor-pointer">
                    🔄 তথ্য সংশোধন করে আবার চেষ্টা করুন
                </button>
            </div>
        </div>
    @endif

    <!-- MAIN 4-STEP CHECKOUT CONTAINER -->
    <div id="stepperCheckoutSection" class="{{ $pendingSubscription ? 'hidden' : 'block' }} space-y-8">
        
        <!-- STEPPER PROGRESS BAR (4 Steps) -->
        <div class="bg-slate-900/90 p-4 sm:p-6 rounded-3xl border border-slate-800 shadow-xl">
            <div class="grid grid-cols-4 gap-2 sm:gap-4 relative">
                <!-- Step 1 Tab -->
                <button type="button" onclick="goToStep(1)" class="stepper-tab group text-left cursor-pointer transition-all" id="stepTab1">
                    <div class="flex items-center gap-2 sm:gap-3">
                        <span class="step-badge w-8 h-8 sm:w-10 sm:h-10 rounded-2xl bg-rose-500 text-white flex items-center justify-center font-black text-xs sm:text-sm shadow-lg shadow-rose-500/30 transition-all">
                            ১
                        </span>
                        <div class="hidden md:block">
                            <span class="text-[10px] uppercase font-bold text-rose-400 block tracking-wider">ধাপ ১</span>
                            <span class="step-title text-xs sm:text-sm font-black text-white block">প্যাকেজ নির্বাচন</span>
                        </div>
                    </div>
                    <div class="h-1.5 w-full bg-rose-500 rounded-full mt-3 step-indicator"></div>
                </button>

                <!-- Step 2 Tab -->
                <button type="button" onclick="goToStep(2)" class="stepper-tab group text-left cursor-pointer transition-all" id="stepTab2">
                    <div class="flex items-center gap-2 sm:gap-3">
                        <span class="step-badge w-8 h-8 sm:w-10 sm:h-10 rounded-2xl bg-slate-800 text-slate-400 flex items-center justify-center font-black text-xs sm:text-sm transition-all">
                            ২
                        </span>
                        <div class="hidden md:block">
                            <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">ধাপ ২</span>
                            <span class="step-title text-xs sm:text-sm font-black text-slate-400 block">পেমেন্ট মাধ্যম</span>
                        </div>
                    </div>
                    <div class="h-1.5 w-full bg-slate-800 rounded-full mt-3 step-indicator"></div>
                </button>

                <!-- Step 3 Tab -->
                <button type="button" onclick="goToStep(3)" class="stepper-tab group text-left cursor-pointer transition-all" id="stepTab3">
                    <div class="flex items-center gap-2 sm:gap-3">
                        <span class="step-badge w-8 h-8 sm:w-10 sm:h-10 rounded-2xl bg-slate-800 text-slate-400 flex items-center justify-center font-black text-xs sm:text-sm transition-all">
                            ৩
                        </span>
                        <div class="hidden md:block">
                            <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">ধাপ ৩</span>
                            <span class="step-title text-xs sm:text-sm font-black text-slate-400 block">টাকা পাঠান</span>
                        </div>
                    </div>
                    <div class="h-1.5 w-full bg-slate-800 rounded-full mt-3 step-indicator"></div>
                </button>

                <!-- Step 4 Tab -->
                <button type="button" onclick="goToStep(4)" class="stepper-tab group text-left cursor-pointer transition-all" id="stepTab4">
                    <div class="flex items-center gap-2 sm:gap-3">
                        <span class="step-badge w-8 h-8 sm:w-10 sm:h-10 rounded-2xl bg-slate-800 text-slate-400 flex items-center justify-center font-black text-xs sm:text-sm transition-all">
                            ৪
                        </span>
                        <div class="hidden md:block">
                            <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">ধাপ ৪</span>
                            <span class="step-title text-xs sm:text-sm font-black text-slate-400 block">যাচাই ও কনফার্ম</span>
                        </div>
                    </div>
                    <div class="h-1.5 w-full bg-slate-800 rounded-full mt-3 step-indicator"></div>
                </button>
            </div>
        </div>

        <!-- MAIN CHECKOUT GRID: Left (Active Step) + Right (Order Summary Card) -->
        <div class="grid lg:grid-cols-12 gap-8 items-start">
            
            <!-- LEFT 8 COLS: The Focused Active Step Container -->
            <div class="lg:col-span-8 space-y-6">

                <!-- ================= STEP 1: PACKAGE SELECTION ================= -->
                <div id="stepContainer1" class="step-content space-y-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-xl sm:text-2xl font-black text-white">
                                {{ $shop->is_office_mode ? '১. অফিস কর্পোরেট প্যাকেজ নির্বাচন করুন' : '১. আপনার সুবিধাজনক প্যাকেজটি বেছে নিন' }}
                            </h2>
                            <p class="text-xs sm:text-sm text-slate-400 mt-1">প্যাকেজের উপর ক্লিক করে নিচের "পরবর্তী ধাপ" বাটনে চাপুন</p>
                        </div>
                        <span class="text-xs font-bold text-rose-400 bg-rose-500/10 px-3 py-1 rounded-full border border-rose-500/20">ধাপ ১ / ৪</span>
                    </div>

                    <div class="grid sm:grid-cols-3 gap-5">
                        @if($shop->is_office_mode)
                            <!-- OFFICE PLAN 1: Basic Monthly -->
                            <div onclick="selectPlan('office_basic_monthly', 'অফিস বেসিক (১ মাস)', 499, '১ মাস')" class="plan-card relative bg-slate-900/90 p-5 sm:p-6 rounded-3xl border-2 cursor-pointer transition-all hover:border-indigo-500 {{ $preselectedPlan === 'office_basic_monthly' ? 'border-indigo-500 shadow-xl shadow-indigo-500/10' : 'border-slate-800' }}" id="pcard_office_basic_monthly">
                                <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                                    <span class="text-sm font-bold text-white">অফিস বেসিক</span>
                                    <span class="plan-checkmark w-5 h-5 rounded-full border-2 border-slate-600 flex items-center justify-center text-[10px] text-white"></span>
                                </div>
                                <div class="my-4">
                                    <span class="text-2xl sm:text-3xl font-black text-white font-en">৳ ৪৯৯</span>
                                    <span class="text-xs text-slate-400 block mt-0.5">/ ১ মাস</span>
                                </div>
                                <ul class="space-y-2 text-xs text-slate-300">
                                    <li class="flex items-center gap-2"><span class="text-indigo-400 font-bold">✓</span> কর্মীদের জন্য জিরো-পেমেন্ট প্রিন্ট</li>
                                    <li class="flex items-center gap-2"><span class="text-indigo-400 font-bold">✓</span> ১টি প্রিন্টার কানেকশন</li>
                                    <li class="flex items-center gap-2"><span class="text-indigo-400 font-bold">✓</span> স্বয়ংক্রিয় ফাইল ডিলিট</li>
                                </ul>
                            </div>

                            <!-- OFFICE PLAN 2: Basic Yearly (Recommended) -->
                            <div onclick="selectPlan('office_basic_yearly', 'অফিস বেসিক (১ বছর)', 4500, '১ বছর')" class="plan-card relative bg-slate-900/90 p-5 sm:p-6 rounded-3xl border-2 cursor-pointer transition-all hover:border-indigo-500 {{ ($preselectedPlan === 'office_basic_yearly' || !$preselectedPlan) ? 'border-indigo-500 shadow-xl shadow-indigo-500/20' : 'border-slate-800' }}" id="pcard_office_basic_yearly">
                                <div class="absolute -top-3 left-1/2 -translate-x-1/2 px-3 py-0.5 rounded-full bg-gradient-to-r from-indigo-500 to-blue-500 text-white text-[9px] font-black uppercase tracking-wider shadow">
                                    ★ সেরা পছন্দ (২ মাস ফ্রি)
                                </div>
                                <div class="flex items-center justify-between pb-3 border-b border-slate-800 mt-1">
                                    <span class="text-sm font-bold text-white">অফিস বেসিক (১ বছর)</span>
                                    <span class="plan-checkmark w-5 h-5 rounded-full border-2 border-slate-600 flex items-center justify-center text-[10px] text-white"></span>
                                </div>
                                <div class="my-4">
                                    <span class="text-2xl sm:text-3xl font-black text-indigo-400 font-en">৳ ৪,৫০০</span>
                                    <span class="text-xs text-slate-400 block mt-0.5">/ ১ বছর (১২ মাস)</span>
                                </div>
                                <ul class="space-y-2 text-xs text-slate-300">
                                    <li class="flex items-center gap-2"><span class="text-indigo-400 font-bold">✓</span> আনলিমিটেড কর্মী প্রিন্ট</li>
                                    <li class="flex items-center gap-2"><span class="text-indigo-400 font-bold">✓</span> অফিস QR পোস্টার ও ড্যাশবোর্ড</li>
                                    <li class="flex items-center gap-2"><span class="text-indigo-400 font-bold">✓</span> ফোন ও AnyDesk রিমোট সাপোর্ট</li>
                                </ul>
                            </div>

                            <!-- OFFICE PLAN 3: Corporate Pro -->
                            <div onclick="selectPlan('office_corporate_yearly', 'কর্পোরেট প্রো (১ বছর)', 8999, '১ বছর')" class="plan-card relative bg-slate-900/90 p-5 sm:p-6 rounded-3xl border-2 cursor-pointer transition-all hover:border-cyan-500 {{ $preselectedPlan === 'office_corporate_yearly' ? 'border-cyan-500 shadow-xl shadow-cyan-500/20' : 'border-slate-800' }}" id="pcard_office_corporate_yearly">
                                <div class="absolute top-0 right-0 px-3 py-1 bg-gradient-to-l from-indigo-600 to-cyan-600 text-white text-[9px] font-black rounded-bl-xl uppercase">
                                    🏢 মাল্টি-প্রিন্টার
                                </div>
                                <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                                    <span class="text-sm font-bold text-cyan-300">কর্পোরেট প্রো</span>
                                    <span class="plan-checkmark w-5 h-5 rounded-full border-2 border-slate-600 flex items-center justify-center text-[10px] text-white"></span>
                                </div>
                                <div class="my-4">
                                    <span class="text-2xl sm:text-3xl font-black text-cyan-400 font-en">৳ ৮,৯৯৯</span>
                                    <span class="text-xs text-slate-400 block mt-0.5">/ ১ বছর</span>
                                </div>
                                <ul class="space-y-2 text-xs text-slate-300">
                                    <li class="flex items-center gap-2"><span class="text-cyan-400 font-bold">✓</span> আনলিমিটেড প্রিন্টার কানেকশন</li>
                                    <li class="flex items-center gap-2"><span class="text-cyan-400 font-bold">✓</span> একাধিক ডিপার্টমেন্ট ইউজার কিউ</li>
                                    <li class="flex items-center gap-2"><span class="text-cyan-400 font-bold">✓</span> ডেডিকেটেড একাউন্ট ম্যানেজার</li>
                                </ul>
                            </div>
                        @else
                            <!-- SHOP PLAN 1: Monthly Starter -->
                            <div onclick="selectPlan('monthly_starter', 'মান্থলি স্টার্টার', 99, '১ মাস')" class="plan-card relative bg-slate-900/90 p-5 sm:p-6 rounded-3xl border-2 cursor-pointer transition-all hover:border-slate-600 {{ $preselectedPlan === 'monthly_starter' ? 'border-rose-500 shadow-xl shadow-rose-500/20' : 'border-slate-800' }}" id="pcard_monthly_starter">
                                <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                                    <span class="text-sm font-bold text-white">মান্থলি স্টার্টার</span>
                                    <span class="plan-checkmark w-5 h-5 rounded-full border-2 border-slate-600 flex items-center justify-center text-[10px] text-white"></span>
                                </div>
                                <div class="my-4">
                                    <span class="text-2xl sm:text-3xl font-black text-white font-en">৳ ৯৯</span>
                                    <span class="text-xs text-slate-400 block mt-0.5">/ ১ মাস</span>
                                </div>
                                <ul class="space-y-2 text-xs text-slate-300">
                                    <li class="flex items-center gap-2"><span class="text-rose-400 font-bold">✓</span> আনলিমিটেড কাস্টমার কিউ</li>
                                    <li class="flex items-center gap-2"><span class="text-rose-400 font-bold">✓</span> বিকাশ ও নগদ পেমেন্ট ম্যাচিং</li>
                                    <li class="flex items-center gap-2"><span class="text-rose-400 font-bold">✓</span> ২ প্রিন্টার কানেকশন</li>
                                </ul>
                            </div>

                            <!-- SHOP PLAN 2: Yearly Pro (Recommended) -->
                            <div onclick="selectPlan('yearly_pro', 'ইয়ারলি প্রো', 999, '১ বছর')" class="plan-card relative bg-slate-900/90 p-5 sm:p-6 rounded-3xl border-2 cursor-pointer transition-all hover:border-rose-500 {{ ($preselectedPlan === 'yearly_pro' || !$preselectedPlan) ? 'border-rose-500 shadow-xl shadow-rose-500/20' : 'border-slate-800' }}" id="pcard_yearly_pro">
                                <div class="absolute -top-3 left-1/2 -translate-x-1/2 px-3 py-0.5 rounded-full bg-gradient-to-r from-rose-500 to-pink-500 text-white text-[9px] font-black uppercase tracking-wider shadow">
                                    ★ সবচেয়ে জনপ্রিয় (Best Value)
                                </div>
                                <div class="flex items-center justify-between pb-3 border-b border-slate-800 mt-1">
                                    <span class="text-sm font-bold text-white">ইয়ারলি প্রো</span>
                                    <span class="plan-checkmark w-5 h-5 rounded-full border-2 border-slate-600 flex items-center justify-center text-[10px] text-white"></span>
                                </div>
                                <div class="my-4">
                                    <span class="text-2xl sm:text-3xl font-black text-rose-400 font-en">৳ ৯৯৯</span>
                                    <span class="text-xs text-slate-400 block mt-0.5">/ ১ বছর (১২ মাস)</span>
                                </div>
                                <ul class="space-y-2 text-xs text-slate-300">
                                    <li class="flex items-center gap-2"><span class="text-rose-400 font-bold">✓</span> আনলিমিটেড প্রিন্ট কিউ</li>
                                    <li class="flex items-center gap-2"><span class="text-rose-400 font-bold">✓</span> স্মার্ট এনআইডি কার্ড ২-সাইড প্রিন্ট</li>
                                    <li class="flex items-center gap-2"><span class="text-rose-400 font-bold">✓</span> ৫টি প্রিন্টার কানেকশন</li>
                                    <li class="flex items-center gap-2"><span class="text-rose-400 font-bold">✓</span> ফোন ও AnyDesk প্রায়োরিটি সাপোর্ট</li>
                                </ul>
                            </div>

                            <!-- SHOP PLAN 3: Lifetime -->
                            <div onclick="selectPlan('lifetime', 'লাইফটাইম আনলিমিটেড', 3999, 'আজীবন')" class="plan-card relative bg-slate-900/90 p-5 sm:p-6 rounded-3xl border-2 cursor-pointer transition-all hover:border-purple-500 {{ $preselectedPlan === 'lifetime' ? 'border-purple-500 shadow-xl shadow-purple-500/20' : 'border-slate-800' }}" id="pcard_lifetime">
                                <div class="absolute top-0 right-0 px-3 py-1 bg-gradient-to-l from-purple-600 to-indigo-600 text-white text-[9px] font-black rounded-bl-xl uppercase">
                                    ⚡ সীমিত অফার
                                </div>
                                <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                                    <span class="text-sm font-bold text-purple-300">👑 লাইফটাইম</span>
                                    <span class="plan-checkmark w-5 h-5 rounded-full border-2 border-slate-600 flex items-center justify-center text-[10px] text-white"></span>
                                </div>
                                <div class="my-4">
                                    <span class="text-2xl sm:text-3xl font-black text-purple-400 font-en">৳ ৩,৯৯৯</span>
                                    <span class="text-xs text-slate-400 block mt-0.5">/ এককালীন আজীবন</span>
                                </div>
                                <ul class="space-y-2 text-xs text-slate-300">
                                    <li class="flex items-center gap-2"><span class="text-purple-400 font-bold">✓</span> কখনো কোনো রিনিউয়াল ফি নেই</li>
                                    <li class="flex items-center gap-2"><span class="text-purple-400 font-bold">✓</span> আজীবন সব নতুন ফিচার ফ্রি</li>
                                    <li class="flex items-center gap-2"><span class="text-purple-400 font-bold">✓</span> আনলিমিটেড প্রিন্টার কানেকশন</li>
                                </ul>
                            </div>
                        @endif
                    </div>

                    <!-- Step 1 Actions -->
                    <div class="pt-4 flex justify-end">
                        <button type="button" onclick="goToStep(2)" class="px-8 py-3.5 rounded-2xl bg-gradient-to-r {{ $shop->is_office_mode ? 'from-indigo-600 to-blue-600 hover:from-indigo-700' : 'from-rose-500 to-pink-500 hover:from-rose-600' }} text-white font-black text-sm shadow-xl flex items-center gap-2 cursor-pointer transition hover:scale-[1.02]">
                            <span>পরবর্তী ধাপ: পেমেন্ট মাধ্যম নির্বাচন →</span>
                        </button>
                    </div>
                </div>

                <!-- ================= STEP 2: PAYMENT METHOD TILES ================= -->
                <div id="stepContainer2" class="step-content hidden space-y-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-xl sm:text-2xl font-black text-white">২. পেমেন্ট মাধ্যম বেছে নিন</h2>
                            <p class="text-xs sm:text-sm text-slate-400 mt-1">যেকোনো একটি বড় টাইল ক্লিক করুন — পুরো পেমেন্ট প্যানেল সেই ব্র্যান্ডের হবে</p>
                        </div>
                        <span class="text-xs font-bold text-rose-400 bg-rose-500/10 px-3 py-1 rounded-full border border-rose-500/20">ধাপ ২ / ৪</span>
                    </div>

                    <!-- 3 LARGE BRANDED TILES -->
                    <div class="grid sm:grid-cols-3 gap-5">
                        <!-- bKash Tile -->
                        <div onclick="selectMethod('bkash')" id="methodTile_bkash" class="method-tile p-5 sm:p-6 rounded-3xl border-2 border-pink-500/80 bg-pink-500/10 cursor-pointer transition-all hover:scale-[1.02] shadow-xl relative overflow-hidden group">
                            <div class="flex items-center justify-between pb-3 border-b border-pink-500/30">
                                <div class="h-10 px-3 rounded-xl bg-white flex items-center justify-center shadow-md">
                                    <img src="{{ asset('images/payments/bkash.png') }}" alt="bKash" class="h-7 w-auto object-contain">
                                </div>
                                <span class="method-check w-6 h-6 rounded-full bg-pink-500 text-white flex items-center justify-center text-xs font-black">✓</span>
                            </div>
                            <div class="mt-4 space-y-1">
                                <div class="text-base sm:text-lg font-black text-white">বিকাশ (bKash)</div>
                                <div class="text-xs text-pink-300 font-medium">Send Money (পার্সোনাল)</div>
                            </div>
                            <div class="mt-4 pt-3 border-t border-pink-500/20 text-[11px] text-pink-200 flex items-center gap-1.5 font-bold">
                                <span>📱 অ্যাপ অথবা *247#</span>
                            </div>
                        </div>

                        <!-- Nagad Tile -->
                        <div onclick="selectMethod('nagad')" id="methodTile_nagad" class="method-tile p-5 sm:p-6 rounded-3xl border-2 border-slate-800 bg-slate-900/90 cursor-pointer transition-all hover:scale-[1.02] hover:border-amber-500/80 relative overflow-hidden group">
                            <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                                <div class="h-10 px-3 rounded-xl bg-white flex items-center justify-center shadow-md">
                                    <img src="{{ asset('images/payments/nagad.png') }}" alt="Nagad" class="h-7 w-auto object-contain">
                                </div>
                                <span class="method-check w-6 h-6 rounded-full border-2 border-slate-700 flex items-center justify-center text-xs font-black text-white"></span>
                            </div>
                            <div class="mt-4 space-y-1">
                                <div class="text-base sm:text-lg font-black text-white">নগদ (Nagad)</div>
                                <div class="text-xs text-slate-400 font-medium">Send Money (পার্সোনাল)</div>
                            </div>
                            <div class="mt-4 pt-3 border-t border-slate-800 text-[11px] text-slate-400 flex items-center gap-1.5 font-bold">
                                <span>📱 অ্যাপ অথবা *167#</span>
                            </div>
                        </div>

                        <!-- Rocket Tile -->
                        <div onclick="selectMethod('rocket')" id="methodTile_rocket" class="method-tile p-5 sm:p-6 rounded-3xl border-2 border-slate-800 bg-slate-900/90 cursor-pointer transition-all hover:scale-[1.02] hover:border-purple-500/80 relative overflow-hidden group">
                            <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                                <div class="h-10 px-3 rounded-xl bg-white flex items-center justify-center shadow-md">
                                    <img src="{{ asset('images/payments/rocket.svg') }}" alt="Rocket" class="h-7 w-auto object-contain">
                                </div>
                                <span class="method-check w-6 h-6 rounded-full border-2 border-slate-700 flex items-center justify-center text-xs font-black text-white"></span>
                            </div>
                            <div class="mt-4 space-y-1">
                                <div class="text-base sm:text-lg font-black text-white">রকেট (Rocket)</div>
                                <div class="text-xs text-slate-400 font-medium">Send Money (পার্সোনাল)</div>
                            </div>
                            <div class="mt-4 pt-3 border-t border-slate-800 text-[11px] text-slate-400 flex items-center gap-1.5 font-bold">
                                <span>📱 ডাচ-বাংলা রকেট অ্যাপ</span>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2 Actions -->
                    <div class="pt-4 flex items-center justify-between">
                        <button type="button" onclick="goToStep(1)" class="px-6 py-3 rounded-2xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold text-xs cursor-pointer transition">
                            ← প্যাকেজ পরিবর্তন
                        </button>
                        <button type="button" onclick="goToStep(3)" class="px-8 py-3.5 rounded-2xl bg-gradient-to-r {{ $shop->is_office_mode ? 'from-indigo-600 to-blue-600 hover:from-indigo-700' : 'from-rose-500 to-pink-500 hover:from-rose-600' }} text-white font-black text-sm shadow-xl flex items-center gap-2 cursor-pointer transition hover:scale-[1.02]">
                            <span>পরবর্তী ধাপ: টাকা পাঠানোর তথ্য →</span>
                        </button>
                    </div>
                </div>

                <!-- ================= STEP 3: SEND MONEY INSTRUCTIONS & NUMBER ================= -->
                <div id="stepContainer3" class="step-content hidden space-y-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-xl sm:text-2xl font-black text-white">৩. নির্বাচিত নম্বরে টাকা পাঠান</h2>
                            <p class="text-xs sm:text-sm text-slate-400 mt-1">নম্বর ও টাকার পরিমাণ কপি করে আপনার মোবাইল ব্যাংকিং অ্যাপ থেকে Send Money সম্পন্ন করুন</p>
                        </div>
                        <span class="text-xs font-bold text-rose-400 bg-rose-500/10 px-3 py-1 rounded-full border border-rose-500/20">ধাপ ৩ / ৪</span>
                    </div>

                    <!-- Branded Interactive Box (Colors change automatically!) -->
                    <div id="sendMoneyBrandBox" class="p-6 sm:p-8 rounded-3xl border-2 border-pink-500/60 bg-pink-500/5 shadow-2xl space-y-6 transition-all duration-300">
                        
                        <!-- Account Type Badge & Warning -->
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-pink-500/20" id="sendMoneyHeaderBorder">
                            <div class="flex items-center gap-3">
                                <div class="h-12 px-3 rounded-2xl bg-white flex items-center justify-center shadow-md shrink-0">
                                    <img id="brandLogoImg" src="{{ asset('images/payments/bkash.png') }}" alt="bKash" class="h-8 w-auto object-contain">
                                </div>
                                <div>
                                    <div id="brandTitleText" class="text-base sm:text-lg font-black text-white">বিকাশ পার্সোনাল একাউন্ট (bKash Personal)</div>
                                    <span class="text-xs text-pink-400 font-bold" id="brandSubText">Send Money করুন — পেমেন্ট বা ক্যাশআউট নয়</span>
                                </div>
                            </div>
                            <span class="px-3 py-1 rounded-full bg-amber-500/20 text-amber-300 border border-amber-500/30 text-xs font-bold self-start sm:self-auto">
                                ⚠️ শুধুমাত্র "Send Money" প্রযোজ্য
                            </span>
                        </div>

                        <!-- Numbers and Amount in Big Font with Copy Buttons -->
                        <div class="grid sm:grid-cols-2 gap-4">
                            <!-- Recipient Number Box -->
                            <div class="p-4 sm:p-5 rounded-2xl bg-slate-950/90 border border-slate-800 space-y-2">
                                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">প্রাপক নম্বর (Recipient Number):</span>
                                <div class="flex items-center justify-between gap-2">
                                    <span id="currentMethodNumber" class="text-xl sm:text-2xl font-black text-white font-mono tracking-wider">{{ $adminBkash }}</span>
                                    <button type="button" onclick="copyCurrentNumber()" class="px-3.5 py-2 rounded-xl bg-pink-500 hover:bg-pink-600 text-white text-xs font-bold transition shadow cursor-pointer shrink-0" id="copyNumBtn">
                                        📋 কপি করুন
                                    </button>
                                </div>
                                <span class="text-[10px] text-slate-400 block pt-1">অ্যাকাউন্টের ধরন: <strong class="text-emerald-400">ব্যক্তিগত (Personal)</strong></span>
                            </div>

                            <!-- Payable Amount Box -->
                            <div class="p-4 sm:p-5 rounded-2xl bg-slate-950/90 border border-slate-800 space-y-2">
                                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">প্রদেয় টাকার পরিমাণ (Payable Amount):</span>
                                <div class="flex items-center justify-between gap-2">
                                    <span id="currentStepAmount" class="text-xl sm:text-2xl font-black text-emerald-400 font-en tracking-wider">৳ ৯৯৯</span>
                                    <button type="button" onclick="copyCurrentAmount()" class="px-3.5 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-white text-xs font-bold transition shadow cursor-pointer shrink-0">
                                        📋 কপি
                                    </button>
                                </div>
                                <span class="text-[10px] text-slate-400 block pt-1">প্যাকেজ: <strong id="currentStepPlanTitle" class="text-white">ইয়ারলি প্রো (১ বছর)</strong></span>
                            </div>
                        </div>

                        <!-- 4 Step Visual App Instructions -->
                        <div class="p-4 sm:p-5 rounded-2xl bg-slate-950/60 border border-slate-800/80 space-y-3">
                            <span class="text-xs font-black text-white block">টাকা পাঠানোর ৪টি সহজ ধাপ:</span>
                            <div class="grid sm:grid-cols-4 gap-3 text-xs">
                                <div class="p-3 rounded-xl bg-slate-900 border border-slate-800/80 space-y-1">
                                    <span class="text-[10px] font-bold text-pink-400 block" id="guideStep1Brand">১. অ্যাপ খুলুন</span>
                                    <p class="text-slate-300 text-[11px]">আপনার বিকাশ/নগদ/রকেট অ্যাপে লগইন করুন</p>
                                </div>
                                <div class="p-3 rounded-xl bg-slate-900 border border-slate-800/80 space-y-1">
                                    <span class="text-[10px] font-bold text-pink-400 block" id="guideStep2Brand">২. Send Money</span>
                                    <p class="text-slate-300 text-[11px]">মেনু থেকে <strong>"Send Money"</strong> চাপুন</p>
                                </div>
                                <div class="p-3 rounded-xl bg-slate-900 border border-slate-800/80 space-y-1">
                                    <span class="text-[10px] font-bold text-pink-400 block" id="guideStep3Brand">৩. নম্বর ও টাকা</span>
                                    <p class="text-slate-300 text-[11px]">কপি করা নম্বর ও নির্ধারিত টাকা বসান</p>
                                </div>
                                <div class="p-3 rounded-xl bg-slate-900 border border-slate-800/80 space-y-1">
                                    <span class="text-[10px] font-bold text-pink-400 block" id="guideStep4Brand">৪. পিন ও TrxID</span>
                                    <p class="text-slate-300 text-[11px]">পিন দিয়ে সম্পন্ন করে TrxID কপি করুন</p>
                                </div>
                            </div>
                        </div>

                        <!-- Clarification Notice -->
                        <div class="p-3.5 rounded-xl bg-slate-950 border border-slate-800 text-[11px] text-slate-400 flex items-start gap-2.5">
                            <span class="text-base mt-0.5">💡</span>
                            <div>
                                <span class="font-bold text-slate-300 block mb-0.5">নম্বর সংক্রান্ত পরামর্শ:</span>
                                বিকাশ, নগদ বা রকেটের নম্বর একই হতে পারে, কিন্তু আপনি ধাপ ২-এ যে মাধ্যম বেছে নিয়েছেন সেই নির্দিষ্ট অ্যাপ থেকেই টাকা পাঠাবেন।
                            </div>
                        </div>

                    </div>

                    <!-- Step 3 Actions -->
                    <div class="pt-4 flex items-center justify-between">
                        <button type="button" onclick="goToStep(2)" class="px-6 py-3 rounded-2xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold text-xs cursor-pointer transition">
                            ← মাধ্যম পরিবর্তন
                        </button>
                        <button type="button" onclick="goToStep(4)" class="px-8 py-3.5 rounded-2xl bg-gradient-to-r {{ $shop->is_office_mode ? 'from-indigo-600 to-blue-600 hover:from-indigo-700' : 'from-rose-500 to-pink-500 hover:from-rose-600' }} text-white font-black text-sm shadow-xl flex items-center gap-2 cursor-pointer transition hover:scale-[1.02]">
                            <span>টাকা পাঠিয়েছি, ট্রানজেকশন যাচাই করুন →</span>
                        </button>
                    </div>
                </div>

                <!-- ================= STEP 4: VERIFICATION FORM ================= -->
                <div id="stepContainer4" class="step-content hidden space-y-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-xl sm:text-2xl font-black text-white">৪. পেমেন্ট ট্রানজেকশন তথ্য দিন</h2>
                            <p class="text-xs sm:text-sm text-slate-400 mt-1">টাকা পাঠানোর পর প্রাপ্ত Transaction ID ও আপনার প্রেরক মোবাইল নম্বরটি লিখুন</p>
                        </div>
                        <span class="text-xs font-bold text-rose-400 bg-rose-500/10 px-3 py-1 rounded-full border border-rose-500/20">ধাপ ৪ / ৪</span>
                    </div>

                    <form id="subscriptionPurchaseForm" action="{{ route('shop.subscription.purchase') }}" method="POST" onsubmit="return handleFormSubmit(event)" class="bg-slate-900/90 p-6 sm:p-8 rounded-3xl border border-slate-800 shadow-2xl space-y-6">
                        @csrf
                        <input type="hidden" name="plan" id="formPlanInput" value="{{ $preselectedPlan ?: ($shop->is_office_mode ? 'office_basic_yearly' : 'yearly_pro') }}">
                        <input type="hidden" name="payment_method" id="formMethodInput" value="bkash">

                        <!-- Selected Summary Bar inside Step 4 -->
                        <div class="p-4 rounded-2xl bg-slate-950 border border-slate-800 flex flex-wrap items-center justify-between gap-3">
                            <div class="flex items-center gap-2 text-xs">
                                <span class="text-slate-400">নির্বাচিত প্যাকেজ:</span>
                                <span id="formSummaryPlan" class="font-bold text-white">ইয়ারলি প্রো (৳ ৯৯৯)</span>
                            </div>
                            <div class="flex items-center gap-2 text-xs">
                                <span class="text-slate-400">মাধ্যম:</span>
                                <span id="formSummaryMethod" class="font-bold uppercase text-pink-400">বিকাশ (bKash)</span>
                            </div>
                            <button type="button" onclick="goToStep(1)" class="text-[11px] text-slate-400 hover:text-white underline cursor-pointer">
                                পরিবর্তন
                            </button>
                        </div>

                        <!-- Sender Phone Number Field with Real-time Regex Validation -->
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <label for="sender_number_input" class="block text-xs font-bold text-slate-300">
                                    যে নম্বর থেকে টাকা পাঠিয়েছেন (Sender Mobile Number) <span class="text-rose-500">*</span>
                                </label>
                                <span id="phoneValidationStatus" class="text-[11px] font-bold text-slate-500">১১ ডিজিট প্রয়োজন</span>
                            </div>
                            <div class="relative">
                                <input type="text" id="sender_number_input" name="sender_number" required maxlength="11" 
                                    placeholder="017XXXXXXXX" value="{{ old('sender_number', $shop->phone) }}" 
                                    oninput="validateSenderPhone()" 
                                    class="w-full px-4 py-3.5 rounded-2xl bg-slate-950 border-2 border-slate-800 text-sm font-mono text-white placeholder-slate-600 focus:outline-none focus:border-rose-500 transition-colors">
                                <span id="phoneCheckIcon" class="absolute right-4 top-1/2 -translate-y-1/2 text-sm hidden">✓</span>
                            </div>
                            <p id="phoneErrorMessage" class="text-[11px] text-rose-400 hidden">সঠিক ১১ ডিজিটের মোবাইল নম্বর দিন (উদাঃ 01712345678)।</p>
                        </div>

                        <!-- Transaction ID (TrxID) Field with Auto-Uppercase, Strip Spaces & Paste Button -->
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <label for="transaction_id_input" class="block text-xs font-bold text-slate-300">
                                    ট্রানজেকশন আইডি (Transaction ID / TrxID) <span class="text-rose-500">*</span>
                                </label>
                                <span id="trxValidationStatus" class="text-[11px] font-bold text-slate-500">কমপক্ষে ৬ ডিজিট/অক্ষর</span>
                            </div>
                            <div class="relative">
                                <input type="text" id="transaction_id_input" name="transaction_id" required maxlength="40" 
                                    placeholder="উদাঃ BKB7A89DFQ" value="{{ old('transaction_id') }}" 
                                    oninput="formatAndValidateTrxID()" 
                                    class="w-full pl-4 pr-24 py-3.5 rounded-2xl bg-slate-950 border-2 border-slate-800 text-sm font-mono uppercase text-white placeholder-slate-600 focus:outline-none focus:border-rose-500 transition-colors tracking-wider">
                                <button type="button" onclick="pasteTrxID()" class="absolute right-2 top-1/2 -translate-y-1/2 px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-bold transition cursor-pointer flex items-center gap-1">
                                    <span>📋 পেস্ট</span>
                                </button>
                            </div>
                            <p id="trxErrorMessage" class="text-[11px] text-rose-400 hidden">সঠিক Transaction ID দিন (কমপক্ষে ৬ অক্ষর/ডিজিট)।</p>
                        </div>

                        <!-- Security & Trust Notice -->
                        <div class="p-4 rounded-2xl bg-slate-950/90 border border-slate-800 flex items-start gap-3 text-xs text-slate-400">
                            <span class="text-xl mt-0.5">🛡️</span>
                            <div>
                                <span class="font-bold text-white block mb-0.5">নিরাপত্তা ও বিশ্বাসযোগ্যতা গ্যারান্টি:</span>
                                আপনার বিকাশ/নগদ/রকেটের গোপনীয় পিন (PIN) বা কোনো OTP আমরা কখনো চাই না। কাউকে কখনোই আপনার পিন শেয়ার করবেন না। সাধারণত ৫ থেকে ১৫ মিনিটের মধ্যে পেমেন্ট স্বয়ংক্রিয়ভাবে ভেরিফাই হয়ে যায়।
                            </div>
                        </div>

                        <!-- Step 4 Actions & Submit Button -->
                        <div class="pt-2 flex flex-col sm:flex-row items-center gap-4 justify-between">
                            <button type="button" onclick="goToStep(3)" class="w-full sm:w-auto px-6 py-3 rounded-2xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold text-xs cursor-pointer transition">
                                ← টাকা পাঠানোর তথ্য দেখুন
                            </button>
                            <button type="submit" id="submitPaymentBtn" disabled class="w-full sm:w-auto px-10 py-3.5 rounded-2xl bg-gradient-to-r {{ $shop->is_office_mode ? 'from-indigo-600 to-blue-600 hover:from-indigo-700' : 'from-rose-500 to-pink-500 hover:from-rose-600' }} text-white font-black text-sm shadow-xl flex items-center justify-center gap-2 cursor-pointer transition disabled:opacity-40 disabled:cursor-not-allowed hover:scale-[1.01]">
                                <span id="submitBtnSpinner" class="hidden animate-spin">🌀</span>
                                <span id="submitBtnText">🚀 {{ $shop->is_office_mode ? 'কর্পোরেট লাইসেন্স সক্রিয় করার অনুরোধ পাঠান' : 'সাবস্ক্রিপশন সক্রিয় করার অনুরোধ পাঠান' }}</span>
                            </button>
                        </div>
                    </form>
                </div>

            </div>

            <!-- RIGHT 4 COLS: Persistent Desktop Order Summary Card -->
            <div class="lg:col-span-4 sticky top-6 space-y-5">
                <div class="bg-slate-900/90 p-6 rounded-3xl border border-slate-800 shadow-xl space-y-5">
                    <div class="pb-3 border-b border-slate-800 flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">অর্ডার সামারি (Summary)</span>
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    </div>

                    <!-- Selected Plan Line -->
                    <div class="space-y-1">
                        <span class="text-[11px] text-slate-400 block">নির্বাচিত প্যাকেজ:</span>
                        <div class="text-base font-black text-white" id="summaryPlanTitle">ইয়ারলি প্রো (১ বছর)</div>
                        <span class="text-xs text-slate-400 block" id="summaryPlanDuration">মেয়াদ: ১২ মাস</span>
                    </div>

                    <!-- Selected Method Line -->
                    <div class="space-y-1 pt-2 border-t border-slate-800/80">
                        <span class="text-[11px] text-slate-400 block">পেমেন্ট মাধ্যম:</span>
                        <div class="text-sm font-bold text-pink-400 flex items-center gap-2" id="summaryMethodText">
                            <div class="h-6 px-1.5 rounded bg-white flex items-center justify-center shadow-sm shrink-0">
                                <img id="summaryMethodLogo" src="{{ asset('images/payments/bkash.png') }}" alt="bKash" class="h-4 w-auto object-contain">
                            </div>
                            <span id="summaryMethodLabel">বিকাশ (bKash Send Money)</span>
                        </div>
                    </div>

                    <!-- Total Price in Bold -->
                    <div class="p-4 rounded-2xl bg-slate-950 border border-slate-800 space-y-1">
                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">সর্বমোট প্রদেয় টাকা:</span>
                        <div class="text-3xl font-black text-emerald-400 font-en" id="summaryTotalPrice">৳ ৯৯৯</div>
                        <span class="text-[10px] text-slate-500 block">কোনো লুকানো বা অতিরিক্ত ভ্যাট নেই</span>
                    </div>

                    <!-- Direct Step Navigation on Summary Card -->
                    <div class="pt-2">
                        <button type="button" onclick="nextStep()" id="summaryNextBtn" class="w-full py-3.5 px-4 rounded-2xl bg-gradient-to-r {{ $shop->is_office_mode ? 'from-indigo-600 to-blue-600 hover:from-indigo-700' : 'from-rose-500 to-pink-500 hover:from-rose-600' }} text-white font-black text-xs sm:text-sm shadow-xl flex items-center justify-center gap-2 cursor-pointer transition hover:scale-[1.02]">
                            <span id="summaryNextBtnText">পরবর্তী ধাপ →</span>
                        </button>
                    </div>

                    <!-- Helpline & Direct Support -->
                    <div class="pt-2 border-t border-slate-800/80 space-y-2 text-xs">
                        <span class="text-[11px] text-slate-400 block">সরাসরি সহায়তা:</span>
                        <div class="flex items-center justify-between">
                            <a href="tel:01644416378" class="text-slate-300 hover:text-white font-bold flex items-center gap-1.5">
                                <span>📞</span> <span>01644416378</span>
                            </a>
                            <a href="https://wa.me/8801644416378" target="_blank" class="text-emerald-400 hover:text-emerald-300 font-bold flex items-center gap-1.5">
                                <span>💬</span> <span>WhatsApp</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <!-- MOBILE STICKY BOTTOM BAR -->
    <div id="mobileStickyBar" class="lg:hidden fixed bottom-0 left-0 right-0 z-40 bg-slate-900/95 backdrop-blur-md border-t border-slate-800 p-3 sm:p-4 px-4 shadow-2xl flex items-center justify-between gap-3">
        <div class="space-y-0.5">
            <span class="text-[10px] text-slate-400 uppercase tracking-wider block font-bold" id="mobilePlanLabel">ইয়ারলি প্রো</span>
            <div class="text-lg font-black text-emerald-400 font-en leading-none" id="mobilePlanPrice">৳ ৯৯৯</div>
        </div>
        <button type="button" onclick="nextStep()" id="mobileNextBtn" class="px-6 py-2.5 rounded-xl bg-gradient-to-r {{ $shop->is_office_mode ? 'from-indigo-600 to-blue-600' : 'from-rose-500 to-pink-500' }} text-white font-black text-xs shadow-lg flex items-center gap-2 cursor-pointer">
            <span id="mobileNextBtnText">পরবর্তী ধাপ →</span>
        </button>
    </div>

    <!-- SUBSCRIPTION REQUEST HISTORY -->
    <div class="bg-slate-900/80 rounded-3xl border border-slate-800 shadow-xl overflow-hidden mt-8">
        <div class="p-6 border-b border-slate-800 flex items-center justify-between">
            <div>
                <h3 class="text-base font-black text-white">পেমেন্ট ও সাবস্ক্রিপশন হিস্ট্রি</h3>
                <p class="text-xs text-slate-400 mt-0.5">আপনার পূর্বের সকল প্যাকেজ রিকোয়েস্ট, ইনভয়েস ও অনুমোদনের স্ট্যাটাস</p>
            </div>
            <span id="subHistoryCount" class="text-xs text-slate-400 font-mono">{{ count($subscriptionsHistory) }} টি রেকর্ড</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-950 text-slate-400 font-bold border-b border-slate-800 uppercase text-[10px] tracking-wider">
                    <tr>
                        <th class="px-6 py-3.5">তারিখ</th>
                        <th class="px-6 py-3.5">প্যাকেজ</th>
                        <th class="px-6 py-3.5">টাকা</th>
                        <th class="px-6 py-3.5">মাধ্যম</th>
                        <th class="px-6 py-3.5">TrxID / সেন্ডার</th>
                        <th class="px-6 py-3.5">স্ট্যাটাস</th>
                        <th class="px-6 py-3.5 text-right">রসিদ</th>
                    </tr>
                </thead>
                <tbody id="subHistoryTbody" class="divide-y divide-slate-800/80">
                    @forelse($subscriptionsHistory as $sub)
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            <td class="px-6 py-4 text-slate-300 font-mono">{{ $sub->created_at->format('d M, Y h:i A') }}</td>
                            <td class="px-6 py-4 font-bold text-white">{{ $sub->plan_name }}</td>
                            <td class="px-6 py-4 font-en font-bold text-emerald-400">৳ {{ number_format($sub->amount, 0) }}</td>
                            <td class="px-6 py-4 uppercase font-bold">
                                @if($sub->payment_method === 'bkash')
                                    <span class="px-2 py-0.5 rounded-md bg-pink-500/15 text-pink-400 font-bold border border-pink-500/30">bKash</span>
                                @elseif($sub->payment_method === 'nagad')
                                    <span class="px-2 py-0.5 rounded-md bg-amber-500/15 text-amber-400 font-bold border border-amber-500/30">Nagad</span>
                                @elseif($sub->payment_method === 'rocket')
                                    <span class="px-2 py-0.5 rounded-md bg-purple-500/15 text-purple-400 font-bold border border-purple-500/30">Rocket</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-md bg-slate-800 text-slate-300 font-bold">{{ $sub->payment_method }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-mono font-bold {{ $shop->is_office_mode ? 'text-indigo-400' : 'text-rose-400' }}">{{ $sub->transaction_id ?? 'N/A' }}</div>
                                <div class="text-[10px] text-slate-500 font-mono">{{ $sub->sender_number }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @if($sub->status === 'approved')
                                    <span class="px-2.5 py-1 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 font-bold text-[10px]">
                                        ● অনুমোদিত (Active)
                                    </span>
                                @elseif($sub->status === 'rejected')
                                    <span class="px-2.5 py-1 rounded-full bg-rose-500/10 text-rose-400 border border-rose-500/20 font-bold text-[10px]">
                                        ✕ বাতিল (Rejected)
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full bg-amber-500/10 text-amber-400 border border-amber-500/20 font-bold text-[10px] animate-pulse">
                                        ⏳ পেন্ডিং (Verifying)
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if($sub->status === 'approved')
                                    <button type="button" onclick="showInvoiceDirect('{{ 'PS-INV-' . str_pad($sub->id, 5, '0', STR_PAD_LEFT) }}', '{{ $sub->plan_name }}', '{{ $sub->amount }}', '{{ strtoupper($sub->payment_method) }}', '{{ $sub->transaction_id }}', '{{ $sub->sender_number }}', '{{ $sub->created_at->format('d M, Y') }}', '{{ $sub->approved_at ? $sub->approved_at->format('d M, Y') : $sub->created_at->format('d M, Y') }}')" class="px-2.5 py-1 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold text-[11px] transition cursor-pointer">
                                        🧾 রসিদ
                                    </button>
                                @else
                                    <span class="text-slate-600 text-[11px]">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-slate-500">এখনো কোনো প্যাকেজ রিকোয়েস্ট করা হয়নি</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- PRINTABLE MONEY RECEIPT / INVOICE MODAL -->
<div id="receiptModal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-slate-900 border border-slate-700 rounded-3xl max-w-lg w-full p-6 sm:p-8 space-y-6 shadow-2xl relative text-white" id="printableReceiptArea">
        
        <!-- Header -->
        <div class="flex items-center justify-between border-b border-slate-800 pb-4">
            <div class="flex items-center gap-2">
                <span class="text-xl">🖨️</span>
                <div>
                    <h3 class="font-black text-lg text-white">PrintSheba</h3>
                    <span class="text-[10px] text-slate-400">অফিসিয়াল মানি রিসিট / সাবস্ক্রিপশন ইনভয়েস</span>
                </div>
            </div>
            <div class="text-right">
                <span class="text-[10px] text-slate-400 block">ইনভয়েস নং:</span>
                <span id="modalInvoiceNo" class="font-mono font-bold text-rose-400 text-xs">PS-INV-00001</span>
            </div>
        </div>

        <!-- Receipt Body Details -->
        <div class="p-5 rounded-2xl bg-slate-950 border border-slate-800 space-y-3 text-xs">
            <div class="flex justify-between py-1 border-b border-slate-800/80">
                <span class="text-slate-400">গ্রাহক / প্রতিষ্ঠান:</span>
                <span class="font-bold text-white">{{ $shop->name }}</span>
            </div>
            <div class="flex justify-between py-1 border-b border-slate-800/80">
                <span class="text-slate-400">প্যাকেজের নাম:</span>
                <span id="modalPlanName" class="font-bold text-white">ইয়ারলি প্রো</span>
            </div>
            <div class="flex justify-between py-1 border-b border-slate-800/80">
                <span class="text-slate-400">পরিশোধের মাধ্যম:</span>
                <span id="modalMethod" class="font-bold uppercase text-white">BKASH</span>
            </div>
            <div class="flex justify-between py-1 border-b border-slate-800/80">
                <span class="text-slate-400">ট্রানজেকশন আইডি (TrxID):</span>
                <span id="modalTrxId" class="font-mono font-bold text-amber-300">BKB1234567</span>
            </div>
            <div class="flex justify-between py-1 border-b border-slate-800/80">
                <span class="text-slate-400">প্রেরক নম্বর:</span>
                <span id="modalSender" class="font-mono text-white">01712345678</span>
            </div>
            <div class="flex justify-between py-1 border-b border-slate-800/80">
                <span class="text-slate-400">অনুমোদনের তারিখ:</span>
                <span id="modalDate" class="font-mono text-white">21 Nov, 2026</span>
            </div>
            <div class="flex justify-between pt-2 text-sm">
                <span class="font-bold text-slate-300">মোট পরিশোধিত টাকা:</span>
                <span id="modalAmount" class="font-black text-emerald-400 font-en text-base">৳ ৯৯৯</span>
            </div>
        </div>

        <div class="text-center text-[10px] text-slate-400">
            ✓ এই রিসিটটি কম্পিউটার জেনারেটেড এবং প্রিন্টশেবা কেন্দ্রীয় লাইসেন্স ভেরিফাইড।
        </div>

        <!-- Modal Actions (Hidden during print) -->
        <div class="flex items-center justify-end gap-3 pt-2 modal-actions">
            <button type="button" onclick="closeReceiptModal()" class="px-5 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-bold transition cursor-pointer">
                বন্ধ করুন
            </button>
            <button type="button" onclick="window.print()" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold transition flex items-center gap-1.5 shadow cursor-pointer">
                <span>🖨️ প্রিন্ট করুন</span>
            </button>
        </div>
    </div>
</div>

<!-- CLIENT JAVASCRIPT FOR STEPPER, DYNAMIC BRANDING, VALIDATION & POLLING -->
<script>
    // System Numbers
    const adminNumbers = {
        'bkash': '{{ $adminBkash }}',
        'nagad': '{{ $adminNagad }}',
        'rocket': '{{ $adminRocket }}'
    };

    // Brand Payment Logos
    const paymentLogos = {
        'bkash': '{{ asset('images/payments/bkash.png') }}',
        'nagad': '{{ asset('images/payments/nagad.png') }}',
        'rocket': '{{ asset('images/payments/rocket.svg') }}'
    };

    // State Variables
    let currentStep = 1;
    let selectedPlan = '{{ $preselectedPlan ?: ($shop->is_office_mode ? 'office_basic_yearly' : 'yearly_pro') }}';
    let selectedPlanName = '{{ $shop->is_office_mode ? 'অফিস বেসিক (১ বছর)' : 'ইয়ারলি প্রো' }}';
    let selectedPlanPrice = {{ $shop->is_office_mode ? 4500 : 999 }};
    let selectedPlanDuration = '{{ $shop->is_office_mode ? '১ বছর' : '১২ মাস' }}';
    let selectedMethod = 'bkash';

    const isOffice = {{ $shop->is_office_mode ? 'true' : 'false' }};
    const primaryColor = isOffice ? 'indigo' : 'rose';

    // Step Navigation Function
    function goToStep(step) {
        if (step < 1 || step > 4) return;
        currentStep = step;

        // 1. Show/Hide Containers
        for (let i = 1; i <= 4; i++) {
            const container = document.getElementById('stepContainer' + i);
            if (container) {
                if (i === step) {
                    container.classList.remove('hidden');
                } else {
                    container.classList.add('hidden');
                }
            }
        }

        // 2. Update Tabs
        for (let i = 1; i <= 4; i++) {
            const tab = document.getElementById('stepTab' + i);
            if (!tab) continue;

            const badge = tab.querySelector('.step-badge');
            const indicator = tab.querySelector('.step-indicator');
            const title = tab.querySelector('.step-title');

            if (badge) {
                if (i === step) {
                    badge.className = `step-badge w-8 h-8 sm:w-10 sm:h-10 rounded-2xl ${isOffice ? 'bg-indigo-600 shadow-indigo-600/30' : 'bg-rose-500 shadow-rose-500/30'} text-white flex items-center justify-center font-black text-xs sm:text-sm shadow-lg transition-all`;
                    badge.innerText = i;
                } else if (i < step) {
                    badge.className = 'step-badge w-8 h-8 sm:w-10 sm:h-10 rounded-2xl bg-emerald-500 text-white flex items-center justify-center font-black text-xs sm:text-sm shadow transition-all';
                    badge.innerText = '✓';
                } else {
                    badge.className = 'step-badge w-8 h-8 sm:w-10 sm:h-10 rounded-2xl bg-slate-800 text-slate-500 flex items-center justify-center font-black text-xs sm:text-sm transition-all';
                    badge.innerText = i;
                }
            }

            if (indicator) {
                if (i === step) {
                    indicator.className = `h-1.5 w-full ${isOffice ? 'bg-indigo-500' : 'bg-rose-500'} rounded-full mt-3 step-indicator`;
                } else if (i < step) {
                    indicator.className = 'h-1.5 w-full bg-emerald-500 rounded-full mt-3 step-indicator';
                } else {
                    indicator.className = 'h-1.5 w-full bg-slate-800 rounded-full mt-3 step-indicator';
                }
            }

            if (title) {
                if (i === step) {
                    title.className = 'step-title text-xs sm:text-sm font-black text-white block';
                } else if (i < step) {
                    title.className = 'step-title text-xs sm:text-sm font-black text-slate-300 block';
                } else {
                    title.className = 'step-title text-xs sm:text-sm font-black text-slate-500 block';
                }
            }
        }

        // 3. Update Summary CTA Button text safely
        const summaryNextBtnText = document.getElementById('summaryNextBtnText');
        const mobileNextBtnText = document.getElementById('mobileNextBtnText');
        if (summaryNextBtnText) {
            if (step === 4) {
                summaryNextBtnText.innerText = 'যাচাই ও কনফার্ম ফর্মে আছি';
            } else if (step === 3) {
                summaryNextBtnText.innerText = 'টাকা পাঠানো শেষ, TrxID দিন →';
            } else if (step === 2) {
                summaryNextBtnText.innerText = 'পরবর্তী: টাকা পাঠান →';
            } else {
                summaryNextBtnText.innerText = 'পরবর্তী ধাপ: মাধ্যম নির্বাচন →';
            }
        }

        if (mobileNextBtnText) {
            if (step === 4) {
                mobileNextBtnText.innerText = 'সাবমিট করুন';
            } else if (step === 3) {
                mobileNextBtnText.innerText = 'TrxID দিন →';
            } else if (step === 2) {
                mobileNextBtnText.innerText = 'টাকা পাঠান →';
            } else {
                mobileNextBtnText.innerText = 'মাধ্যম নির্বাচন →';
            }
        }

        // 4. Safe Auto-scroll
        try {
            const pageEl = document.getElementById('subscriptionPage');
            if (pageEl && typeof pageEl.offsetTop === 'number') {
                window.scrollTo({ top: Math.max(0, pageEl.offsetTop - 30), behavior: 'smooth' });
            }
        } catch (e) {
            console.warn('Scroll warning:', e);
        }
    }

    function nextStep() {
        if (currentStep < 4) {
            goToStep(currentStep + 1);
        } else {
            document.getElementById('subscriptionPurchaseForm').dispatchEvent(new Event('submit', { cancelable: true }));
        }
    }

    // Plan Selection
    function selectPlan(planKey, planName, planPrice, duration) {
        selectedPlan = planKey;
        selectedPlanName = planName;
        selectedPlanPrice = planPrice;
        selectedPlanDuration = duration;

        document.getElementById('formPlanInput').value = planKey;

        // Visual highlight for cards
        document.querySelectorAll('.plan-card').forEach(card => {
            const check = card.querySelector('.plan-checkmark');
            card.classList.remove('border-rose-500', 'border-indigo-500', 'border-cyan-500', 'border-purple-500', 'shadow-xl');
            card.classList.add('border-slate-800');
            if (check) {
                check.className = 'plan-checkmark w-5 h-5 rounded-full border-2 border-slate-600 flex items-center justify-center text-[10px] text-white';
                check.innerText = '';
            }
        });

        const activeCard = document.getElementById('pcard_' + planKey);
        if (activeCard) {
            const borderCol = planKey.includes('lifetime') ? 'border-purple-500' : (isOffice ? 'border-indigo-500' : 'border-rose-500');
            const bgCol = planKey.includes('lifetime') ? 'bg-purple-600' : (isOffice ? 'bg-indigo-600' : 'bg-rose-500');
            activeCard.classList.remove('border-slate-800');
            activeCard.classList.add(borderCol, 'shadow-xl');
            const check = activeCard.querySelector('.plan-checkmark');
            if (check) {
                check.className = `plan-checkmark w-5 h-5 rounded-full ${bgCol} text-white flex items-center justify-center text-[10px] font-bold`;
                check.innerText = '✓';
            }
        }

        // Update Summary displays
        const formattedPrice = '৳ ' + Number(planPrice).toLocaleString('en-US');
        document.getElementById('summaryPlanTitle').innerText = planName;
        document.getElementById('summaryPlanDuration').innerText = 'মেয়াদ: ' + duration;
        document.getElementById('summaryTotalPrice').innerText = formattedPrice;
        document.getElementById('currentStepAmount').innerText = formattedPrice;
        document.getElementById('currentStepPlanTitle').innerText = planName;
        document.getElementById('formSummaryPlan').innerText = planName + ' (' + formattedPrice + ')';
        document.getElementById('mobilePlanLabel').innerText = planName;
        document.getElementById('mobilePlanPrice').innerText = formattedPrice;
    }

    // Method Selection & Dynamic Brand Color Customization
    function selectMethod(method) {
        selectedMethod = method;
        document.getElementById('formMethodInput').value = method;

        const tileBkash = document.getElementById('methodTile_bkash');
        const tileNagad = document.getElementById('methodTile_nagad');
        const tileRocket = document.getElementById('methodTile_rocket');

        // Reset Tiles
        [tileBkash, tileNagad, tileRocket].forEach(tile => {
            if (!tile) return;
            tile.className = 'method-tile p-5 sm:p-6 rounded-3xl border-2 border-slate-800 bg-slate-900/90 cursor-pointer transition-all hover:scale-[1.02] relative overflow-hidden group';
            const check = tile.querySelector('.method-check');
            if (check) {
                check.className = 'method-check w-6 h-6 rounded-full border-2 border-slate-700 flex items-center justify-center text-xs font-black text-white';
                check.innerText = '';
            }
        });

        // Set Active Tile & Dynamic Color Scheme
        const sendMoneyBox = document.getElementById('sendMoneyBrandBox');
        const brandLogoImg = document.getElementById('brandLogoImg');
        const brandTitleText = document.getElementById('brandTitleText');
        const brandSubText = document.getElementById('brandSubText');
        const copyNumBtn = document.getElementById('copyNumBtn');
        const currentMethodNumber = document.getElementById('currentMethodNumber');
        const formSummaryMethod = document.getElementById('formSummaryMethod');
        const summaryMethodLogo = document.getElementById('summaryMethodLogo');
        const summaryMethodLabel = document.getElementById('summaryMethodLabel');
        const summaryMethodText = document.getElementById('summaryMethodText');

        const activeNumber = adminNumbers[method] || '01644416378';
        if (currentMethodNumber) currentMethodNumber.innerText = activeNumber;

        if (brandLogoImg && paymentLogos[method]) {
            brandLogoImg.src = paymentLogos[method];
            brandLogoImg.alt = method.toUpperCase();
        }

        if (summaryMethodLogo && paymentLogos[method]) {
            summaryMethodLogo.src = paymentLogos[method];
            summaryMethodLogo.alt = method.toUpperCase();
        }

        if (method === 'bkash') {
            tileBkash.className = 'method-tile p-5 sm:p-6 rounded-3xl border-2 border-pink-500/80 bg-pink-500/10 cursor-pointer transition-all hover:scale-[1.02] shadow-xl relative overflow-hidden group';
            tileBkash.querySelector('.method-check').className = 'method-check w-6 h-6 rounded-full bg-pink-500 text-white flex items-center justify-center text-xs font-black';
            tileBkash.querySelector('.method-check').innerText = '✓';

            if (sendMoneyBox) sendMoneyBox.className = 'p-6 sm:p-8 rounded-3xl border-2 border-pink-500/60 bg-pink-500/5 shadow-2xl space-y-6 transition-all duration-300';
            if (brandTitleText) brandTitleText.innerText = 'বিকাশ পার্সোনাল একাউন্ট (bKash Personal)';
            if (brandSubText) brandSubText.className = 'text-xs text-pink-400 font-bold';
            if (copyNumBtn) copyNumBtn.className = 'px-3.5 py-2 rounded-xl bg-pink-500 hover:bg-pink-600 text-white text-xs font-bold transition shadow cursor-pointer shrink-0';

            if (formSummaryMethod) {
                formSummaryMethod.className = 'font-bold uppercase text-pink-400';
                formSummaryMethod.innerText = 'বিকাশ (bKash)';
            }
            if (summaryMethodLabel) summaryMethodLabel.innerText = 'বিকাশ (bKash Send Money)';
            if (summaryMethodText) summaryMethodText.className = 'text-sm font-bold text-pink-400 flex items-center gap-2';

        } else if (method === 'nagad') {
            tileNagad.className = 'method-tile p-5 sm:p-6 rounded-3xl border-2 border-amber-500/80 bg-amber-500/10 cursor-pointer transition-all hover:scale-[1.02] shadow-xl relative overflow-hidden group';
            tileNagad.querySelector('.method-check').className = 'method-check w-6 h-6 rounded-full bg-amber-500 text-white flex items-center justify-center text-xs font-black';
            tileNagad.querySelector('.method-check').innerText = '✓';

            if (sendMoneyBox) sendMoneyBox.className = 'p-6 sm:p-8 rounded-3xl border-2 border-amber-500/60 bg-amber-500/5 shadow-2xl space-y-6 transition-all duration-300';
            if (brandTitleText) brandTitleText.innerText = 'নগদ পার্সোনাল একাউন্ট (Nagad Personal)';
            if (brandSubText) brandSubText.className = 'text-xs text-amber-400 font-bold';
            if (copyNumBtn) copyNumBtn.className = 'px-3.5 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold transition shadow cursor-pointer shrink-0';

            if (formSummaryMethod) {
                formSummaryMethod.className = 'font-bold uppercase text-amber-400';
                formSummaryMethod.innerText = 'নগদ (Nagad)';
            }
            if (summaryMethodLabel) summaryMethodLabel.innerText = 'নগদ (Nagad Send Money)';
            if (summaryMethodText) summaryMethodText.className = 'text-sm font-bold text-amber-400 flex items-center gap-2';

        } else if (method === 'rocket') {
            tileRocket.className = 'method-tile p-5 sm:p-6 rounded-3xl border-2 border-purple-500/80 bg-purple-500/10 cursor-pointer transition-all hover:scale-[1.02] shadow-xl relative overflow-hidden group';
            tileRocket.querySelector('.method-check').className = 'method-check w-6 h-6 rounded-full bg-purple-600 text-white flex items-center justify-center text-xs font-black';
            tileRocket.querySelector('.method-check').innerText = '✓';

            if (sendMoneyBox) sendMoneyBox.className = 'p-6 sm:p-8 rounded-3xl border-2 border-purple-500/60 bg-purple-500/5 shadow-2xl space-y-6 transition-all duration-300';
            if (brandTitleText) brandTitleText.innerText = 'রকেট পার্সোনাল একাউন্ট (Rocket Personal)';
            if (brandSubText) brandSubText.className = 'text-xs text-purple-400 font-bold';
            if (copyNumBtn) copyNumBtn.className = 'px-3.5 py-2 rounded-xl bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold transition shadow cursor-pointer shrink-0';

            if (formSummaryMethod) {
                formSummaryMethod.className = 'font-bold uppercase text-purple-400';
                formSummaryMethod.innerText = 'রকেট (Rocket)';
            }
            if (summaryMethodLabel) summaryMethodLabel.innerText = 'রকেট (Rocket Send Money)';
            if (summaryMethodText) summaryMethodText.className = 'text-sm font-bold text-purple-400 flex items-center gap-2';
        }
    }

    // Copy Toast Helper
    function showToast(message) {
        const toast = document.getElementById('copyToast');
        const text = document.getElementById('copyToastText');
        text.innerText = message;
        toast.classList.remove('translate-y-[-100px]', 'opacity-0');
        toast.classList.add('translate-y-0', 'opacity-100');
        setTimeout(() => {
            toast.classList.remove('translate-y-0', 'opacity-100');
            toast.classList.add('translate-y-[-100px]', 'opacity-0');
        }, 2500);
    }

    function copyCurrentNumber() {
        const num = document.getElementById('currentMethodNumber').innerText.trim();
        navigator.clipboard.writeText(num).then(() => {
            showToast('নম্বর কপি হয়েছে: ' + num);
        }).catch(() => {
            showToast('নম্বর কপি হয়েছে ✓');
        });
    }

    function copyCurrentAmount() {
        const amt = selectedPlanPrice.toString();
        navigator.clipboard.writeText(amt).then(() => {
            showToast('টাকার পরিমাণ কপি হয়েছে: ৳ ' + amt);
        }).catch(() => {
            showToast('টাকা কপি হয়েছে ✓');
        });
    }

    // Step 4 Smart Form Behavior & Real-time Validation
    function validateSenderPhone() {
        const input = document.getElementById('sender_number_input');
        const status = document.getElementById('phoneValidationStatus');
        const error = document.getElementById('phoneErrorMessage');
        const icon = document.getElementById('phoneCheckIcon');
        const val = input.value.trim();

        const phoneRegex = /^01[3-9]\d{8}$/;
        if (phoneRegex.test(val)) {
            input.classList.remove('border-rose-500', 'border-slate-800');
            input.classList.add('border-emerald-500');
            status.innerText = '✓ সঠিক মোবাইল নম্বর';
            status.className = 'text-[11px] font-bold text-emerald-400';
            error.classList.add('hidden');
            icon.classList.remove('hidden');
            icon.innerText = '✓';
            icon.className = 'absolute right-4 top-1/2 -translate-y-1/2 text-sm text-emerald-400';
            checkFormValidity();
            return true;
        } else {
            input.classList.remove('border-emerald-500');
            if (val.length >= 3 && !val.startsWith('01')) {
                input.classList.add('border-rose-500');
                status.innerText = '০১ দিয়ে শুরু হতে হবে';
                status.className = 'text-[11px] font-bold text-rose-400';
                error.classList.remove('hidden');
            } else if (val.length === 11 && !phoneRegex.test(val)) {
                input.classList.add('border-rose-500');
                status.innerText = 'ভুল নম্বর ফরম্যাট';
                status.className = 'text-[11px] font-bold text-rose-400';
                error.classList.remove('hidden');
            } else {
                input.classList.remove('border-rose-500');
                input.classList.add('border-slate-800');
                status.innerText = (11 - val.length) + ' ডিজিট বাকি';
                status.className = 'text-[11px] font-bold text-slate-500';
                error.classList.add('hidden');
            }
            icon.classList.add('hidden');
            checkFormValidity();
            return false;
        }
    }

    function formatAndValidateTrxID() {
        const input = document.getElementById('transaction_id_input');
        const status = document.getElementById('trxValidationStatus');
        const error = document.getElementById('trxErrorMessage');
        
        // Auto uppercase and remove spaces instantly
        let val = input.value.toUpperCase().replace(/\s+/g, '');
        input.value = val;

        if (val.length >= 6) {
            input.classList.remove('border-rose-500', 'border-slate-800');
            input.classList.add('border-emerald-500');
            status.innerText = '✓ ট্রানজেকশন আইডি গৃহীত';
            status.className = 'text-[11px] font-bold text-emerald-400';
            error.classList.add('hidden');
            checkFormValidity();
            return true;
        } else {
            input.classList.remove('border-emerald-500');
            input.classList.add('border-slate-800');
            status.innerText = 'কমপক্ষে ৬ ডিজিট/অক্ষর';
            status.className = 'text-[11px] font-bold text-slate-500';
            checkFormValidity();
            return false;
        }
    }

    function pasteTrxID() {
        navigator.clipboard.readText().then(text => {
            if (text) {
                const input = document.getElementById('transaction_id_input');
                input.value = text.toUpperCase().replace(/\s+/g, '');
                formatAndValidateTrxID();
                showToast('TrxID পেস্ট করা হয়েছে ✓');
            }
        }).catch(() => {
            alert('ক্লিপবোর্ড থেকে পেস্ট করার অনুমতি দিন অথবা ম্যানুয়ালি পেস্ট করুন।');
        });
    }

    function checkFormValidity() {
        const phoneValid = /^01[3-9]\d{8}$/.test(document.getElementById('sender_number_input').value.trim());
        const trxValid = document.getElementById('transaction_id_input').value.trim().length >= 6;
        const btn = document.getElementById('submitPaymentBtn');

        if (phoneValid && trxValid) {
            btn.disabled = false;
        } else {
            btn.disabled = true;
        }
    }

    function handleFormSubmit(e) {
        if (!validateSenderPhone() || !formatAndValidateTrxID()) {
            e.preventDefault();
            alert('অনুগ্রহ করে সঠিক মোবাইল নম্বর ও TrxID প্রদান করুন।');
            return false;
        }

        const btn = document.getElementById('submitPaymentBtn');
        const spinner = document.getElementById('submitBtnSpinner');
        const text = document.getElementById('submitBtnText');

        btn.disabled = true;
        spinner.classList.remove('hidden');
        text.innerText = 'যাচাইয়ের জন্য পাঠানো হচ্ছে...';
        return true;
    }

    // Toggle Stepper vs Pending Screen
    function toggleStepperVisibility() {
        document.getElementById('stepperCheckoutSection').classList.remove('hidden');
        window.scrollTo({ top: document.getElementById('stepperCheckoutSection').offsetTop - 30, behavior: 'smooth' });
    }

    function showStepperAgain() {
        document.getElementById('rejectedNoticeContainer').classList.add('hidden');
        document.getElementById('stepperCheckoutSection').classList.remove('hidden');
        goToStep(4);
    }

    // Real-Time Polling for Status
    let pollingInterval = null;
    function startPolling() {
        if (pollingInterval) clearInterval(pollingInterval);
        pollingInterval = setInterval(() => {
            checkLiveStatus(false);
        }, 5000);
    }

    function handleSubscriptionLivePayload(data) {
        // Update Top Plan Card & Expired Banner Live
        const headerPlanLabel = document.getElementById('headerPlanLabel');
        const headerStatusWrap = document.getElementById('headerSubStatusWrapper');
        const headerExpiresAt = document.getElementById('headerExpiresAt');
        const expiredBanner = document.getElementById('subPageExpiredBanner');

        if (headerPlanLabel && data.plan_label) {
            headerPlanLabel.innerText = data.plan_label;
        }
        if (headerStatusWrap) {
            if (data.is_lifetime) {
                headerStatusWrap.innerHTML = '<span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-purple-500/20 text-purple-300 text-[10px] font-bold border border-purple-500/30">👑 আজীবন ফ্রি লাইসেন্স</span>';
                if (headerExpiresAt) headerExpiresAt.classList.add('hidden');
            } else if (data.is_active) {
                headerStatusWrap.innerHTML = `<span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span><span class="text-xs font-bold text-emerald-400">${data.time_remaining_label || 'সক্রিয়'}</span>`;
                if (headerExpiresAt) {
                    headerExpiresAt.classList.remove('hidden');
                    headerExpiresAt.innerHTML = `মেয়াদ শেষ: <span class="font-bold text-slate-300">${data.formatted_expires_at || ''}</span>`;
                }
            } else {
                headerStatusWrap.innerHTML = '<span class="w-2 h-2 rounded-full bg-rose-500"></span><span class="text-xs font-bold text-rose-400">মেয়াদ উত্তীর্ণ (Expired)</span>';
            }
        }
        if (expiredBanner && data.is_active) {
            expiredBanner.classList.add('hidden');
        }

        // Update History Table Live
        const historyTbody = document.getElementById('subHistoryTbody');
        const historyCount = document.getElementById('subHistoryCount');
        if (historyTbody && Array.isArray(data.subscriptions_history)) {
            if (historyCount) historyCount.innerText = `${data.subscriptions_history.length} টি রেকর্ড`;
            if (data.subscriptions_history.length > 0) {
                const isOffice = {{ $shop->is_office_mode ? 'true' : 'false' }};
                historyTbody.innerHTML = data.subscriptions_history.map(sub => {
                    const m = sub.payment_method;
                    const methodBadge = m === 'bkash'
                        ? '<span class="px-2 py-0.5 rounded-md bg-pink-500/15 text-pink-400 font-bold border border-pink-500/30">bKash</span>'
                        : m === 'nagad'
                        ? '<span class="px-2 py-0.5 rounded-md bg-amber-500/15 text-amber-400 font-bold border border-amber-500/30">Nagad</span>'
                        : m === 'rocket'
                        ? '<span class="px-2 py-0.5 rounded-md bg-purple-500/15 text-purple-400 font-bold border border-purple-500/30">Rocket</span>'
                        : `<span class="px-2 py-0.5 rounded-md bg-slate-800 text-slate-300 font-bold">${m}</span>`;

                    const statusBadge = sub.status === 'approved'
                        ? '<span class="px-2.5 py-1 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 font-bold text-[10px]">● অনুমোদিত (Active)</span>'
                        : sub.status === 'rejected'
                        ? '<span class="px-2.5 py-1 rounded-full bg-rose-500/10 text-rose-400 border border-rose-500/20 font-bold text-[10px]">✕ বাতিল (Rejected)</span>'
                        : '<span class="px-2.5 py-1 rounded-full bg-amber-500/10 text-amber-400 border border-amber-500/20 font-bold text-[10px] animate-pulse">⏳ পেন্ডিং (Verifying)</span>';

                    const receiptBtn = sub.status === 'approved'
                        ? `<button type="button" onclick="showInvoiceDirect('${sub.invoice_no}', '${sub.plan_name}', '${sub.amount}', '${String(m).toUpperCase()}', '${sub.transaction_id}', '${sub.sender_number}', '${sub.created_date}', '${sub.approved_date}')" class="px-2.5 py-1 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold text-[11px] transition cursor-pointer">🧾 রসিদ</button>`
                        : '<span class="text-slate-600 text-[11px]">—</span>';

                    return `<tr class="hover:bg-slate-800/40 transition-colors">
                        <td class="px-6 py-4 text-slate-300 font-mono">${sub.created_at}</td>
                        <td class="px-6 py-4 font-bold text-white">${sub.plan_name}</td>
                        <td class="px-6 py-4 font-en font-bold text-emerald-400">৳ ${sub.amount_formatted}</td>
                        <td class="px-6 py-4 uppercase font-bold">${methodBadge}</td>
                        <td class="px-6 py-4">
                            <div class="font-mono font-bold ${isOffice ? 'text-indigo-400' : 'text-rose-400'}">${sub.transaction_id}</div>
                            <div class="text-[10px] text-slate-500 font-mono">${sub.sender_number}</div>
                        </td>
                        <td class="px-6 py-4">${statusBadge}</td>
                        <td class="px-6 py-4 text-right">${receiptBtn}</td>
                    </tr>`;
                }).join('');
            }
        }

        // Update Stepper Verification Box
        if (data.status === 'approved') {
            const progressFill = document.getElementById('liveProgressFill');
            const verifyingCircle = document.getElementById('verifyingCircle');
            const verifyingLabel = document.getElementById('verifyingLabel');
            const activeCircle = document.getElementById('activeCircle');
            const activeLabel = document.getElementById('activeLabel');
            const successBox = document.getElementById('approvedSuccessContainer');

            if (progressFill) progressFill.style.width = '100%';
            if (verifyingCircle) {
                verifyingCircle.classList.remove('bg-amber-500', 'animate-pulse');
                verifyingCircle.classList.add('bg-emerald-500');
                verifyingCircle.innerText = '✓';
            }
            if (verifyingLabel) verifyingLabel.className = 'text-xs sm:text-sm font-bold text-emerald-400 mt-2';
            if (activeCircle) {
                activeCircle.classList.remove('bg-slate-800', 'text-slate-500');
                activeCircle.classList.add('bg-emerald-500', 'text-white', 'shadow-lg', 'shadow-emerald-500/40');
                activeCircle.innerText = '✓';
            }
            if (activeLabel) {
                activeLabel.className = 'text-xs sm:text-sm font-bold text-emerald-400 mt-2';
                activeLabel.innerText = 'সফলভাবে সক্রিয়!';
            }
            if (successBox) successBox.classList.remove('hidden');
            if (pollingInterval) clearInterval(pollingInterval);

        } else if (data.status === 'rejected') {
            const rejectedBox = document.getElementById('rejectedNoticeContainer');
            const reasonText = document.getElementById('rejectionReasonText');
            if (reasonText && data.rejection_reason) {
                reasonText.innerText = 'বাতিলের কারণ: ' + data.rejection_reason;
            }
            if (rejectedBox) rejectedBox.classList.remove('hidden');
            if (pollingInterval) clearInterval(pollingInterval);
        }
    }

    window.addEventListener('shop:subscription-updated', (e) => {
        if (e.detail) handleSubscriptionLivePayload(e.detail);
    });

    function checkLiveStatus(isManualClick) {
        const icon = document.getElementById('refreshIcon');
        if (icon && isManualClick) icon.classList.add('animate-spin');

        fetch('{{ route('shop.subscription.status') }}')
            .then(res => res.json())
            .then(data => {
                if (icon && isManualClick) {
                    setTimeout(() => icon.classList.remove('animate-spin'), 500);
                    showToast('স্ট্যাটাস আপডেট চেক সম্পন্ন');
                }
                handleSubscriptionLivePayload(data);
            })
            .catch(err => {
                if (icon && isManualClick) icon.classList.remove('animate-spin');
                console.error('Polling error', err);
            });
    }

    // Printable Invoice Modal Functions
    function openReceiptModal() {
        fetch('{{ route('shop.subscription.status') }}')
            .then(res => res.json())
            .then(data => {
                if (data.subscription) {
                    showInvoiceDirect(
                        data.subscription.invoice_no,
                        data.subscription.plan_name,
                        data.subscription.amount,
                        data.subscription.payment_method.toUpperCase(),
                        data.subscription.transaction_id,
                        data.subscription.sender_number,
                        data.subscription.created_at,
                        data.subscription.approved_at || data.subscription.created_at
                    );
                }
            });
    }

    function showInvoiceDirect(invoiceNo, planName, amount, method, trxId, sender, created, approved) {
        document.getElementById('modalInvoiceNo').innerText = invoiceNo;
        document.getElementById('modalPlanName').innerText = planName;
        document.getElementById('modalMethod').innerText = method;
        document.getElementById('modalTrxId').innerText = trxId;
        document.getElementById('modalSender').innerText = sender;
        document.getElementById('modalDate').innerText = approved || created;
        document.getElementById('modalAmount').innerText = '৳ ' + Number(amount).toLocaleString('en-US');

        const modal = document.getElementById('receiptModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeReceiptModal() {
        const modal = document.getElementById('receiptModal');
        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }

    // Init on DOM ready
    document.addEventListener('DOMContentLoaded', () => {
        validateSenderPhone();
        formatAndValidateTrxID();

        @if($pendingSubscription)
            startPolling();
        @endif
    });
</script>

<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #printableReceiptArea, #printableReceiptArea * {
            visibility: visible;
        }
        #printableReceiptArea {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            background: white !important;
            color: black !important;
            border: 1px solid #ddd !important;
        }
        .modal-actions {
            display: none !important;
        }
    }
</style>
@endsection