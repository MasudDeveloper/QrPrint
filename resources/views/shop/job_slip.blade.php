<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>খামের স্লিপ – {{ $job->job_token }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;600;700&family=Plus+Jakarta+Sans:wght@700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Hind Siliguri', sans-serif;
            background-color: #f1f5f9;
        }
        .font-en { font-family: 'Plus Jakarta Sans', sans-serif; }
        @media print {
            body {
                background: #ffffff !important;
                padding: 0 !important;
            }
            .no-print {
                display: none !important;
            }
            .slip-card {
                box-shadow: none !important;
                border: 1px dashed #000000 !important;
                margin: 0 !important;
                max-width: 100% !important;
            }
        }
    </style>
</head>
<body class="min-h-screen flex flex-col items-center justify-center p-4">

    <!-- Action Bar -->
    <div class="w-full max-w-sm mb-4 flex items-center justify-between no-print">
        <a href="{{ route('shop.dashboard') }}" class="text-xs font-bold text-slate-600 hover:text-slate-900 flex items-center gap-1">
            ← ড্যাশবোর্ডে ফিরুন
        </a>
        <button onclick="window.print()" class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs flex items-center gap-1.5 shadow-md cursor-pointer">
            <span>🖨️ স্লিপ প্রিন্ট করুন</span>
        </button>
    </div>

    <!-- Printable Slip Card (Optimal for 80mm thermal or cut-out) -->
    <div class="slip-card w-full max-w-sm bg-white rounded-3xl p-6 border-2 border-slate-900 shadow-xl space-y-4 text-slate-900">
        
        <!-- Header -->
        <div class="text-center border-b-2 border-dashed border-slate-300 pb-3 space-y-0.5">
            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">ডেলিভারি খাম / প্যাকেট স্লিপ</span>
            <h1 class="text-base font-black text-slate-900 leading-tight">{{ $shop->name }}</h1>
            @if($shop->phone)
                <span class="text-[11px] font-mono text-slate-600 block">{{ $shop->phone }}</span>
            @endif
        </div>

        <!-- BIG HIGHLIGHT: ENVELOPE REFERENCE / CODE -->
        <div class="p-4 rounded-2xl bg-amber-100 border-2 border-amber-500 text-center space-y-1">
            <span class="text-[10px] font-bold text-amber-800 uppercase tracking-wider block">খাম / ডেলিভারি রেফারেন্স</span>
            <div class="text-xl sm:text-2xl font-black text-amber-950 font-mono tracking-wide break-words">
                {{ $job->pickup_reference ?: $job->job_token }}
            </div>
            @if($job->pickup_reference)
                <span class="text-[10px] font-mono text-slate-500 block">টোকেন: {{ $job->job_token }}</span>
            @endif
        </div>

        <!-- Job Details -->
        <div class="space-y-2 text-xs divide-y divide-slate-100">
            <div class="flex justify-between pt-1">
                <span class="text-slate-500">গ্রাহক / কর্মকর্তা:</span>
                <span class="font-bold text-slate-900">{{ $job->customer_name }}</span>
            </div>

            @if($job->customer_phone)
            <div class="flex justify-between pt-1">
                <span class="text-slate-500">ফোন নম্বর:</span>
                <span class="font-bold font-mono text-slate-900">{{ $job->customer_phone }}</span>
            </div>
            @endif

            <div class="flex justify-between pt-1">
                <span class="text-slate-500">ফাইল নাম:</span>
                <span class="font-medium text-slate-800 truncate max-w-[170px]" title="{{ $job->original_name }}">
                    {{ $job->original_name }}
                </span>
            </div>

            <div class="flex justify-between pt-1">
                <span class="text-slate-500">প্রিন্ট কপি:</span>
                <span class="font-bold text-slate-900">
                    {{ $job->total_pages }} পেজ × {{ $job->copies }} কপি
                </span>
            </div>

            <div class="flex justify-between pt-1">
                <span class="text-slate-500">প্রিন্ট মোড:</span>
                <span class="font-bold {{ $job->color_mode === 'color' ? 'text-pink-600' : 'text-slate-900' }}">
                    {{ $job->color_mode === 'color' ? 'রঙিন (Color)' : 'সাদাকালো' }} 
                    ({{ $job->is_duplex ? 'উভয় পাশ' : 'একপাশ' }})
                </span>
            </div>

            <div class="flex justify-between pt-1 text-sm">
                <span class="font-bold text-slate-700">মোট বিল:</span>
                <span class="font-black text-emerald-600 font-en">
                    @if($job->is_office_job)
                        ফ্রি (অফিস)
                    @else
                        ৳ {{ number_format($job->total_amount, 2) }}
                    @endif
                </span>
            </div>

            <div class="flex justify-between pt-1">
                <span class="text-slate-500">পেমেন্ট স্ট্যাটাস:</span>
                <span class="font-bold uppercase text-[10px] px-2 py-0.5 rounded {{ $job->payment_status === 'paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                    {{ $job->payment_status === 'paid' ? 'পরিশোধিত (PAID)' : 'ক্যাশ বাকি / পেন্ডিং' }}
                </span>
            </div>

            <div class="flex justify-between pt-1 text-[10px] text-slate-400 font-mono">
                <span>অর্ডারের সময়:</span>
                <span>{{ $job->created_at->format('d M, h:i A') }}</span>
            </div>
        </div>

        <!-- Cutting Guide Border -->
        <div class="border-t-2 border-dashed border-slate-300 pt-3 text-center">
            <span class="text-[10px] text-slate-500 block">
                ✂️ স্লিপটি কেটে খামের ওপর লাগিয়ে রাখুন অথবা রেফারেন্স নম্বরটি খামে লিখুন
            </span>
        </div>
    </div>

</body>
</html>
