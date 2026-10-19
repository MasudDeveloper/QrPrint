<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>প্রিন্ট স্ট্যাটাস – {{ $job->job_token }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('logo.svg') }}">
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.4/dist/confetti.browser.min.js"></script>
    <style>
        body {
            font-family: 'Hind Siliguri', sans-serif;
            background-color: #f8fafc;
        }
        .font-en { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-slate-50 text-slate-800 flex flex-col justify-center items-center p-4">

    <div class="w-full max-w-md space-y-6">

        <!-- Status Card -->
        <div class="bg-white rounded-[32px] p-6 sm:p-8 border border-slate-200 shadow-xl text-center space-y-6 relative overflow-hidden">
            
            <!-- Shop info with Logo -->
            <div class="border-b border-slate-100 pb-4 flex items-center justify-between">
                <div class="flex items-center gap-2.5 text-left">
                    <img src="{{ asset('logo.svg') }}" alt="PrintSheba Logo" class="w-8 h-8 rounded-xl shadow-xs">
                    <div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">দোকানের নাম</span>
                        <h1 class="text-sm font-black text-slate-900 leading-tight">{{ $job->shop->name }}</h1>
                    </div>
                </div>
                <span class="text-[10px] font-mono px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 font-bold">
                    {{ $job->job_token }}
                </span>
            </div>

            <!-- Animated Status Icon -->
            <div id="statusIconBox" class="w-24 h-24 rounded-full mx-auto flex items-center justify-center text-4xl shadow-inner transition-all duration-300 {{ $job->print_status === 'printed' ? 'bg-emerald-100 text-emerald-600' : ($job->print_status === 'cancelled' ? 'bg-rose-100 text-rose-600' : 'bg-rose-50 text-rose-500 animate-pulse') }}">
                @if($job->print_status === 'printed')
                    ✓
                @elseif($job->print_status === 'cancelled')
                    ✕
                @elseif($job->print_status === 'spooling')
                    🖨️
                @else
                    ⏳
                @endif
            </div>

            <div class="space-y-1">
                <h2 id="statusHeadline" class="text-2xl font-black text-slate-900">
                    @if($job->print_status === 'printed')
                        প্রিন্ট সম্পন্ন হয়েছে!
                    @elseif($job->print_status === 'cancelled')
                        অর্ডারটি বাতিল করা হয়েছে
                    @elseif($job->print_status === 'spooling')
                        প্রিন্টারে পেজ বের হচ্ছে...
                    @else
                        অর্ডারের অপেক্ষা করা হচ্ছে...
                    @endif
                </h2>
                <p id="statusSubtext" class="text-xs text-slate-500">
                    @if($job->print_status === 'printed')
                        কাউন্টার থেকে আপনার প্রিন্ট করা পেজ সংগ্রহ করুন
                    @elseif($job->print_status === 'cancelled')
                        {{ $job->cancel_reason ? 'বাতিলের কারণ: ' . $job->cancel_reason : 'দোকানদার কর্তৃক এই প্রিন্ট অর্ডারটি বাতিল করা হয়েছে।' }}
                    @elseif($job->payment_status === 'paid')
                        পেমেন্ট নিশ্চিত হয়েছে, প্রিন্টার কাজ শুরু করেছে
                    @else
                        পেমেন্ট ভেরিফাই হওয়ামাত্র স্বয়ংক্রিয়ভাবে পেজ বের হবে
                    @endif
                </p>
            </div>

            <!-- Job Details Pill -->
            <div class="bg-slate-50 rounded-2xl p-4 text-left text-xs space-y-2 border border-slate-200">
                <div class="flex justify-between">
                    <span class="text-slate-500">টোকেন নম্বর:</span>
                    <span class="font-mono font-bold text-slate-900">{{ $job->job_token }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">ফাইল:</span>
                    <span class="font-medium text-slate-800 truncate max-w-[180px]">{{ $job->original_name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">মোট পেজ ও কপি:</span>
                    <span class="font-bold text-slate-800">{{ $job->total_pages }} পেজ × {{ $job->copies }} কপি</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">মোড:</span>
                    <span class="font-bold {{ $job->color_mode === 'color' ? 'text-pink-600' : 'text-slate-800' }}">
                        {{ $job->color_mode === 'color' ? 'কালার' : 'সাদাকালো' }} ({{ $job->is_duplex ? 'উভয় পাশ' : 'একপাশ' }})
                    </span>
                </div>
                @if($job->pickup_reference)
                    <div class="p-3.5 rounded-2xl bg-amber-500/10 border-2 border-amber-400 text-amber-950 text-left space-y-1 shadow-xs">
                        <div class="flex items-center justify-between text-xs font-black text-amber-900">
                            <span class="flex items-center gap-1.5">
                                <span>🏷️ খাম / প্যাকেট রেফারেন্স:</span>
                                <span class="px-2.5 py-0.5 rounded-lg bg-amber-200 text-amber-900 font-bold font-mono text-xs">{{ $job->pickup_reference }}</span>
                            </span>
                        </div>
                        <div class="text-[11px] text-amber-800 leading-tight">
                            দোকানদার এই রেফারেন্স লিখে আপনার প্রিন্ট কপি খামে সংরক্ষণ করবেন। দোকানে এটি দেখালেই আপনার খাম পেয়ে যাবেন।
                        </div>
                    </div>
                @endif

                @if($job->is_office_job)
                    <div class="p-2.5 rounded-xl bg-indigo-50 border border-indigo-200 text-indigo-900 text-xs flex items-center justify-between font-bold">
                        <span>🏢 অফিস ডিরেক্ট প্রিন্ট</span>
                        <span class="text-[10px] text-indigo-700 font-normal">কোনো পেমেন্ট ছাড়াই সরাসরি প্রিন্ট</span>
                    </div>
                @endif

                <div class="flex justify-between pt-2 border-t border-slate-200 text-sm">
                    <span class="font-bold text-slate-700">মোট প্রদেয় বিল:</span>
                    <span class="font-black text-emerald-600 font-en">৳ {{ number_format($job->total_amount, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">পেমেন্ট স্ট্যাটাস:</span>
                    <span id="paymentStatusBadge" class="font-bold uppercase text-[10px] px-2 py-0.5 rounded {{ $job->print_status === 'cancelled' ? 'bg-rose-100 text-rose-800' : ($job->payment_status === 'paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800') }}">
                        {{ $job->print_status === 'cancelled' ? 'বাতিল (CANCELLED)' : ($job->payment_status === 'paid' ? 'পরিশোধিত (PAID)' : 'অপেক্ষমান (PENDING)') }}
                    </span>
                </div>
            </div>

            <!-- Back or New Print button -->
            <div class="pt-2">
                <a href="{{ $job->is_office_job ? route('customer.office_upload', $job->shop->slug) : route('customer.upload', $job->shop->slug) }}" class="inline-block w-full py-3.5 rounded-2xl bg-gradient-to-r from-rose-500 to-rose-600 hover:from-rose-600 hover:to-rose-700 text-white font-bold text-xs shadow-lg shadow-rose-500/20 transition-all">
                    + নতুন ডকুমেন্ট প্রিন্ট করুন
                </a>
            </div>
        </div>

        <div class="text-center text-[10px] text-slate-400">
            🔒 জিরো ডাটা স্টোরেজ: প্রিন্ট শেষে আপনার ফাইল সার্ভার ও লোকাল পিসি থেকে স্থায়ীভাবে মুছে দেওয়া হয়
        </div>
    </div>

    <!-- Live Polling Script -->
    <script>
        const jobToken = '{{ $job->job_token }}';
        let isAlreadyCelebrated = {{ $job->print_status === 'printed' ? 'true' : 'false' }};
        let pollTimer = null;

        function checkStatus() {
            fetch('/api/customer/status/' + jobToken)
                .then(r => r.json())
                .then(data => {
                    const statusText = document.getElementById('statusHeadline');
                    const subText = document.getElementById('statusSubtext');
                    const iconBox = document.getElementById('statusIconBox');
                    const payBadge = document.getElementById('paymentStatusBadge');

                    if (data.print_status === 'cancelled' || data.payment_status === 'cancelled') {
                        statusText.innerText = 'অর্ডারটি বাতিল করা হয়েছে';
                        subText.innerText = data.cancel_reason ? ('বাতিলের কারণ: ' + data.cancel_reason) : 'কাউন্টার থেকে এই প্রিন্ট অর্ডারটি বাতিল করা হয়েছে';
                        iconBox.className = 'w-24 h-24 rounded-full mx-auto flex items-center justify-center text-4xl shadow-inner bg-rose-100 text-rose-600';
                        iconBox.innerText = '✕';
                        payBadge.className = 'font-bold uppercase text-[10px] px-2 py-0.5 rounded bg-rose-100 text-rose-800';
                        payBadge.innerText = 'বাতিল (CANCELLED)';
                        if (pollTimer) clearInterval(pollTimer);
                        return;
                    }

                    if (data.payment_status === 'paid') {
                        payBadge.className = 'font-bold uppercase text-[10px] px-2 py-0.5 rounded bg-emerald-100 text-emerald-800';
                        payBadge.innerText = 'পরিশোধিত (PAID)';
                    }

                    if (data.print_status === 'printed') {
                        statusText.innerText = 'প্রিন্ট সম্পন্ন হয়েছে!';
                        subText.innerText = 'কাউন্টার থেকে আপনার প্রিন্ট করা পেজ সংগ্রহ করুন';
                        iconBox.className = 'w-24 h-24 rounded-full mx-auto flex items-center justify-center text-4xl shadow-inner bg-emerald-100 text-emerald-600';
                        iconBox.innerText = '✓';

                        if (!isAlreadyCelebrated) {
                            confetti({ particleCount: 100, spread: 70, origin: { y: 0.6 } });
                            isAlreadyCelebrated = true;
                        }
                        if (pollTimer) clearInterval(pollTimer);
                    } else if (data.print_status === 'spooling') {
                        statusText.innerText = 'প্রিন্টারে পেজ বের হচ্ছে...';
                        subText.innerText = 'অনুগ্রহ করে অপেক্ষা করুন, প্রিন্ট কমান্ড পাঠানো হয়েছে';
                        iconBox.className = 'w-24 h-24 rounded-full mx-auto flex items-center justify-center text-4xl shadow-inner bg-rose-50 text-rose-500 animate-pulse';
                        iconBox.innerText = '🖨️';
                    }
                })
                .catch(err => console.error(err));
        }

        // Poll every 2.5 seconds if not printed or cancelled
        if ('{{ $job->print_status }}' !== 'printed' && '{{ $job->print_status }}' !== 'cancelled') {
            pollTimer = setInterval(checkStatus, 2500);
        }
    </script>
</body>
</html>
