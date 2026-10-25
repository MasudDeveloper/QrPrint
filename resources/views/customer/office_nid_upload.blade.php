<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $shop->name }} – অফিস স্মার্ট এনআইডি প্রিন্ট</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('logo.svg') }}">
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Hind Siliguri', sans-serif;
            background-color: #f8fafc;
            -webkit-tap-highlight-color: transparent;
        }
        .font-en { font-family: 'Plus Jakarta Sans', sans-serif; }
        .a4-mockup {
            aspect-ratio: 1 / 1.414;
            background: #ffffff;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        }
        .card-ratio {
            aspect-ratio: 85.6 / 54;
        }
    </style>
</head>
<body class="min-h-screen bg-slate-50 text-slate-800 pb-20">

    @php
        $isAgentOnline = \App\Models\Printer::where('shop_id', $shop->id)
            ->where('last_heartbeat_at', '>=', now()->subMinutes(3))
            ->exists();
    @endphp

    <!-- Header -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-30 shadow-xs">
        <div class="max-w-md mx-auto px-4 h-16 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-bold text-lg shadow-sm">
                    🏢
                </div>
                <div>
                    <h1 class="text-sm font-black text-slate-900 leading-tight truncate max-w-[190px]">{{ $shop->name }}</h1>
                    @if($isAgentOnline)
                        <span class="text-[10px] text-emerald-600 font-bold flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            অফিস প্রিন্টার প্রস্তুত
                        </span>
                    @else
                        <span class="text-[10px] text-amber-600 font-bold flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                            প্রিন্ট সার্ভার স্ট্যান্ডবাই
                        </span>
                    @endif
                </div>
            </div>
            <a href="{{ route('customer.office_upload', $shop->slug) }}" class="text-[11px] px-3 py-1 rounded-xl bg-slate-100 hover:bg-slate-200 font-bold text-slate-700 border border-slate-200 transition-all flex items-center gap-1">
                <span>📄 ডকুমেন্ট</span>
            </a>
        </div>
    </header>

    <div class="max-w-md mx-auto px-4 pt-4 space-y-4">

        <!-- Navigation Tabs -->
        <div class="grid grid-cols-2 p-1 bg-slate-200/80 rounded-2xl gap-1 text-xs font-bold text-center">
            <a href="{{ route('customer.office_upload', $shop->slug) }}" class="py-2.5 rounded-xl text-slate-600 hover:text-slate-900 transition-all flex items-center justify-center gap-1.5">
                <span>📄 ডকুমেন্ট প্রিন্ট</span>
            </a>
            <div class="py-2.5 rounded-xl bg-white text-indigo-900 shadow-sm flex items-center justify-center gap-1.5 font-black border border-indigo-200">
                <span>🪪 এনআইডি প্রিন্ট (AI)</span>
                <span class="px-1.5 py-0.2 rounded-md bg-indigo-100 text-[9px] text-indigo-800">স্মার্ট</span>
            </div>
        </div>

        @if(session('error_alert'))
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-300 text-rose-900 text-xs flex items-start gap-3 shadow-sm">
                <span class="text-xl leading-none">⚠️</span>
                <div class="space-y-1">
                    <strong class="font-bold text-sm text-rose-800 block">সতর্কতা</strong>
                    <span class="text-xs text-rose-700 leading-relaxed">{{ session('error_alert') }}</span>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-300 text-rose-900 text-xs flex items-start gap-3 shadow-sm">
                <span class="text-xl leading-none">⚠️</span>
                <div class="space-y-1">
                    <strong class="font-bold text-sm text-rose-800 block">ফর্ম পূরণে ত্রুটি হয়েছে:</strong>
                    <ul class="list-disc list-inside space-y-0.5 text-xs text-rose-700">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <!-- Banner -->
        <div class="p-4 rounded-3xl bg-gradient-to-r from-indigo-950 via-slate-900 to-indigo-900 text-white shadow-lg space-y-1.5 border border-indigo-800/40 relative overflow-hidden">
            <div class="flex items-center gap-2">
                <span class="px-2 py-0.5 rounded-md bg-indigo-500/20 text-indigo-300 text-[10px] font-bold border border-indigo-500/30">অফিস স্মার্ট এনআইডি</span>
                <span class="text-[10px] text-emerald-400 font-bold">জিরো পেমেন্ট • ১:১ স্কেল</span>
            </div>
            <h2 class="text-base font-black">অফিস এনআইডি স্মার্ট ক্রপ ও প্রিন্ট</h2>
            <p class="text-[11px] text-slate-300 leading-relaxed">
                মোবাইলে সামনের ও পেছনের ছবি দিন। এআই সোজা করে অফিসিয়াল এ৪ পেজে রিয়েল সাইজে সাজিয়ে সরাসরি সেন্ট্রাল প্রিন্টারে পাঠাবে।
            </p>
        </div>

        <form action="{{ route('customer.office_nid_submit', $shop->slug) }}" method="POST" enctype="multipart/form-data" id="officeNidForm" class="space-y-4">
            @csrf

            <input type="hidden" name="front_cropped_data" id="frontCroppedData">
            <input type="hidden" name="back_cropped_data" id="backCroppedData">

            <!-- STEP 1: FRONT SIDE -->
            <div class="bg-white p-4 rounded-3xl border border-slate-200 shadow-sm space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-1.5">
                        <span class="w-5 h-5 rounded-full bg-indigo-600 text-white text-[11px] font-bold flex items-center justify-center">১</span>
                        <span>সামনের দিক (Front Part)</span>
                    </span>
                    <span id="frontStatusBadge" class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-slate-100 text-slate-500">
                        ছবি দিন
                    </span>
                </div>

                <div id="frontUploadBox" class="border-2 border-dashed border-slate-300 hover:border-indigo-500 rounded-2xl p-5 text-center transition-all bg-slate-50/50 relative overflow-hidden">
                    <input type="file" name="front_file" id="frontFileInput" accept="image/*" class="hidden">
                    <div class="space-y-3">
                        <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto text-xl shadow-xs">
                            🪪
                        </div>
                        <div>
                            <div class="text-xs font-bold text-slate-800">এনআইডি কার্ডের সামনের অংশের ছবি</div>
                            <div class="text-[10px] text-slate-400 mt-0.5">টেবিল বা মেঝের ওপর রেখে ছবি তুলুন অথবা সরাসরি <kbd class="px-1 py-0.5 rounded bg-slate-200 text-slate-700 font-mono text-[9px]">Ctrl+V</kbd> দিয়ে পেস্ট করুন</div>
                        </div>
                        <div class="flex items-center justify-center gap-2 pt-1 flex-wrap">
                            <button type="button" onclick="triggerFileInput('front', true)" class="px-3 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-sm transition-all flex items-center gap-1.5 cursor-pointer">
                                <span>📸 ক্যামেরা</span>
                            </button>
                            <button type="button" onclick="triggerFileInput('front', false)" class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold border border-slate-200 transition-all flex items-center gap-1.5 cursor-pointer">
                                <span>📁 ফাইল</span>
                            </button>
                            <button type="button" onclick="pasteFromClipboard('front')" class="px-3 py-2 rounded-xl bg-purple-50 hover:bg-purple-100 text-purple-700 text-xs font-bold border border-purple-200 transition-all flex items-center gap-1.5 cursor-pointer" title="কপি করা ছবি পেস্ট করুন">
                                <span>📋 পেস্ট (Ctrl+V)</span>
                            </button>
                        </div>
                    </div>
                    <!-- Front AI Scanning Overlay -->
                    <div id="frontAiLoading" class="hidden absolute inset-0 bg-white/95 rounded-2xl flex flex-col items-center justify-center p-4 z-20 backdrop-blur-xs">
                        <div class="w-10 h-10 border-3 border-indigo-600 border-t-transparent rounded-full animate-spin mb-2.5"></div>
                        <span class="text-xs font-bold text-indigo-900">🤖 AI কার্ড ডিটেক্ট ও সোজা করছে...</span>
                        <span class="text-[10px] text-slate-500 mt-1">দয়া করে কয়েক সেকেন্ড অপেক্ষা করুন</span>
                    </div>
                </div>

                <div id="frontPreviewBox" class="hidden space-y-2">
                    <div class="relative rounded-xl overflow-hidden border border-slate-200 bg-slate-100 shadow-inner">
                        <canvas id="frontCanvas" class="w-full card-ratio object-cover block"></canvas>
                        <div class="absolute top-2 right-2">
                            <span class="px-2 py-0.5 rounded-md bg-emerald-600 text-white text-[10px] font-bold shadow-xs">
                                ✓ ক্রপ রেডি
                            </span>
                        </div>
                    </div>
                    <!-- Filter Toggle Toolbar -->
                    <div class="flex items-center gap-1 p-1 bg-slate-100 rounded-xl">
                        <button type="button" onclick="setSideFilter('front', 'clean_bw')" id="frontFilter_clean_bw" class="flex-1 py-1 px-1 rounded-lg text-[10px] font-bold bg-white text-indigo-800 shadow-xs border border-indigo-300">
                            📄 ক্লিন সাদা (CamScanner)
                        </button>
                        <button type="button" onclick="setSideFilter('front', 'magic_color')" id="frontFilter_magic_color" class="flex-1 py-1 px-1 rounded-lg text-[10px] font-bold text-slate-600 hover:text-slate-900">
                            🎨 ম্যাজিক কালার
                        </button>
                        <button type="button" onclick="setSideFilter('front', 'original')" id="frontFilter_original" class="flex-1 py-1 px-1 rounded-lg text-[10px] font-bold text-slate-600 hover:text-slate-900">
                            🖼️ অরিজিনাল
                        </button>
                    </div>

                    <div class="flex items-center justify-between gap-2 pt-1">
                        <button type="button" onclick="rotateCard('front')" class="px-2.5 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-[11px] font-bold border border-slate-200 flex items-center gap-1 cursor-pointer">
                            <span>🔄 ঘোরান</span>
                        </button>
                        <button type="button" onclick="openCropperModal('front')" class="px-2.5 py-1.5 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-800 text-[11px] font-bold border border-indigo-200 flex items-center gap-1 cursor-pointer">
                            <span>✏️ ক্রপ অ্যাডজাস্ট</span>
                        </button>
                        <button type="button" onclick="resetSide('front')" class="px-2.5 py-1.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 text-[11px] font-bold border border-rose-200 flex items-center gap-1 cursor-pointer">
                            <span>✕ পরিবর্তন</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- STEP 2: BACK SIDE -->
            <div class="bg-white p-4 rounded-3xl border border-slate-200 shadow-sm space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-1.5">
                        <span class="w-5 h-5 rounded-full bg-blue-600 text-white text-[11px] font-bold flex items-center justify-center">২</span>
                        <span>পেছনের দিক (Back Part)</span>
                    </span>
                    <span id="backStatusBadge" class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-slate-100 text-slate-500">
                        ছবি দিন
                    </span>
                </div>

                <div id="backUploadBox" class="border-2 border-dashed border-slate-300 hover:border-blue-500 rounded-2xl p-5 text-center transition-all bg-slate-50/50 relative overflow-hidden">
                    <input type="file" name="back_file" id="backFileInput" accept="image/*" class="hidden">
                    <div class="space-y-3">
                        <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto text-xl shadow-xs">
                            🪪
                        </div>
                        <div>
                            <div class="text-xs font-bold text-slate-800">এনআইডি কার্ডের পেছনের অংশের ছবি</div>
                            <div class="text-[10px] text-slate-400 mt-0.5">ঠিকানা ও বারকোড পরিষ্কার রাখুন বা সরাসরি <kbd class="px-1 py-0.5 rounded bg-slate-200 text-slate-700 font-mono text-[9px]">Ctrl+V</kbd> দিয়ে পেস্ট করুন</div>
                        </div>
                        <div class="flex items-center justify-center gap-2 pt-1 flex-wrap">
                            <button type="button" onclick="triggerFileInput('back', true)" class="px-3 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-sm transition-all flex items-center gap-1.5 cursor-pointer">
                                <span>📸 ক্যামেরা</span>
                            </button>
                            <button type="button" onclick="triggerFileInput('back', false)" class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold border border-slate-200 transition-all flex items-center gap-1.5 cursor-pointer">
                                <span>📁 ফাইল</span>
                            </button>
                            <button type="button" onclick="pasteFromClipboard('back')" class="px-3 py-2 rounded-xl bg-purple-50 hover:bg-purple-100 text-purple-700 text-xs font-bold border border-purple-200 transition-all flex items-center gap-1.5 cursor-pointer" title="কপি করা ছবি পেস্ট করুন">
                                <span>📋 পেস্ট (Ctrl+V)</span>
                            </button>
                        </div>
                    </div>
                    <!-- Back AI Scanning Overlay -->
                    <div id="backAiLoading" class="hidden absolute inset-0 bg-white/95 rounded-2xl flex flex-col items-center justify-center p-4 z-20 backdrop-blur-xs">
                        <div class="w-10 h-10 border-3 border-blue-600 border-t-transparent rounded-full animate-spin mb-2.5"></div>
                        <span class="text-xs font-bold text-blue-900">🤖 AI কার্ড ডিটেক্ট ও সোজা করছে...</span>
                        <span class="text-[10px] text-slate-500 mt-1">দয়া করে কয়েক সেকেন্ড অপেক্ষা করুন</span>
                    </div>
                </div>

                <div id="backPreviewBox" class="hidden space-y-2">
                    <div class="relative rounded-xl overflow-hidden border border-slate-200 bg-slate-100 shadow-inner">
                        <canvas id="backCanvas" class="w-full card-ratio object-cover block"></canvas>
                        <div class="absolute top-2 right-2">
                            <span class="px-2 py-0.5 rounded-md bg-emerald-600 text-white text-[10px] font-bold shadow-xs">
                                ✓ ক্রপ রেডি
                            </span>
                        </div>
                    </div>
                    <!-- Filter Toggle Toolbar -->
                    <div class="flex items-center gap-1 p-1 bg-slate-100 rounded-xl">
                        <button type="button" onclick="setSideFilter('back', 'clean_bw')" id="backFilter_clean_bw" class="flex-1 py-1 px-1 rounded-lg text-[10px] font-bold bg-white text-blue-800 shadow-xs border border-blue-300">
                            📄 ক্লিন সাদা (CamScanner)
                        </button>
                        <button type="button" onclick="setSideFilter('back', 'magic_color')" id="backFilter_magic_color" class="flex-1 py-1 px-1 rounded-lg text-[10px] font-bold text-slate-600 hover:text-slate-900">
                            🎨 ম্যাজিক কালার
                        </button>
                        <button type="button" onclick="setSideFilter('back', 'original')" id="backFilter_original" class="flex-1 py-1 px-1 rounded-lg text-[10px] font-bold text-slate-600 hover:text-slate-900">
                            🖼️ অরিজিনাল
                        </button>
                    </div>

                    <div class="flex items-center justify-between gap-2 pt-1">
                        <button type="button" onclick="rotateCard('back')" class="px-2.5 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-[11px] font-bold border border-slate-200 flex items-center gap-1 cursor-pointer">
                            <span>🔄 ঘোরান</span>
                        </button>
                        <button type="button" onclick="openCropperModal('back')" class="px-2.5 py-1.5 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-800 text-[11px] font-bold border border-blue-200 flex items-center gap-1 cursor-pointer">
                            <span>✏️ ক্রপ অ্যাডজাস্ট</span>
                        </button>
                        <button type="button" onclick="resetSide('back')" class="px-2.5 py-1.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 text-[11px] font-bold border border-rose-200 flex items-center gap-1 cursor-pointer">
                            <span>✕ পরিবর্তন</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- STEP 3: EMPLOYEE & REFERENCE IDENTIFIERS -->
            <div class="bg-white p-4 rounded-3xl border border-slate-200 shadow-sm space-y-3">
                <span class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-1.5">
                    <span class="w-5 h-5 rounded-full bg-teal-600 text-white text-[11px] font-bold flex items-center justify-center">৩</span>
                    <span>কর্মকর্তা ও রেফারেন্স পরিচিতি</span>
                </span>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">আপনার নাম / পদবী:</label>
                        <input type="text" name="employee_name" 
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-900 focus:outline-none focus:border-indigo-500"
                            placeholder="যেমন: শামীম (এইচআর)">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">পিসি / রেফারেন্স নং:</label>
                        <input type="text" name="pickup_reference" 
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-900 focus:outline-none focus:border-indigo-500"
                            placeholder="যেমন: পিসি-২ / স্টাফ ফাইল">
                    </div>
                </div>
            </div>

            <!-- STEP 4: A4 LIVE MOCKUP -->
            <div class="bg-white p-4 rounded-3xl border border-slate-200 shadow-sm space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-1.5">
                        <span class="w-5 h-5 rounded-full bg-indigo-600 text-white text-[11px] font-bold flex items-center justify-center">৪</span>
                        <span>এ৪ পেজ লাইভ প্রিভিউ (1:1 Real Scale)</span>
                    </span>
                </div>

                <div class="p-3 bg-slate-200/50 rounded-2xl border border-slate-200 flex justify-center">
                    <div class="a4-mockup w-52 rounded-lg p-3 flex flex-col justify-start items-center border border-slate-300 relative shadow-md overflow-hidden">
                        <div class="w-full mt-3 flex flex-col items-center">
                            <span class="text-[8px] font-bold text-slate-400 uppercase tracking-wider mb-1">সামনের অংশ</span>
                            <div class="w-36 card-ratio rounded border border-dashed border-slate-300 bg-slate-50 flex items-center justify-center overflow-hidden" id="a4FrontSlot">
                                <span class="text-[9px] text-slate-400">সামনের ছবি দিন</span>
                            </div>
                        </div>
                        <div class="my-2 border-b border-dashed border-slate-200 w-full"></div>
                        <div class="w-full flex flex-col items-center">
                            <span class="text-[8px] font-bold text-slate-400 uppercase tracking-wider mb-1">পেছনের অংশ</span>
                            <div class="w-36 card-ratio rounded border border-dashed border-slate-300 bg-slate-50 flex items-center justify-center overflow-hidden" id="a4BackSlot">
                                <span class="text-[9px] text-slate-400">পেছনের ছবি দিন</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- STEP 5: COLOR & COPIES -->
            <div class="bg-white p-4 rounded-3xl border border-slate-200 shadow-sm space-y-4">
                <span class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-1.5">
                    <span class="w-5 h-5 rounded-full bg-purple-600 text-white text-[11px] font-bold flex items-center justify-center">৫</span>
                    <span>প্রিন্ট সেটিংস</span>
                </span>

                <div class="grid grid-cols-2 gap-3">
                    <label class="border-2 border-slate-200 rounded-2xl p-3 flex items-center justify-between cursor-pointer has-[:checked]:border-slate-900 has-[:checked]:bg-slate-50 transition-all">
                        <span class="text-xs font-bold text-slate-900">⚪⚫ সাদাকালো</span>
                        <input type="radio" name="color_mode" value="bw" checked class="accent-slate-900" onchange="updateA4Mockup()">
                    </label>

                    @if($shop->has_color_print)
                    <label class="border-2 border-slate-200 rounded-2xl p-3 flex items-center justify-between cursor-pointer has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50/30 transition-all">
                        <span class="text-xs font-bold text-slate-900">🎨 রঙিন (Color)</span>
                        <input type="radio" name="color_mode" value="color" class="accent-indigo-600" onchange="updateA4Mockup()">
                    </label>
                    @endif
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">কপি সংখ্যা:</label>
                    <input type="number" name="copies" value="1" min="1" max="50" required 
                        class="w-full py-2 rounded-xl border border-slate-200 text-sm font-bold text-center">
                </div>
            </div>

            <!-- SUBMIT BUTTON -->
            <button type="submit" id="submitBtn" class="w-full py-4 rounded-2xl bg-gradient-to-r from-indigo-600 via-blue-600 to-indigo-700 hover:from-indigo-700 hover:to-blue-700 text-white font-black text-sm shadow-xl shadow-indigo-600/30 transition-all active:scale-95 flex items-center justify-center gap-2 cursor-pointer">
                <span>🖨️ সেন্ট্রাল প্রিন্টারে এনআইডি প্রিন্ট করুন →</span>
            </button>
        </form>

        <div class="text-center text-[10px] text-slate-400">
            🏢 অফিস ইন্টারনাল নেটওয়ার্ক প্রিন্টার • সম্পূর্ণ অটোমেটেড প্রিন্ট
        </div>
    </div>

    <!-- CORNER ADJUSTMENT / CROPPER MODAL -->
    <div id="cropperModal" class="fixed inset-0 z-50 bg-slate-950/90 backdrop-blur-sm hidden flex flex-col justify-between p-4">
        <div class="flex items-center justify-between text-white pb-2 border-b border-slate-800">
            <div>
                <h3 class="text-sm font-bold" id="cropperModalTitle">🪪 এনআইডি ক্রপ ও সোজা করুন</h3>
                <span class="text-[10px] text-slate-400">৪টি কোণা টেনে কার্ডের বর্ডারে নিখুঁতভাবে বসান</span>
            </div>
            <button type="button" onclick="closeCropperModal()" class="w-8 h-8 rounded-full bg-slate-800 text-slate-300 flex items-center justify-center">✕</button>
        </div>

        <div class="flex-1 flex items-center justify-center my-2 relative overflow-hidden" id="cropperCanvasContainer">
            <canvas id="cropperCanvas" class="max-w-full max-h-[60vh] object-contain shadow-2xl rounded-lg"></canvas>
        </div>

        <div class="space-y-2 pt-2 border-t border-slate-800">
            <div class="flex items-center justify-between gap-2">
                <button type="button" onclick="autoDetectCurrentModal()" class="px-3 py-2 rounded-xl bg-slate-800 text-indigo-400 text-xs font-bold border border-slate-700">
                    <span>🤖 অটো এআই ডিটেক্ট</span>
                </button>
                <button type="button" onclick="rotateCurrentModalImage()" class="px-3 py-2 rounded-xl bg-slate-800 text-slate-200 text-xs font-bold border border-slate-700">
                    <span>🔄 ৯০° ঘোরান</span>
                </button>
                <button type="button" onclick="resetCurrentModalCorners()" class="px-3 py-2 rounded-xl bg-slate-800 text-slate-400 text-xs font-bold border border-slate-700">
                    <span>↺ রিসেট</span>
                </button>
            </div>
            <button type="button" onclick="saveCropperModal()" class="w-full py-3 rounded-xl bg-indigo-600 text-white font-bold text-xs shadow-lg shadow-indigo-600/30">
                <span>✓ ক্রপ সম্পন্ন ও সোজা করুন</span>
            </button>
        </div>
    </div>

    <script>
        const TARGET_WIDTH = 1012;
        const TARGET_HEIGHT = 638;

        const cardState = {
            front: { originalImage: null, rotation: 0, corners: null, croppedCanvas: null, filter: 'clean_bw' },
            back: { originalImage: null, rotation: 0, corners: null, croppedCanvas: null, filter: 'clean_bw' }
        };

        function applyFilterToCanvas(srcCanvas, filterMode) {
            const out = document.createElement('canvas');
            out.width = srcCanvas.width;
            out.height = srcCanvas.height;
            const ctx = out.getContext('2d');
            ctx.drawImage(srcCanvas, 0, 0);

            if (filterMode === 'original') return out;

            const imgData = ctx.getImageData(0, 0, out.width, out.height);
            const d = imgData.data;
            const len = d.length;

            if (filterMode === 'clean_bw') {
                for (let i = 0; i < len; i += 4) {
                    const r = d[i], g = d[i + 1], b = d[i + 2];
                    const lum = 0.299 * r + 0.587 * g + 0.114 * b;
                    if (lum > 148) {
                        d[i] = 255; d[i + 1] = 255; d[i + 2] = 255;
                    } else if (lum < 112) {
                        d[i] = 0; d[i + 1] = 0; d[i + 2] = 0;
                    } else {
                        const val = Math.min(255, Math.floor(((lum - 112) / 36) * 255));
                        d[i] = val; d[i + 1] = val; d[i + 2] = val;
                    }
                }
            } else if (filterMode === 'magic_color') {
                for (let i = 0; i < len; i += 4) {
                    const r = d[i], g = d[i + 1], b = d[i + 2];
                    const lum = 0.299 * r + 0.587 * g + 0.114 * b;
                    const max = Math.max(r, g, b), min = Math.min(r, g, b);
                    const sat = max === 0 ? 0 : (max - min) / max;
                    if (lum > 145 && (sat < 0.45 || (b > r && b > g && lum > 155))) {
                        d[i] = 255; d[i + 1] = 255; d[i + 2] = 255;
                    } else if (lum < 100) {
                        d[i] = Math.max(0, r - 30);
                        d[i + 1] = Math.max(0, g - 30);
                        d[i + 2] = Math.max(0, b - 30);
                    }
                }
            }
            ctx.putImageData(imgData, 0, 0);
            return out;
        }

        function renderSideOutput(side) {
            const state = cardState[side];
            if (!state.croppedCanvas) return;

            const mode = state.filter || 'clean_bw';
            const finalCanvas = applyFilterToCanvas(state.croppedCanvas, mode);

            const previewCanvas = document.getElementById(`${side}Canvas`);
            previewCanvas.width = TARGET_WIDTH;
            previewCanvas.height = TARGET_HEIGHT;
            const pCtx = previewCanvas.getContext('2d');
            pCtx.drawImage(finalCanvas, 0, 0);

            const croppedInput = document.getElementById(`${side}CroppedData`);
            if (croppedInput) {
                croppedInput.value = finalCanvas.toDataURL('image/jpeg', 0.95);
            }

            if (typeof updateA4Mockup === 'function') updateA4Mockup();
        }

        function setSideFilter(side, filterMode) {
            cardState[side].filter = filterMode;
            ['clean_bw', 'magic_color', 'original'].forEach(m => {
                const btn = document.getElementById(`${side}Filter_${m}`);
                if (!btn) return;
                const activeColor = side === 'front' ? 'text-indigo-800 border-indigo-300' : 'text-blue-800 border-blue-300';
                if (m === filterMode) {
                    btn.className = `flex-1 py-1 px-1 rounded-lg text-[10px] font-bold bg-white ${activeColor} shadow-xs border`;
                } else {
                    btn.className = 'flex-1 py-1 px-1 rounded-lg text-[10px] font-bold text-slate-600 hover:text-slate-900';
                }
            });
            renderSideOutput(side);
        }

        let activeModalSide = null;
        let activeHandleIndex = -1;

        function triggerFileInput(side, isCamera) {
            const input = document.getElementById(`${side}FileInput`);
            if (isCamera) input.setAttribute('capture', 'environment');
            else input.removeAttribute('capture');
            input.click();
        }

        // File Selection Handlers
        ['front', 'back'].forEach(side => {
            const input = document.getElementById(`${side}FileInput`);
            input.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (!file) return;
                processUploadedFile(side, file);
            });
        });

        function readFileAsDataUrl(file) {
            return new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.onload = () => resolve(reader.result);
                reader.onerror = reject;
                reader.readAsDataURL(file);
            });
        }

        function loadImageFromSrc(src) {
            return new Promise((resolve, reject) => {
                const img = new Image();
                img.onload = () => resolve(img);
                img.onerror = reject;
                img.src = src;
            });
        }

        // Main File / Paste Processing Pipeline
        async function processUploadedFile(side, file, optionalDataUrl = null) {
            const loadingEl = document.getElementById(`${side}AiLoading`);
            const statusBadge = document.getElementById(`${side}StatusBadge`);

            if (loadingEl) loadingEl.classList.remove('hidden');
            if (statusBadge) {
                statusBadge.className = 'text-[10px] font-bold px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 animate-pulse';
                statusBadge.innerText = '🤖 AI প্রসেসিং...';
            }

            try {
                const dataUrl = optionalDataUrl || await readFileAsDataUrl(file);
                const originalImg = await loadImageFromSrc(dataUrl);

                cardState[side].originalImage = originalImg;
                cardState[side].rotation = 0;

                let aiSuccess = false;
                try {
                    const formData = new FormData();
                    if (file) {
                        formData.append('file', file);
                    } else {
                        formData.append('image_data', dataUrl);
                    }

                    const response = await fetch('{{ route("api.ai.auto_crop") }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });

                    if (response.ok) {
                        const result = await response.json();
                        if (result.success && result.image_data) {
                            const croppedImg = await loadImageFromSrc(result.image_data);

                            const outCanvas = document.createElement('canvas');
                            outCanvas.width = TARGET_WIDTH;
                            outCanvas.height = TARGET_HEIGHT;
                            const outCtx = outCanvas.getContext('2d');
                            outCtx.drawImage(croppedImg, 0, 0, TARGET_WIDTH, TARGET_HEIGHT);

                            cardState[side].croppedCanvas = outCanvas;
                            document.getElementById(`${side}UploadBox`).classList.add('hidden');
                            document.getElementById(`${side}PreviewBox`).classList.remove('hidden');

                            if (statusBadge) {
                                statusBadge.className = 'text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800';
                                statusBadge.innerText = '✨ AI পারফেক্ট ক্রপ';
                            }

                            renderSideOutput(side);
                            aiSuccess = true;

                            fetchAiCorners(side, file, dataUrl, originalImg.width, originalImg.height);
                        }
                    }
                } catch (apiErr) {
                    console.warn('Python AI API not reachable, falling back to local detection:', apiErr);
                }

                if (!aiSuccess) {
                    detectCardAndCrop(side);
                    if (statusBadge) {
                        statusBadge.className = 'text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800';
                        statusBadge.innerText = '✓ ক্রপ রেডি';
                    }
                }
            } catch (err) {
                console.error('File process error:', err);
                Swal.fire({
                    icon: 'error',
                    title: 'ত্রুটি',
                    text: 'ছবি প্রসেস করা সম্ভব হয়নি। অনুগ্রহ করে অন্য কোনো ছবি চেষ্টা করুন।',
                    confirmButtonColor: '#4f46e5'
                });
            } finally {
                if (loadingEl) loadingEl.classList.add('hidden');
            }
        }

        async function fetchAiCorners(side, file, dataUrl, origW, origH) {
            try {
                const formData = new FormData();
                if (file) {
                    formData.append('file', file);
                } else {
                    formData.append('image_data', dataUrl);
                }

                const res = await fetch('{{ route("api.ai.detect_corners") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                if (res.ok) {
                    const data = await res.json();
                    if (data.success && Array.isArray(data.corners) && data.corners.length === 4) {
                        cardState[side].corners = data.corners.map(pt => ({
                            x: pt.x * origW,
                            y: pt.y * origH
                        }));
                    }
                }
            } catch (e) {
                console.warn('AI corner detection warning:', e);
            }
        }

        async function pasteFromClipboard(side) {
            try {
                if (navigator.clipboard && navigator.clipboard.read) {
                    const items = await navigator.clipboard.read();
                    for (const item of items) {
                        const imageType = item.types.find(t => t.startsWith('image/'));
                        if (imageType) {
                            const blob = await item.getType(imageType);
                            const file = new File([blob], `pasted_${side}_${Date.now()}.png`, { type: imageType });
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: `📋 ${side === 'front' ? 'সামনের' : 'পেছনের'} কার্ডের ছবি পেস্ট হয়েছে!`,
                                showConfirmButton: false,
                                timer: 2000
                            });
                            processUploadedFile(side, file);
                            return;
                        }
                    }
                }
                Swal.fire({
                    icon: 'info',
                    title: 'ছবি পেস্ট করুন (Ctrl + V)',
                    text: 'কীবোর্ডে Ctrl + V চাপুন। কপি করা ছবিটি স্বয়ংক্রিয়ভাবে বসে যাবে!',
                    confirmButtonColor: '#4f46e5',
                    confirmButtonText: 'বুঝেছি'
                });
            } catch (e) {
                Swal.fire({
                    icon: 'info',
                    title: 'ছবি পেস্ট করুন (Ctrl + V)',
                    text: 'কীবোর্ডে Ctrl + V চাপুন। কপি করা ছবিটি সরাসরি পেস্ট হয়ে যাবে!',
                    confirmButtonColor: '#4f46e5',
                    confirmButtonText: 'ঠিক আছে'
                });
            }
        }

        window.addEventListener('paste', function(e) {
            const items = (e.clipboardData || e.originalEvent?.clipboardData)?.items;
            if (!items) return;

            for (let i = 0; i < items.length; i++) {
                if (items[i].type && items[i].type.startsWith('image/')) {
                    const file = items[i].getAsFile();
                    if (!file) continue;

                    let targetSide = 'front';
                    if (activeModalSide) {
                        targetSide = activeModalSide;
                    } else if (cardState.front.originalImage && !cardState.back.originalImage) {
                        targetSide = 'back';
                    }

                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: `📋 ${targetSide === 'front' ? 'সামনের' : 'পেছনের'} কার্ডের ছবি পেস্ট হয়েছে!`,
                        showConfirmButton: false,
                        timer: 2000
                    });

                    processUploadedFile(targetSide, file);
                    break;
                }
            }
        });

        function detectCardAndCrop(side) {
            const state = cardState[side];
            const img = state.originalImage;
            if (!img) return;

            const buffer = getRotatedCanvas(img, state.rotation);
            state.corners = findCardCorners(buffer);
            warpAndRender(side);
        }

        function rotateCard(side) {
            const state = cardState[side];
            if (!state.originalImage) return;
            state.rotation = (state.rotation + 90) % 360;
            detectCardAndCrop(side);
        }

        function resetSide(side) {
            cardState[side].originalImage = null;
            cardState[side].corners = null;
            cardState[side].croppedCanvas = null;

            document.getElementById(`${side}UploadBox`).classList.remove('hidden');
            document.getElementById(`${side}PreviewBox`).classList.add('hidden');
            document.getElementById(`${side}StatusBadge`).className = 'text-[10px] font-bold px-2 py-0.5 rounded-full bg-slate-100 text-slate-500';
            document.getElementById(`${side}StatusBadge`).innerText = 'ছবি দিন';
            document.getElementById(`${side}CroppedData`).value = '';

            updateA4Mockup();
        }

        function getRotatedCanvas(img, deg) {
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');
            if (deg === 90 || deg === 270) {
                canvas.width = img.height;
                canvas.height = img.width;
            } else {
                canvas.width = img.width;
                canvas.height = img.height;
            }
            ctx.translate(canvas.width / 2, canvas.height / 2);
            ctx.rotate((deg * Math.PI) / 180);
            ctx.drawImage(img, -img.width / 2, -img.height / 2);
            return canvas;
        }

        function findCardCorners(canvas) {
            const w = canvas.width, h = canvas.height;
            const scale = Math.min(1, 400 / Math.max(w, h));
            const sw = Math.round(w * scale), sh = Math.round(h * scale);

            const small = document.createElement('canvas');
            small.width = sw; small.height = sh;
            const sctx = small.getContext('2d');
            sctx.drawImage(canvas, 0, 0, sw, sh);

            const data = sctx.getImageData(0, 0, sw, sh).data;
            const margin = Math.max(4, Math.floor(sw * 0.03));
            let bgR = 0, bgG = 0, bgB = 0, bgCount = 0;
            const samples = [[margin, margin], [sw - margin, margin], [margin, sh - margin], [sw - margin, sh - margin]];
            samples.forEach(([sx, sy]) => {
                const idx = (sy * sw + sx) * 4;
                bgR += data[idx]; bgG += data[idx + 1]; bgB += data[idx + 2]; bgCount++;
            });
            bgR /= bgCount; bgG /= bgCount; bgB /= bgCount;
            const bgLum = 0.299 * bgR + 0.587 * bgG + 0.114 * bgB;

            const points = [];
            for (let y = margin; y < sh - margin; y += 4) {
                for (let x = margin; x < sw - margin; x += 4) {
                    const idx = (y * sw + x) * 4;
                    const r = data[idx], g = data[idx + 1], b = data[idx + 2];
                    const lum = 0.299 * r + 0.587 * g + 0.114 * b;
                    const diff = Math.hypot(r - bgR, g - bgG, b - bgB);
                    if (diff > 35 || Math.abs(lum - bgLum) > 30) points.push([x, y]);
                }
            }

            if (points.length > (sw * sh / 160)) {
                let minS = Infinity, maxS = -Infinity, minD = Infinity, maxD = -Infinity;
                let tl = null, br = null, tr = null, bl = null;
                for (let i = 0; i < points.length; i++) {
                    const [px, py] = points[i];
                    const s = px + py, d = px - py;
                    if (s < minS) { minS = s; tl = [px, py]; }
                    if (s > maxS) { maxS = s; br = [px, py]; }
                    if (d > maxD) { maxD = d; tr = [px, py]; }
                    if (d < minD) { minD = d; bl = [px, py]; }
                }
                if (tl && tr && br && bl) {
                    const inv = 1 / scale;
                    return [
                        { x: tl[0] * inv, y: tl[1] * inv },
                        { x: tr[0] * inv, y: tr[1] * inv },
                        { x: br[0] * inv, y: br[1] * inv },
                        { x: bl[0] * inv, y: bl[1] * inv }
                    ];
                }
            }

            const ix = w * 0.08, iy = h * 0.12;
            return [
                { x: ix, y: iy }, { x: w - ix, y: iy },
                { x: w - ix, y: h - iy }, { x: ix, y: h - iy }
            ];
        }

        function warpAndRender(side) {
            const state = cardState[side];
            const img = state.originalImage;
            if (!img || !state.corners) return;

            const rotCanvas = getRotatedCanvas(img, state.rotation);
            const corners = state.corners;

            const outCanvas = document.createElement('canvas');
            outCanvas.width = TARGET_WIDTH;
            outCanvas.height = TARGET_HEIGHT;
            const outCtx = outCanvas.getContext('2d');

            renderPerspectiveMesh(rotCanvas, outCtx, corners, TARGET_WIDTH, TARGET_HEIGHT, 6);
            state.croppedCanvas = outCanvas;
            renderSideOutput(side);

            document.getElementById(`${side}UploadBox`).classList.add('hidden');
            document.getElementById(`${side}PreviewBox`).classList.remove('hidden');
            document.getElementById(`${side}StatusBadge`).className = 'text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800';
            document.getElementById(`${side}StatusBadge`).innerText = '✓ ক্রপ সম্পন্ন';
        }

        function renderPerspectiveMesh(srcCanvas, destCtx, corners, destW, destH, subdivisions) {
            const [c0, c1, c2, c3] = corners;
            function interpQuad(u, v) {
                const topX = c0.x + (c1.x - c0.x) * u, topY = c0.y + (c1.y - c0.y) * u;
                const botX = c3.x + (c2.x - c3.x) * u, botY = c3.y + (c2.y - c3.y) * u;
                return { x: topX + (botX - topX) * v, y: topY + (botY - topY) * v };
            }
            destCtx.clearRect(0, 0, destW, destH);
            const n = subdivisions || 4;
            for (let i = 0; i < n; i++) {
                for (let j = 0; j < n; j++) {
                    const u0 = i / n, u1 = (i + 1) / n;
                    const v0 = j / n, v1 = (j + 1) / n;
                    const p00 = interpQuad(u0, v0), p10 = interpQuad(u1, v0);
                    const p01 = interpQuad(u0, v1), p11 = interpQuad(u1, v1);
                    const dX0 = u0 * destW, dX1 = u1 * destW;
                    const dY0 = v0 * destH, dY1 = v1 * destH;
                    drawTriangle(destCtx, srcCanvas, p00.x, p00.y, p10.x, p10.y, p01.x, p01.y, dX0, dY0, dX1, dY0, dX0, dY1);
                    drawTriangle(destCtx, srcCanvas, p10.x, p10.y, p11.x, p11.y, p01.x, p01.y, dX1, dY0, dX1, dY1, dX0, dY1);
                }
            }
        }

        function drawTriangle(ctx, im, x0, y0, x1, y1, x2, y2, u0, v0, u1, v1, u2, v2) {
            ctx.save();
            ctx.beginPath();
            ctx.moveTo(u0, v0); ctx.lineTo(u1, v1); ctx.lineTo(u2, v2); ctx.closePath();
            ctx.clip();
            const delta = x0 * (y1 - y2) + x1 * (y2 - y0) + x2 * (y0 - y1);
            if (Math.abs(delta) < 1e-5) { ctx.restore(); return; }
            const a = (u0 * (y1 - y2) + u1 * (y2 - y0) + u2 * (y0 - y1)) / delta;
            const b = (u0 * (x2 - x1) + u1 * (x0 - x2) + u2 * (x1 - x0)) / delta;
            const c = (u0 * (x1 * y2 - x2 * y1) + u1 * (x2 * y0 - x0 * y2) + u2 * (x0 * y1 - x1 * y0)) / delta;
            const d = (v0 * (y1 - y2) + v1 * (y2 - y0) + v2 * (y0 - y1)) / delta;
            const e = (v0 * (x2 - x1) + v1 * (x0 - x2) + v2 * (x1 - x0)) / delta;
            const f = (v0 * (x1 * y2 - x2 * y1) + v1 * (x2 * y0 - x0 * y2) + v2 * (x0 * y1 - x1 * y0)) / delta;
            ctx.transform(a, d, b, e, c, f);
            ctx.drawImage(im, 0, 0);
            ctx.restore();
        }

        function updateA4Mockup() {
            const frontSlot = document.getElementById('a4FrontSlot');
            const backSlot = document.getElementById('a4BackSlot');
            const colorMode = document.querySelector('input[name="color_mode"]:checked')?.value || 'bw';
            const filterClass = (colorMode === 'bw') ? 'grayscale contrast-125' : '';

            if (cardState.front.croppedCanvas) {
                frontSlot.innerHTML = `<img src="${cardState.front.croppedCanvas.toDataURL()}" class="w-full h-full object-cover ${filterClass}">`;
            } else {
                frontSlot.innerHTML = `<span class="text-[9px] text-slate-400">সামনের ছবি দিন</span>`;
            }

            if (cardState.back.croppedCanvas) {
                backSlot.innerHTML = `<img src="${cardState.back.croppedCanvas.toDataURL()}" class="w-full h-full object-cover ${filterClass}">`;
            } else {
                backSlot.innerHTML = `<span class="text-[9px] text-slate-400">পেছনের ছবি দিন</span>`;
            }
        }

        const modalCanvas = document.getElementById('cropperCanvas');
        const mctx = modalCanvas.getContext('2d');
        let modalRotCanvas = null, modalScale = 1, modalCorners = [];

        function openCropperModal(side) {
            activeModalSide = side;
            const state = cardState[side];
            if (!state.originalImage) return;
            document.getElementById('cropperModalTitle').innerText = `🪪 ${side === 'front' ? 'সামনের' : 'পেছনের'} অংশ ক্রপ ও সোজা করুন`;
            modalRotCanvas = getRotatedCanvas(state.originalImage, state.rotation);
            modalCorners = state.corners ? JSON.parse(JSON.stringify(state.corners)) : findCardCorners(modalRotCanvas);
            document.getElementById('cropperModal').classList.remove('hidden');
            drawCropperModal();
        }

        function closeCropperModal() {
            document.getElementById('cropperModal').classList.add('hidden');
            activeModalSide = null;
        }

        function saveCropperModal() {
            if (!activeModalSide) return;
            cardState[activeModalSide].corners = JSON.parse(JSON.stringify(modalCorners));
            warpAndRender(activeModalSide);
            closeCropperModal();
        }

        function autoDetectCurrentModal() {
            if (!modalRotCanvas) return;
            modalCorners = findCardCorners(modalRotCanvas);
            drawCropperModal();
        }

        function rotateCurrentModalImage() {
            if (!activeModalSide) return;
            const state = cardState[activeModalSide];
            state.rotation = (state.rotation + 90) % 360;
            modalRotCanvas = getRotatedCanvas(state.originalImage, state.rotation);
            modalCorners = findCardCorners(modalRotCanvas);
            drawCropperModal();
        }

        function resetCurrentModalCorners() {
            if (!modalRotCanvas) return;
            const w = modalRotCanvas.width, h = modalRotCanvas.height;
            const ix = w * 0.05, iy = h * 0.08;
            modalCorners = [{ x: ix, y: iy }, { x: w - ix, y: iy }, { x: w - ix, y: h - iy }, { x: ix, y: h - iy }];
            drawCropperModal();
        }

        function drawCropperModal() {
            if (!modalRotCanvas) return;
            const container = document.getElementById('cropperCanvasContainer');
            const maxW = container.clientWidth || 360, maxH = container.clientHeight || 450;
            const scale = Math.min(maxW / modalRotCanvas.width, maxH / modalRotCanvas.height, 1);
            modalScale = scale;

            modalCanvas.width = modalRotCanvas.width * scale;
            modalCanvas.height = modalRotCanvas.height * scale;

            mctx.drawImage(modalRotCanvas, 0, 0, modalCanvas.width, modalCanvas.height);

            mctx.save();
            mctx.fillStyle = 'rgba(0, 0, 0, 0.55)';
            mctx.fillRect(0, 0, modalCanvas.width, modalCanvas.height);

            mctx.globalCompositeOperation = 'destination-out';
            mctx.beginPath();
            mctx.moveTo(modalCorners[0].x * scale, modalCorners[0].y * scale);
            mctx.lineTo(modalCorners[1].x * scale, modalCorners[1].y * scale);
            mctx.lineTo(modalCorners[2].x * scale, modalCorners[2].y * scale);
            mctx.lineTo(modalCorners[3].x * scale, modalCorners[3].y * scale);
            mctx.closePath();
            mctx.fill();
            mctx.restore();

            mctx.strokeStyle = '#6366f1';
            mctx.lineWidth = 2.5;
            mctx.beginPath();
            mctx.moveTo(modalCorners[0].x * scale, modalCorners[0].y * scale);
            mctx.lineTo(modalCorners[1].x * scale, modalCorners[1].y * scale);
            mctx.lineTo(modalCorners[2].x * scale, modalCorners[2].y * scale);
            mctx.lineTo(modalCorners[3].x * scale, modalCorners[3].y * scale);
            mctx.closePath();
            mctx.stroke();

            modalCorners.forEach((pt, idx) => {
                const cx = pt.x * scale, cy = pt.y * scale;
                mctx.beginPath();
                mctx.arc(cx, cy, 14, 0, 2 * Math.PI);
                mctx.fillStyle = (idx === activeHandleIndex) ? '#4338ca' : '#6366f1';
                mctx.fill();
                mctx.strokeStyle = '#ffffff';
                mctx.lineWidth = 3;
                mctx.stroke();
            });

            // Draw Floating Circular Magnifying Glass (Loupe) when dragging a corner handle
            if (activeHandleIndex !== -1 && activeHandleIndex >= 0 && activeHandleIndex < modalCorners.length) {
                const curPt = modalCorners[activeHandleIndex];
                const cx = curPt.x * scale;
                const cy = curPt.y * scale;

                const loupeRadius = 48; // Circular magnifier radius (diameter 96px)
                const zoom = 2.4;       // 2.4x zoom for crisp edge alignment

                // Position loupe vertically above the finger so finger does not obstruct the view
                const offsetAbove = 85;
                const loupeY = (cy - offsetAbove - loupeRadius >= 8) 
                    ? (cy - offsetAbove) 
                    : (cy + offsetAbove);

                // Clamp loupeX horizontally inside canvas bounds with padding
                const loupeX = Math.max(loupeRadius + 10, Math.min(modalCanvas.width - loupeRadius - 10, cx));

                // Save canvas state for clipped loupe
                mctx.save();

                // Drop shadow around the lens
                mctx.shadowColor = 'rgba(0, 0, 0, 0.45)';
                mctx.shadowBlur = 16;
                mctx.shadowOffsetX = 0;
                mctx.shadowOffsetY = 4;

                // Begin circular clip path
                mctx.beginPath();
                mctx.arc(loupeX, loupeY, loupeRadius, 0, 2 * Math.PI);
                mctx.fillStyle = '#1e1b4b';
                mctx.fill();
                mctx.clip();

                // Reset shadow inside clip
                mctx.shadowColor = 'transparent';

                // Source dimensions from unscaled modalRotCanvas
                const srcDiameter = (loupeRadius * 2) / (scale * zoom);
                const srcX = curPt.x - srcDiameter / 2;
                const srcY = curPt.y - srcDiameter / 2;

                // Draw zoomed image portion
                mctx.drawImage(
                    modalRotCanvas,
                    srcX, srcY, srcDiameter, srcDiameter,
                    loupeX - loupeRadius, loupeY - loupeRadius, loupeRadius * 2, loupeRadius * 2
                );

                // Draw precision Crosshair Target in center of loupe
                mctx.strokeStyle = 'rgba(255, 255, 255, 0.9)';
                mctx.lineWidth = 2.5;
                mctx.beginPath();
                mctx.arc(loupeX, loupeY, 6, 0, 2 * Math.PI);
                mctx.stroke();

                // Precision Red Reticle / Crosshair lines (with center gap)
                mctx.strokeStyle = '#ef4444';
                mctx.lineWidth = 1.8;
                mctx.beginPath();
                mctx.moveTo(loupeX - 18, loupeY);
                mctx.lineTo(loupeX - 4, loupeY);
                mctx.moveTo(loupeX + 4, loupeY);
                mctx.lineTo(loupeX + 18, loupeY);
                mctx.moveTo(loupeX, loupeY - 18);
                mctx.lineTo(loupeX, loupeY - 4);
                mctx.moveTo(loupeX, loupeY + 4);
                mctx.lineTo(loupeX, loupeY + 18);
                mctx.stroke();

                // Center red dot
                mctx.beginPath();
                mctx.arc(loupeX, loupeY, 2.5, 0, 2 * Math.PI);
                mctx.fillStyle = '#ef4444';
                mctx.fill();

                mctx.restore();

                // Outer border lens casing (Indigo theme)
                mctx.save();
                mctx.beginPath();
                mctx.arc(loupeX, loupeY, loupeRadius, 0, 2 * Math.PI);
                mctx.strokeStyle = '#6366f1';
                mctx.lineWidth = 3.5;
                mctx.stroke();

                // Thin white inner rim highlight
                mctx.beginPath();
                mctx.arc(loupeX, loupeY, loupeRadius - 2.5, 0, 2 * Math.PI);
                mctx.strokeStyle = 'rgba(255, 255, 255, 0.6)';
                mctx.lineWidth = 1.2;
                mctx.stroke();

                // Small pill label indicating corner name & zoom factor
                const badgeY = (loupeY < cy) 
                    ? (loupeY - loupeRadius - 10) 
                    : (loupeY + loupeRadius + 12);
                const cornerNames = ['১. বাম-উপর', '২. ডান-উপর', '৩. ডান-নিচ', '৪. বাম-নিচ'];
                const cornerLabel = (cornerNames[activeHandleIndex] || 'কোণা') + ' (২.৪x জুম)';
                
                mctx.font = 'bold 11px "Hind Siliguri", sans-serif';
                mctx.textAlign = 'center';
                mctx.textBaseline = 'middle';
                const textWidth = mctx.measureText(cornerLabel).width;
                const badgeWidth = textWidth + 14;
                const badgeHeight = 18;

                mctx.fillStyle = 'rgba(15, 23, 42, 0.9)';
                mctx.beginPath();
                if (typeof mctx.roundRect === 'function') {
                    mctx.roundRect(loupeX - badgeWidth / 2, badgeY - badgeHeight / 2, badgeWidth, badgeHeight, 9);
                } else {
                    mctx.rect(loupeX - badgeWidth / 2, badgeY - badgeHeight / 2, badgeWidth, badgeHeight);
                }
                mctx.fill();
                mctx.fillStyle = '#818cf8';
                mctx.fillText(cornerLabel, loupeX, badgeY);

                // Dotted connector line from corner handle to loupe for visual connection
                mctx.strokeStyle = 'rgba(99, 102, 241, 0.6)';
                mctx.lineWidth = 1.5;
                mctx.setLineDash([3, 3]);
                mctx.beginPath();
                mctx.moveTo(cx, cy);
                mctx.lineTo(loupeX, (loupeY < cy) ? (loupeY + loupeRadius) : (loupeY - loupeRadius));
                mctx.stroke();
                mctx.setLineDash([]); // Reset line dash

                mctx.restore();
            }
        }

        function getCanvasPoint(evt) {
            const rect = modalCanvas.getBoundingClientRect();
            const clientX = evt.touches ? evt.touches[0].clientX : evt.clientX;
            const clientY = evt.touches ? evt.touches[0].clientY : evt.clientY;
            return { x: (clientX - rect.left) / modalScale, y: (clientY - rect.top) / modalScale };
        }

        function findNearCorner(pt) {
            const hitRadius = 35 / modalScale;
            for (let i = 0; i < modalCorners.length; i++) {
                if (Math.hypot(modalCorners[i].x - pt.x, modalCorners[i].y - pt.y) <= hitRadius) return i;
            }
            return -1;
        }

        function onPointerDown(e) {
            e.preventDefault();
            const pt = getCanvasPoint(e);
            activeHandleIndex = findNearCorner(pt);
            if (activeHandleIndex !== -1) drawCropperModal();
        }

        function onPointerMove(e) {
            if (activeHandleIndex === -1) return;
            e.preventDefault();
            const pt = getCanvasPoint(e);
            modalCorners[activeHandleIndex].x = Math.max(0, Math.min(modalRotCanvas.width, pt.x));
            modalCorners[activeHandleIndex].y = Math.max(0, Math.min(modalRotCanvas.height, pt.y));
            drawCropperModal();
        }

        function onPointerUp() {
            activeHandleIndex = -1;
            drawCropperModal();
        }

        modalCanvas.addEventListener('mousedown', onPointerDown);
        window.addEventListener('mousemove', onPointerMove);
        window.addEventListener('mouseup', onPointerUp);
        modalCanvas.addEventListener('touchstart', onPointerDown, { passive: false });
        window.addEventListener('touchmove', onPointerMove, { passive: false });
        window.addEventListener('touchend', onPointerUp, { passive: false });

        document.getElementById('officeNidForm').addEventListener('submit', function(e) {
            if (!cardState.front.croppedCanvas || !cardState.back.croppedCanvas) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'ছবি অসম্পূর্ণ',
                    text: 'সামনের ও পেছনের উভয় অংশের ছবি দিন।',
                    confirmButtonColor: '#4f46e5'
                });
                return;
            }

            // Ensure cropped base64 is populated
            const frontDataInput = document.getElementById('frontCroppedData');
            if (!frontDataInput.value || frontDataInput.value.length < 50) {
                frontDataInput.value = cardState.front.croppedCanvas.toDataURL('image/jpeg', 0.92);
            }
            const backDataInput = document.getElementById('backCroppedData');
            if (!backDataInput.value || backDataInput.value.length < 50) {
                backDataInput.value = cardState.back.croppedCanvas.toDataURL('image/jpeg', 0.92);
            }

            const btn = document.getElementById('submitBtn');
            btn.classList.add('pointer-events-none', 'opacity-75');
            btn.innerHTML = '<span>প্রিন্টারে পাঠানো হচ্ছে...</span>';

            setTimeout(() => {
                btn.disabled = true;
            }, 400);
        });
    </script>
</body>
</html>
