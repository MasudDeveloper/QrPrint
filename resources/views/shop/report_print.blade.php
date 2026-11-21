<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>প্রিন্ট সেবা – আয় ও অডিট রিপোর্ট</title>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Hind Siliguri', sans-serif;
            background: #ffffff;
            color: #000000;
        }
        .font-en { font-family: 'Plus Jakarta Sans', sans-serif; }
        @media print {
            .no-print { display: none !important; }
            body { padding: 0 !important; }
        }
    </style>
</head>
<body class="p-6 max-w-4xl mx-auto space-y-6">

    <!-- Print Action Bar -->
    <div class="no-print flex items-center justify-between pb-4 border-b border-slate-200">
        <a href="javascript:window.history.back()" class="text-xs font-bold text-slate-600 hover:text-black">← ফিরে যান</a>
        <button onclick="window.print()" class="px-5 py-2 rounded-xl bg-slate-900 text-white font-bold text-xs shadow-md">
            🖨️ প্রিন্ট করুন (Print / Save as PDF)
        </button>
    </div>

    <!-- Header -->
    <div class="border-b-2 border-black pb-4 flex justify-between items-start">
        <div>
            <h1 class="text-2xl font-black text-black">{{ $shop->name }}</h1>
            <p class="text-xs text-slate-700">ফোন: {{ $shop->phone ?: '---' }} | হেল্পলাইন: 01644416378</p>
            <p class="text-xs text-slate-700 font-bold mt-1">রিপোর্ট: {{ $periodLabel }}</p>
        </div>
        <div class="text-right">
            <span class="text-xs font-mono font-bold block">PrintSheba Cloud Audit</span>
            <span class="text-[11px] text-slate-600">তারিখ: {{ now()->format('d/m/Y h:i A') }}</span>
        </div>
    </div>

    <!-- Financial / Office Summary Box -->
    <div class="grid grid-cols-4 gap-3 p-4 border border-black rounded-xl text-center">
        <div>
            <span class="text-[11px] font-bold text-slate-700 block">মোট জব</span>
            <span class="text-xl font-black text-black font-en">{{ $metrics['total_jobs'] }}</span>
        </div>
        <div>
            <span class="text-[11px] font-bold text-slate-700 block">{{ $shop->is_office_mode ? 'মোট পেপার শিট' : 'মোট পেজ' }}</span>
            <span class="text-xl font-black text-black font-en">{{ $metrics['total_pages_printed'] }}</span>
        </div>
        @if($shop->is_office_mode)
            <div>
                <span class="text-[11px] font-bold text-slate-700 block">সফল প্রিন্ট</span>
                <span class="text-xl font-black text-black font-en">{{ $metrics['printed_jobs'] }}</span>
            </div>
            <div>
                <span class="text-[11px] font-bold text-slate-700 block">বাতিল টাস্ক</span>
                <span class="text-xl font-black text-black font-en">{{ $metrics['cancelled_jobs'] }}</span>
            </div>
        @else
            <div>
                <span class="text-[11px] font-bold text-slate-700 block">ক্যাশ আয়</span>
                <span class="text-xl font-black text-black font-en">৳ {{ number_format($metrics['cash_revenue'], 2) }}</span>
            </div>
            <div>
                <span class="text-[11px] font-bold text-slate-700 block">মোট আয় (রেভিনিউ)</span>
                <span class="text-xl font-black text-black font-en">৳ {{ number_format($metrics['total_revenue'], 2) }}</span>
            </div>
        @endif
    </div>

    <!-- Table -->
    <div>
        <table class="w-full text-left text-xs border-collapse">
            <thead>
                <tr class="border-b border-black text-[10px] uppercase font-bold text-slate-800">
                    <th class="py-2">টোকেন নং</th>
                    <th class="py-2">সময়</th>
                    <th class="py-2">{{ $shop->is_office_mode ? 'কর্মকর্তা' : 'গ্রাহক / রেফারেন্স' }}</th>
                    <th class="py-2">ফাইল নাম</th>
                    <th class="py-2">পেজ × কপি</th>
                    <th class="py-2">মোড</th>
                    @if($shop->is_office_mode)
                        <th class="py-2">রেফারেন্স / পিসি</th>
                        <th class="py-2 text-right">পেপার সাইজ</th>
                    @else
                        <th class="py-2">পেমেন্ট</th>
                        <th class="py-2 text-right">টাকা (৳)</th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-300">
                @foreach($jobs as $job)
                    <tr>
                        <td class="py-2 font-mono font-bold">{{ $job->job_token }}</td>
                        <td class="py-2 text-slate-700 font-mono">{{ $job->created_at->format('h:i A') }}</td>
                        <td class="py-2 font-bold">{{ $shop->is_office_mode ? ($job->customer_name ?: 'অফিস কর্মী') : ($job->pickup_reference ?: ($job->customer_name ?: 'কাস্টমার')) }}</td>
                        <td class="py-2 max-w-[140px] truncate">{{ $job->original_name }}</td>
                        <td class="py-2 font-mono">{{ $job->total_pages }} × {{ $job->copies }}</td>
                        <td class="py-2">{{ $job->color_mode === 'color' ? 'কালার' : ($job->color_mode === 'photocopy' ? 'ফটোকপি' : 'সাদাকালো') }}</td>
                        @if($shop->is_office_mode)
                            <td class="py-2 font-mono">{{ $job->pickup_reference ?: '-' }}</td>
                            <td class="py-2 text-right font-mono">{{ $job->paper_size ?: 'A4' }}</td>
                        @else
                            <td class="py-2 uppercase font-mono">{{ $job->payment_method }} {{ $job->payment_trx_id ? '(' . $job->payment_trx_id . ')' : '' }}</td>
                            <td class="py-2 text-right font-en font-bold">৳ {{ number_format($job->total_amount, 2) }}</td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Signature Area -->
    <div class="pt-12 flex justify-between items-end text-xs text-slate-700">
        <div>
            <div class="w-48 border-t border-black mb-1"></div>
            <span>প্রস্তুতকারীর স্বাক্ষর</span>
        </div>
        <div class="text-right">
            <div class="w-48 border-t border-black mb-1 ml-auto"></div>
            <span>{{ $shop->is_office_mode ? 'বিভাগীয় প্রধান / অ্যাডমিন স্বাক্ষর' : 'দোকানদারের অনুমোদন স্বাক্ষর' }}</span>
        </div>
    </div>

</body>
</html>

