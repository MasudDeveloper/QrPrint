@extends('layouts.shop')

@section('title', 'সাবস্ক্রিপশন ও প্যাকেজ প্ল্যান – ' . $shop->name)

@section('content')
<div class="space-y-8 max-w-6xl mx-auto">
    
    <!-- Top Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-gradient-to-r from-slate-900 via-slate-950 to-slate-900 p-6 sm:p-8 rounded-3xl text-white border border-slate-800 shadow-2xl relative overflow-hidden">
        <div class="absolute -top-24 -left-24 w-72 h-72 bg-rose-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="space-y-2 z-10">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-rose-500/15 text-rose-400 text-xs font-bold border border-rose-500/30">
                <span class="w-2 h-2 rounded-full bg-rose-400 animate-pulse"></span>
                <span>দোকানদার প্যাকেজ ও লাইসেন্স পোর্টাল</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black">প্যাকেজ প্ল্যান ও বিলিং</h1>
            <p class="text-xs sm:text-sm text-slate-400">আপনার সফটওয়্যার লাইসেন্সের মেয়াদ চেক করুন এবং বিকাশ/নগদের মাধ্যমে সহজেই রিনিউ বা আপগ্রেড করুন</p>
        </div>

        <!-- Current Active Plan Card on Header -->
        <div class="p-4 rounded-2xl bg-slate-900/90 border border-slate-800 text-left sm:text-right shrink-0 z-10 space-y-1">
            <span class="text-[10px] text-slate-400 uppercase tracking-wider block font-bold">বর্তমান প্যাকেজ:</span>
            <div class="text-lg font-black text-rose-400">{{ $shop->plan_label }}</div>
            
            @if($shop->is_lifetime)
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-purple-500/20 text-purple-300 text-[10px] font-bold border border-purple-500/30">
                    👑 আজীবন ফ্রি লাইসেন্স
                </span>
            @else
                @php
                    $daysRemaining = $shop->days_remaining;
                    $isActive = $shop->isSubscriptionActive();
                @endphp
                <div class="flex items-center sm:justify-end gap-1.5 pt-1">
                    @if($isActive)
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                        <span class="text-xs font-bold text-emerald-400">মেয়াদ বাকি: {{ $daysRemaining }} দিন</span>
                    @else
                        <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                        <span class="text-xs font-bold text-rose-400">মেয়াদ উত্তীর্ণ (Expired)</span>
                    @endif
                </div>
                <div class="text-[10px] text-slate-500">
                    মেয়াদ শেষ: {{ $shop->subscription_expires_at ? $shop->subscription_expires_at->format('d M, Y') : ($shop->created_at ? $shop->created_at->addDays(30)->format('d M, Y') : 'N/A') }}
                </div>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 text-sm font-bold flex items-center gap-3">
            <span class="text-xl">✅</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(!$shop->isSubscriptionActive() && !$shop->is_lifetime)
        <div class="p-5 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-300 text-xs sm:text-sm font-bold flex items-start gap-3">
            <span class="text-2xl mt-0.5">⚠️</span>
            <div>
                <span class="font-black text-white text-base block mb-0.5">আপনার সাবস্ক্রিপশনের মেয়াদ শেষ হয়েছে!</span>
                কাস্টমার কিউ ও স্বয়ংক্রিয় প্রিন্ট সার্ভিস নির্বিঘ্ন রাখতে অনুগ্রহ করে নিচের যেকোনো একটি প্যাকেজ বেছে নিয়ে বিকাশ বা নগদে পেমেন্ট করুন।
            </div>
        </div>
    @endif

    <!-- 3 PRICING TIERS SELECTION -->
    <div class="space-y-4">
        <div>
            <h2 class="text-xl font-black text-white">প্যাকেজ বেছে নিন</h2>
            <p class="text-xs text-slate-400">যেকোনো একটি প্যাকেজে ক্লিক করে নিচে পেমেন্টের তথ্য দিয়ে রিকোয়েস্ট পাঠান</p>
        </div>

        <div class="grid md:grid-cols-3 gap-6">
            
            <!-- Plan 1: Monthly Starter -->
            <label class="relative bg-slate-900/80 p-6 rounded-3xl border-2 cursor-pointer transition-all hover:border-slate-600 block plan-card" id="card_monthly">
                <input type="radio" name="plan_choice" value="monthly_starter" class="hidden plan-radio" {{ $preselectedPlan === 'monthly_starter' ? 'checked' : '' }}>
                <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                    <span class="text-sm font-bold text-white">মান্থলি স্টার্টার</span>
                    <span class="plan-check w-5 h-5 rounded-full border-2 border-slate-600 flex items-center justify-center text-[10px] text-white"></span>
                </div>
                <div class="my-4">
                    <span class="text-3xl font-black text-white font-en">৳ ৯৯</span>
                    <span class="text-xs text-slate-400">/ ১ মাস</span>
                </div>
                <ul class="space-y-2 text-xs text-slate-300">
                    <li class="flex items-center gap-2"><span class="text-rose-500 font-bold">✓</span> আনলিমিটেড প্রিন্ট কিউ</li>
                    <li class="flex items-center gap-2"><span class="text-rose-500 font-bold">✓</span> বিকাশ ও নগদ পেমেন্ট ম্যাচিং</li>
                    <li class="flex items-center gap-2"><span class="text-rose-500 font-bold">✓</span> ২ প্রিন্টার কানেক্ট (B&W + কালার)</li>
                </ul>
            </label>

            <!-- Plan 2: Yearly Pro (Recommended) -->
            <label class="relative bg-slate-900/90 p-6 rounded-3xl border-2 cursor-pointer transition-all block plan-card relative shadow-xl shadow-rose-500/10" id="card_yearly">
                <div class="absolute -top-3 left-1/2 -translate-x-1/2 px-3 py-0.5 rounded-full bg-gradient-to-r from-rose-500 to-pink-500 text-white text-[10px] font-black uppercase tracking-wider shadow">
                    সবচেয়ে জনপ্রিয় (Best Value)
                </div>
                <input type="radio" name="plan_choice" value="yearly_pro" class="hidden plan-radio" {{ ($preselectedPlan === 'yearly_pro' || !$preselectedPlan) ? 'checked' : '' }}>
                <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                    <span class="text-sm font-bold text-white">ইয়ারলি প্রো</span>
                    <span class="plan-check w-5 h-5 rounded-full border-2 border-slate-600 flex items-center justify-center text-[10px] text-white"></span>
                </div>
                <div class="my-4">
                    <span class="text-3xl font-black text-rose-400 font-en">৳ ৯৯৯</span>
                    <span class="text-xs text-slate-400">/ ১ বছর (১২ মাস)</span>
                </div>
                <ul class="space-y-2 text-xs text-slate-300">
                    <li class="flex items-center gap-2"><span class="text-rose-500 font-bold">✓</span> আনলিমিটেড প্রিন্ট কিউ</li>
                    <li class="flex items-center gap-2"><span class="text-rose-500 font-bold">✓</span> ৪x৬ পাসপোর্ট সাইজ ফটো মেকার</li>
                    <li class="flex items-center gap-2"><span class="text-rose-500 font-bold">✓</span> ৫টি প্রিন্টার পর্যন্ত কানেকশন</li>
                    <li class="flex items-center gap-2"><span class="text-rose-500 font-bold">✓</span> ফোন ও AnyDesk প্রায়োরিটি সাপোর্ট</li>
                </ul>
            </label>

            <!-- Plan 3: Lifetime -->
            <label class="relative bg-slate-900/80 p-6 rounded-3xl border-2 cursor-pointer transition-all hover:border-slate-600 block plan-card overflow-hidden" id="card_lifetime">
                <div class="absolute top-0 right-0 px-3 py-1 bg-gradient-to-l from-purple-600 to-indigo-600 text-white text-[9px] font-black rounded-bl-xl tracking-wide uppercase">
                    ⚡ লিমিটেড অফার
                </div>
                <input type="radio" name="plan_choice" value="lifetime" class="hidden plan-radio" {{ $preselectedPlan === 'lifetime' ? 'checked' : '' }}>
                <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                    <span class="text-sm font-bold text-purple-300">👑 লাইফটাইম আনলিমিটেড</span>
                    <span class="plan-check w-5 h-5 rounded-full border-2 border-slate-600 flex items-center justify-center text-[10px] text-white"></span>
                </div>
                <div class="mt-2.5 inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-lg bg-amber-500/15 border border-amber-500/30 text-amber-300 text-[10px] font-bold">
                    <span>🔥 শুধুমাত্র প্রথম ৫০টি দোকানের জন্য</span>
                </div>
                <div class="my-4">
                    <span class="text-3xl font-black text-purple-400 font-en">৳ ৩,৯৯৯</span>
                    <span class="text-xs text-slate-400">/ এককালীন আজীবন</span>
                </div>
                <ul class="space-y-2 text-xs text-slate-300">
                    <li class="flex items-center gap-2"><span class="text-purple-400 font-bold">✓</span> কখনো কোনো রিনিউয়াল ফি নেই</li>
                    <li class="flex items-center gap-2"><span class="text-purple-400 font-bold">✓</span> আজীবন সব নতুন ফিচার ফ্রি</li>
                    <li class="flex items-center gap-2"><span class="text-purple-400 font-bold">✓</span> আনলিমিটেড প্রিন্টার ও পিসি সাপোর্ট</li>
                    <li class="flex items-center gap-2"><span class="text-purple-400 font-bold">✓</span> ভিআইপি ডাইরেক্ট ফোন সাপোর্ট</li>
                </ul>
            </label>

        </div>
    </div>

    <!-- PAYMENT & SUBMISSION BOX -->
    <div class="grid lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left 5 Cols: Payment Numbers & Instructions -->
        <div class="lg:col-span-5 bg-slate-900/80 p-6 sm:p-7 rounded-3xl border border-slate-800 shadow-xl space-y-5">
            <div>
                <h3 class="text-base font-black text-white flex items-center gap-2">
                    <span>📱 টাকা পাঠানোর নিয়ম</span>
                </h3>
                <p class="text-xs text-slate-400 mt-1">
                    নিচের সুপার এডমিনের বিকাশ বা নগদ নম্বরে আপনার নির্বাচিত প্যাকেজের টাকা "Send Money" করুন:
                </p>
            </div>

            <!-- bKash Card -->
            <div class="p-4 rounded-2xl bg-pink-500/10 border border-pink-500/20 flex items-center justify-between">
                <div class="space-y-1">
                    <span class="text-[10px] uppercase font-bold text-pink-400 block tracking-wider">বিকাশ (bKash) Send Money:</span>
                    <div class="text-lg font-black text-white font-mono tracking-wider" id="bkashNumText">{{ $adminBkash }}</div>
                </div>
                <button type="button" onclick="navigator.clipboard.writeText('{{ $adminBkash }}'); alert('বিকাশ নম্বর কপি হয়েছে: {{ $adminBkash }}');" class="px-3 py-1.5 rounded-xl bg-pink-500 hover:bg-pink-600 text-white text-xs font-bold transition-all shadow">
                    কপি করুন
                </button>
            </div>

            <!-- Nagad Card -->
            <div class="p-4 rounded-2xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-between">
                <div class="space-y-1">
                    <span class="text-[10px] uppercase font-bold text-amber-400 block tracking-wider">নগদ (Nagad) Send Money:</span>
                    <div class="text-lg font-black text-white font-mono tracking-wider" id="nagadNumText">{{ $adminNagad }}</div>
                </div>
                <button type="button" onclick="navigator.clipboard.writeText('{{ $adminNagad }}'); alert('নগদ নম্বর কপি হয়েছে: {{ $adminNagad }}');" class="px-3 py-1.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold transition-all shadow">
                    কপি করুন
                </button>
            </div>

            @if($paymentInstructions)
            <div class="p-3.5 rounded-xl bg-slate-950 border border-slate-800 text-[11px] text-slate-400">
                💡 {{ $paymentInstructions }}
            </div>
            @endif

            <div class="text-[11px] text-slate-400 flex items-center gap-2">
                <span>📞 হেল্পলাইন: <a href="tel:01644416378" class="text-rose-400 font-bold">01644416378</a></span>
                <span>•</span>
                <span>💬 <a href="https://wa.me/8801644416378" target="_blank" class="text-emerald-400 font-bold hover:underline">হোয়াটসঅ্যাপ চ্যাট</a></span>
            </div>
        </div>

        <!-- Right 7 Cols: Verification Form -->
        <div class="lg:col-span-7 bg-slate-900/80 p-6 sm:p-7 rounded-3xl border border-slate-800 shadow-xl space-y-5">
            <div>
                <h3 class="text-base font-black text-white">
                    পেমেন্ট কনফার্মেশন ফর্ম
                </h3>
                <p class="text-xs text-slate-400 mt-1">
                    টাকা পাঠানোর পর বিকাশ/নগদের Transaction ID ও আপনার মোবাইল নম্বরটি নিচে লিখে সাবমিট করুন:
                </p>
            </div>

            <form action="{{ route('shop.subscription.purchase') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="plan" id="form_plan_input" value="{{ $preselectedPlan ?: 'yearly_pro' }}">

                <!-- Selected Plan Display Indicator -->
                <div class="p-3.5 rounded-2xl bg-slate-950 border border-slate-800 flex items-center justify-between">
                    <span class="text-xs text-slate-400">নির্বাচিত প্যাকেজ:</span>
                    <span id="selectedPlanDisplay" class="text-xs font-black text-rose-400">ইয়ারলি প্রো (৳ ৯৯৯)</span>
                </div>

                <!-- Payment Method Radio -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-300">কোন মাধ্যমে টাকা পাঠিয়েছেন? <span class="text-rose-500">*</span></label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="flex items-center gap-2.5 p-3 rounded-xl bg-slate-950 border border-slate-800 cursor-pointer hover:border-pink-500 transition-colors">
                            <input type="radio" name="payment_method" value="bkash" checked class="accent-pink-500">
                            <span class="text-xs font-bold text-white">বিকাশ (bKash)</span>
                        </label>
                        <label class="flex items-center gap-2.5 p-3 rounded-xl bg-slate-950 border border-slate-800 cursor-pointer hover:border-amber-500 transition-colors">
                            <input type="radio" name="payment_method" value="nagad" class="accent-amber-500">
                            <span class="text-xs font-bold text-white">নগদ (Nagad)</span>
                        </label>
                    </div>
                </div>

                <!-- Sender Number -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-300">যে নম্বর থেকে টাকা পাঠিয়েছেন (Sender Number) <span class="text-rose-500">*</span></label>
                    <input type="text" name="sender_number" required placeholder="017xxxxxxxx" value="{{ old('sender_number', $shop->phone) }}" 
                        class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-800 text-sm font-mono text-white focus:outline-none focus:border-rose-500">
                </div>

                <!-- Transaction ID -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-300">বিকাশ / নগদ ট্রানজেকশন আইডি (TrxID) <span class="text-rose-500">*</span></label>
                    <input type="text" name="transaction_id" required placeholder="উদাঃ BKB7A89DFQ" value="{{ old('transaction_id') }}" 
                        class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-800 text-sm font-mono uppercase text-white focus:outline-none focus:border-rose-500 tracking-wider">
                </div>

                <button type="submit" class="w-full py-3.5 px-6 rounded-2xl bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white font-black text-sm shadow-xl shadow-rose-500/25 transition-all hover:scale-[1.01] flex items-center justify-center gap-2 cursor-pointer">
                    <span>🚀 অ্যাক্টিভেশনের অনুরোধ পাঠান</span>
                </button>
            </form>
        </div>

    </div>

    <!-- SUBSCRIPTION REQUEST HISTORY -->
    <div class="bg-slate-900/80 rounded-3xl border border-slate-800 shadow-xl overflow-hidden">
        <div class="p-6 border-b border-slate-800">
            <h3 class="text-base font-black text-white">পেমেন্ট ও সাবস্ক্রিপশন হিস্ট্রি</h3>
            <p class="text-xs text-slate-400 mt-0.5">আপনার পূর্বের সকল প্যাকেজ রিকোয়েস্ট ও স্ট্যাটাস</p>
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
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    @forelse($subscriptionsHistory as $sub)
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            <td class="px-6 py-4 text-slate-300 font-mono">{{ $sub->created_at->format('d M, Y h:i A') }}</td>
                            <td class="px-6 py-4 font-bold text-white">{{ $sub->plan_name }}</td>
                            <td class="px-6 py-4 font-en font-bold text-emerald-400">৳ {{ number_format($sub->amount, 0) }}</td>
                            <td class="px-6 py-4 uppercase font-bold text-slate-300">{{ $sub->payment_method }}</td>
                            <td class="px-6 py-4">
                                <div class="font-mono font-bold text-rose-400">{{ $sub->transaction_id ?? 'N/A' }}</div>
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
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-slate-500">এখনো কোনো প্যাকেজ রিকোয়েস্ট করা হয়নি</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Interactive Plan Selector Script -->
<script>
    const planInputs = document.querySelectorAll('.plan-radio');
    const formPlanInput = document.getElementById('form_plan_input');
    const selectedPlanDisplay = document.getElementById('selectedPlanDisplay');

    const planNames = {
        'monthly_starter': 'মান্থলি স্টার্টার (৳ ৯৯)',
        'yearly_pro': 'ইয়ারলি প্রো (৳ ৯৯৯)',
        'lifetime': 'লাইফটাইম আনলিমিটেড (৳ ৩,৯৯৯)'
    };

    function updateCardStyles() {
        planInputs.forEach(input => {
            const card = input.closest('.plan-card');
            const check = card.querySelector('.plan-check');
            if (input.checked) {
                card.classList.add('border-rose-500', 'bg-slate-900');
                card.classList.remove('border-slate-800', 'border-transparent');
                check.classList.add('bg-rose-500', 'border-rose-500');
                check.innerText = '✓';
                formPlanInput.value = input.value;
                selectedPlanDisplay.innerText = planNames[input.value] || input.value;
            } else {
                card.classList.remove('border-rose-500');
                card.classList.add('border-slate-800');
                check.classList.remove('bg-rose-500', 'border-rose-500');
                check.innerText = '';
            }
        });
    }

    planInputs.forEach(input => {
        input.addEventListener('change', updateCardStyles);
    });

    // Run on load
    updateCardStyles();
</script>
@endsection
