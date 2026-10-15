@extends('layouts.admin')

@section('title', 'দোকানসমূহ – সুপার এডমিন')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-white flex items-center gap-2">
                <span>🏪 নিবন্ধিত সকল সাইবার ক্যাফে ও দোকান</span>
            </h1>
            <p class="text-xs sm:text-sm text-slate-400">সারা দেশের সকল দোকানের তথ্য, প্যাকেজের মেয়াদ, প্রিন্টার নিয়ন্ত্রণ ও সরাসরি দোকানে প্রবেশ করুন</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.subscriptions') }}" class="px-4 py-2.5 rounded-2xl bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white font-bold text-xs shadow-lg transition-all flex items-center gap-2">
                <span>💎 সাবস্ক্রিপশন ও পেমেন্ট কিউ</span>
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 text-xs sm:text-sm font-bold flex items-center gap-3">
            <span class="text-xl">✅</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-slate-900/80 rounded-3xl border border-slate-800 shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-950/70 text-slate-400 font-bold border-b border-slate-800 uppercase text-[10px] tracking-wider">
                    <tr>
                        <th class="px-6 py-4">দোকানের নাম ও আইডি</th>
                        <th class="px-6 py-4">মালিক ও ফোন</th>
                        <th class="px-6 py-4">প্যাকেজ ও মেয়াদ</th>
                        <th class="px-6 py-4">প্রিন্টার</th>
                        <th class="px-6 py-4">মোট প্রিন্ট</th>
                        <th class="px-6 py-4">স্ট্যাটাস</th>
                        <th class="px-6 py-4 text-right">অ্যাকশন ও নিয়ন্ত্রণ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    @forelse($shops as $shop)
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            <!-- Shop Name & Direct Link -->
                            <td class="px-6 py-4 font-bold text-white">
                                <div class="text-sm font-black">{{ $shop->name }}</div>
                                <a href="{{ route('customer.upload', $shop->slug) }}" target="_blank" class="text-rose-400 hover:underline font-mono text-[10px] flex items-center gap-1 mt-0.5">
                                    <span>/print/{{ $shop->slug }}</span>
                                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                </a>
                            </td>

                            <!-- Owner & Phone -->
                            <td class="px-6 py-4">
                                <div class="font-medium text-slate-300">{{ $shop->user->name ?? 'N/A' }}</div>
                                <div class="text-[11px] text-slate-400 font-mono">{{ $shop->phone ?? $shop->user->phone }}</div>
                            </td>

                            <!-- Package & Expiry -->
                            <td class="px-6 py-4">
                                <div class="font-bold text-rose-400">{{ $shop->plan_label }}</div>
                                @if($shop->is_lifetime)
                                    <span class="inline-block mt-1 px-2 py-0.5 rounded text-[9px] font-black bg-purple-500/20 text-purple-300 border border-purple-500/30">
                                        👑 LIFETIME
                                    </span>
                                @else
                                    @php
                                        $days = $shop->days_remaining;
                                        $isExp = !$shop->isSubscriptionActive();
                                    @endphp
                                    <div class="text-[10px] {{ $isExp ? 'text-rose-400 font-bold' : 'text-emerald-400 font-semibold' }} mt-0.5">
                                        {{ $isExp ? 'মেয়াদ শেষ' : "বাকি: {$days} দিন" }}
                                    </div>
                                    <div class="text-[9px] text-slate-500 font-mono">
                                        {{ $shop->subscription_expires_at ? $shop->subscription_expires_at->format('d M, Y') : ($shop->created_at ? $shop->created_at->addDays(30)->format('d M, Y') : 'N/A') }}
                                    </div>
                                @endif
                            </td>

                            <!-- Printers -->
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-full bg-slate-800 text-slate-300 font-bold text-[11px]">
                                    {{ $shop->printers->count() }} টি
                                </span>
                            </td>

                            <!-- Print count -->
                            <td class="px-6 py-4 font-en font-bold text-emerald-400">
                                {{ $shop->print_jobs_count }}
                            </td>

                            <!-- Active / Suspended -->
                            <td class="px-6 py-4">
                                @if($shop->is_active)
                                    <span class="px-2.5 py-1 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 font-bold text-[10px]">
                                        ● সক্রিয় (Active)
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full bg-rose-500/10 text-rose-400 border border-rose-500/20 font-bold text-[10px]">
                                        ● নিষ্ক্রিয় (Suspended)
                                    </span>
                                @endif
                            </td>

                            <!-- Action Buttons -->
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <!-- 1. Login as Shop (Impersonate) -->
                                    <a href="{{ route('admin.shops.login_as', $shop) }}" 
                                       title="দোকানদারের ড্যাশবোর্ডে প্রবেশ করুন"
                                       class="px-2.5 py-1.5 rounded-xl text-xs font-bold bg-indigo-500/15 text-indigo-300 hover:bg-indigo-500 hover:text-white border border-indigo-500/30 transition-all flex items-center gap-1">
                                        <span>🔑 প্রবেশ</span>
                                    </a>

                                    <!-- 2. Manage Package & Validity (Open Modal) -->
                                    <button type="button" 
                                            onclick="openPlanModal('{{ $shop->id }}', '{{ addslashes($shop->name) }}', '{{ $shop->subscription_plan }}', {{ $shop->is_lifetime ? 'true' : 'false' }})" 
                                            class="px-2.5 py-1.5 rounded-xl text-xs font-bold bg-purple-500/15 text-purple-300 hover:bg-purple-500 hover:text-white border border-purple-500/30 transition-all flex items-center gap-1 cursor-pointer">
                                        <span>🎁 মেয়াদ দিন</span>
                                    </button>

                                    <!-- 3. Toggle Status (Active / Suspended) -->
                                    <form action="{{ route('admin.shops.toggle', $shop) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-2.5 py-1.5 rounded-xl text-xs font-bold transition-colors cursor-pointer {{ $shop->is_active ? 'bg-rose-500/10 text-rose-400 hover:bg-rose-500/20 border border-rose-500/30' : 'bg-emerald-500/10 text-emerald-400 hover:bg-emerald-500/20 border border-emerald-500/30' }}">
                                            {{ $shop->is_active ? 'বন্ধ' : 'চালু' }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-slate-500">কোনো দোকান পাওয়া যায়নি</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Plan Extension Modal -->
<div id="planModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl max-w-md w-full p-6 sm:p-7 space-y-5 shadow-2xl relative">
        <div class="flex items-center justify-between pb-3 border-b border-slate-800">
            <div>
                <h3 class="text-base font-black text-white" id="modalShopName">প্যাকেজ ও মেয়াদ প্রদান</h3>
                <span class="text-[11px] text-slate-400">দোকানদারকে উপহার বা নির্দিষ্ট দিনের মেয়াদ দিন</span>
            </div>
            <button onclick="closePlanModal()" class="text-slate-400 hover:text-white text-2xl font-bold leading-none">&times;</button>
        </div>

        <form id="planModalForm" action="" method="POST" class="space-y-4">
            @csrf

            <!-- Action Type Selection -->
            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-slate-300">কী করতে চান?</label>
                <select name="action_type" id="modalActionType" onchange="toggleActionFields()" class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-xs font-bold text-white focus:outline-none focus:border-rose-500">
                    <option value="add_days">➕ অতিরিক্ত মেয়াদ (দিন) যোগ করুন</option>
                    <option value="set_plan">📦 সরাসরি প্যাকেজ নির্ধারণ করুন</option>
                    <option value="make_lifetime">👑 আজীবন ফ্রি লাইসেন্স দিন (Lifetime Free)</option>
                </select>
            </div>

            <!-- Days input field -->
            <div id="field_days" class="space-y-1.5">
                <label class="block text-xs font-bold text-slate-300">কত দিন যোগ করবেন?</label>
                <div class="grid grid-cols-4 gap-2 mb-2">
                    <button type="button" onclick="document.getElementById('daysInput').value=7" class="py-1 px-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-xs font-bold text-slate-300">+৭ দিন</button>
                    <button type="button" onclick="document.getElementById('daysInput').value=30" class="py-1 px-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-xs font-bold text-slate-300">+১ মাস</button>
                    <button type="button" onclick="document.getElementById('daysInput').value=90" class="py-1 px-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-xs font-bold text-slate-300">+৩ মাস</button>
                    <button type="button" onclick="document.getElementById('daysInput').value=365" class="py-1 px-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-xs font-bold text-slate-300">+১ বছর</button>
                </div>
                <input type="number" name="days" id="daysInput" value="30" min="1" max="3650" class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-sm font-mono text-white focus:outline-none focus:border-rose-500">
            </div>

            <!-- Set plan field -->
            <div id="field_plan" class="space-y-1.5 hidden">
                <label class="block text-xs font-bold text-slate-300">প্যাকেজ নির্বাচন করুন:</label>
                <select name="plan" class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-xs text-white focus:outline-none focus:border-rose-500">
                    <option value="monthly_starter">মান্থলি স্টার্টার (+৩০ দিন)</option>
                    <option value="yearly_pro">ইয়ারলি প্রো (+৩৬৫ দিন)</option>
                </select>
            </div>

            <div class="pt-2 flex items-center justify-end gap-3">
                <button type="button" onclick="closePlanModal()" class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-bold">
                    বাতিল
                </button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white text-xs font-black shadow-lg">
                    💾 আপডেট নিশ্চিত করুন
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openPlanModal(shopId, shopName, currentPlan, isLifetime) {
        document.getElementById('modalShopName').innerText = shopName + ' – প্যাকেজ ও মেয়াদ';
        document.getElementById('planModalForm').action = '/admin/shops/' + shopId + '/plan';
        document.getElementById('planModal').classList.remove('hidden');
    }

    function closePlanModal() {
        document.getElementById('planModal').classList.add('hidden');
    }

    function toggleActionFields() {
        const action = document.getElementById('modalActionType').value;
        const fieldDays = document.getElementById('field_days');
        const fieldPlan = document.getElementById('field_plan');

        if (action === 'add_days') {
            fieldDays.classList.remove('hidden');
            fieldPlan.classList.add('hidden');
        } else if (action === 'set_plan') {
            fieldDays.classList.add('hidden');
            fieldPlan.classList.remove('hidden');
        } else {
            fieldDays.classList.add('hidden');
            fieldPlan.classList.add('hidden');
        }
    }
</script>
@endsection
