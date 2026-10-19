<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $shop->name }} – অফিস ইন্টারনাল প্রিন্ট পোর্টাল</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('logo.svg') }}">
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
    </script>
    <style>
        body {
            font-family: 'Hind Siliguri', sans-serif;
            background-color: #f8fafc;
        }
        .font-en { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-slate-50 text-slate-800 pb-16">

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
                            অফিস প্রিন্টার অনলাইন ও সংযুক্ত
                        </span>
                    @else
                        <span class="text-[10px] text-amber-600 font-bold flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                            প্রিন্ট সার্ভার স্ট্যান্ডবাই
                        </span>
                    @endif
                </div>
            </div>
            <span class="text-[10px] px-2.5 py-1 rounded-full bg-indigo-50 font-black text-indigo-700 border border-indigo-200 flex items-center gap-1">
                <span>🏢 অফিস মোড</span>
            </span>
        </div>
    </header>

    <div class="max-w-md mx-auto px-4 pt-4 space-y-4">

        <!-- Navigation Tabs (Office Document vs Office NID) -->
        <div class="grid grid-cols-2 p-1 bg-slate-200/80 rounded-2xl gap-1 text-xs font-bold text-center">
            <div class="py-2.5 rounded-xl bg-white text-indigo-900 shadow-sm flex items-center justify-center gap-1.5 font-black border border-indigo-200">
                <span>📄 ডকুমেন্ট প্রিন্ট</span>
            </div>
            <a href="{{ route('customer.office_nid_upload', $shop->slug) }}" class="py-2.5 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-white/60 transition-all flex items-center justify-center gap-1.5">
                <span>🪪 এনআইডি প্রিন্ট (AI)</span>
            </a>
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

        <!-- Intro Banner -->
        <div class="p-4 rounded-3xl bg-gradient-to-r from-indigo-950 via-slate-900 to-indigo-900 text-white shadow-lg space-y-1.5 border border-indigo-800/40 relative overflow-hidden">
            <div class="flex items-center justify-between">
                <span class="px-2 py-0.5 rounded-md bg-indigo-500/20 text-indigo-300 text-[10px] font-bold border border-indigo-500/30">জিরো পেমেন্ট • ১০০% ফ্রি</span>
                <span class="text-[10px] text-emerald-400 font-bold flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> ইনস্ট্যান্ট অটো-প্রিন্ট
                </span>
            </div>
            <h2 class="text-base font-black">অফিস সেন্ট্রাল প্রিন্ট নেটওয়ার্ক</h2>
            <p class="text-[11px] text-slate-300 leading-relaxed">
                অফিসের যেকোনো কম্পিউটার বা মোবাইল থেকে ফাইল আপলোড করে প্রিন্ট দিন। কোনো বিল বা পেমেন্ট ছাড়াই সরাসরি প্রিন্টার থেকে পেজ বের হবে।
            </p>
        </div>

        <form action="{{ route('customer.office_submit', $shop->slug) }}" method="POST" enctype="multipart/form-data" id="officePrintForm" class="space-y-4">
            @csrf

            <!-- STEP 1: FILE UPLOAD -->
            <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-1.5">
                        <span class="w-5 h-5 rounded-full bg-indigo-600 text-white text-[11px] font-bold flex items-center justify-center">১</span>
                        <span>ফাইল নির্বাচন করুন</span>
                    </span>
                    <span class="text-[10px] text-slate-400 font-medium font-en">PDF, Word, JPG, PNG</span>
                </div>

                <div class="relative border-2 border-dashed border-slate-300 rounded-2xl p-6 text-center hover:border-indigo-400 transition-colors bg-slate-50/50 cursor-pointer">
                    <input type="file" name="document" id="documentInput" required accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                        class="absolute inset-0 opacity-0 cursor-pointer w-full h-full">
                    
                    <div id="uploadPrompt" class="space-y-2 pointer-events-none">
                        <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto text-xl shadow-xs">
                            📄
                        </div>
                        <div class="text-xs font-bold text-slate-800">
                            ফাইল সিলেক্ট করতে এখানে ক্লিক করুন
                        </div>
                        <div class="text-[10px] text-slate-400">
                            PDF দিলে পেজ সংখ্যা স্বয়ংক্রিয়ভাবে ডিটেক্ট হবে
                        </div>
                    </div>

                    <div id="fileInfo" class="hidden space-y-1">
                        <span class="text-sm font-bold text-indigo-600 block truncate" id="fileNameText">Document.pdf</span>
                        <span class="text-[11px] text-slate-500 block" id="fileSizeText"></span>
                        <div id="pageDetectBadge" class="hidden inline-flex items-center gap-1 text-[11px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200 mt-1">
                            <span>✓</span> <span id="detectedPagesText">পেজ সংখ্যা ডিটেক্ট হয়েছে</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- STEP 2: EMPLOYEE & REFERENCE IDENTIFIERS -->
            <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm space-y-3">
                <span class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-1.5">
                    <span class="w-5 h-5 rounded-full bg-blue-600 text-white text-[11px] font-bold flex items-center justify-center">২</span>
                    <span>কর্মকর্তা ও রেফারেন্স পরিচিতি</span>
                </span>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">আপনার নাম / পদবী:</label>
                        <input type="text" name="employee_name" 
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-900 focus:outline-none focus:border-indigo-500"
                            placeholder="যেমন: আরিফুল ইসলাম (অ্যাকাউন্টিং)">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">পিসি / রেফারেন্স নং:</label>
                        <input type="text" name="pickup_reference" 
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-900 focus:outline-none focus:border-indigo-500"
                            placeholder="যেমন: পিসি-৩ / মার্চ বিল">
                    </div>
                </div>
                <span class="text-[10px] text-slate-400 block">
                    প্রিন্টারে কার পেজ বের হচ্ছে তা সহজে শনাক্ত করার জন্য নাম ও রেফারেন্স ব্যবহৃত হবে।
                </span>
            </div>

            <!-- STEP 3: PRINT PREFERENCES -->
            <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm space-y-4">
                <span class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-1.5">
                    <span class="w-5 h-5 rounded-full bg-purple-600 text-white text-[11px] font-bold flex items-center justify-center">৩</span>
                    <span>প্রিন্ট সেটিংস</span>
                </span>

                <!-- Mode: B&W vs Color -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">কালার মোড:</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="border-2 border-slate-200 rounded-2xl p-3 flex items-center justify-between cursor-pointer has-[:checked]:border-slate-900 has-[:checked]:bg-slate-50 transition-all">
                            <div>
                                <span class="text-xs font-bold text-slate-900 block">⚪⚫ সাদাকালো</span>
                                <span class="text-[10px] text-slate-500">লেজার প্রিন্টার</span>
                            </div>
                            <input type="radio" name="color_mode" value="bw" checked class="accent-slate-900">
                        </label>

                        @if($shop->has_color_print)
                        <label class="border-2 border-slate-200 rounded-2xl p-3 flex items-center justify-between cursor-pointer has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50/30 transition-all">
                            <div>
                                <span class="text-xs font-bold text-slate-900 block">🎨 রঙিন (Color)</span>
                                <span class="text-[10px] text-indigo-600 font-bold">কালার ইঙ্কজেট/লেজার</span>
                            </div>
                            <input type="radio" name="color_mode" value="color" class="accent-indigo-600">
                        </label>
                        @endif
                    </div>
                </div>

                <!-- Page count & copies -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="block text-xs font-bold text-slate-700">মোট পেজ:</label>
                            <span class="text-[9px] text-emerald-600 font-bold" id="autoCountNotice"></span>
                        </div>
                        <input type="number" id="totalPages" name="total_pages" value="1" min="1" max="500" required 
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm font-bold text-center">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">কপি সংখ্যা (Copies):</label>
                        <input type="number" id="copies" name="copies" value="1" min="1" max="100" required 
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm font-bold text-center">
                    </div>
                </div>

                <!-- Duplex / Both side checkbox -->
                @if($shop->has_duplex_print)
                <label class="flex items-center gap-2.5 p-3 rounded-xl bg-slate-50 border border-slate-200 cursor-pointer">
                    <input type="checkbox" name="is_duplex" id="isDuplex" class="rounded accent-indigo-600">
                    <div class="text-xs">
                        <span class="font-bold text-slate-800">এপিঠ-ওপিঠ (Duplex) প্রিন্ট</span>
                        <span class="text-[10px] text-slate-500 block">একই কাগজের দুই পাশে প্রিন্ট হবে</span>
                    </div>
                </label>
                @endif
            </div>

            <!-- SUBMIT BUTTON -->
            <button type="submit" id="officeSubmitBtn" class="w-full py-4 rounded-2xl bg-gradient-to-r from-indigo-600 via-blue-600 to-indigo-700 hover:from-indigo-700 hover:to-blue-700 text-white font-black text-sm shadow-xl shadow-indigo-600/30 transition-all active:scale-95 flex items-center justify-center gap-2 cursor-pointer">
                <span>🖨️ সেন্ট্রাল প্রিন্টারে সরাসরি প্রিন্ট করুন →</span>
            </button>
        </form>

        <div class="text-center text-[10px] text-slate-400">
            🏢 অফিস অভ্যন্তরীণ নেটওয়ার্ক প্রিন্টিং সিস্টেম • প্রিন্ট হওয়ার পর অটো-ডিলিট হয়
        </div>
    </div>

    <script>
        const fileInput = document.getElementById('documentInput');
        fileInput.addEventListener('change', async (e) => {
            const file = e.target.files[0];
            if (file) {
                document.getElementById('uploadPrompt').classList.add('hidden');
                document.getElementById('fileInfo').classList.remove('hidden');
                document.getElementById('fileNameText').innerText = file.name;
                document.getElementById('fileSizeText').innerText = (file.size / (1024 * 1024)).toFixed(2) + ' MB';

                if (file.type === 'application/pdf' || file.name.toLowerCase().endsWith('.pdf')) {
                    try {
                        const fileReader = new FileReader();
                        fileReader.onload = async function() {
                            const typedarray = new Uint8Array(this.result);
                            const pdf = await pdfjsLib.getDocument(typedarray).promise;
                            const numPages = pdf.numPages;
                            if (numPages > 0) {
                                document.getElementById('totalPages').value = numPages;
                                document.getElementById('pageDetectBadge').classList.remove('hidden');
                                document.getElementById('detectedPagesText').innerText = `মোট ${numPages} পেজ ডিটেক্ট হয়েছে`;
                                document.getElementById('autoCountNotice').innerText = `(${numPages} পেজ)`;
                            }
                        };
                        fileReader.readAsArrayBuffer(file);
                    } catch (err) {}
                }
            }
        });

        document.getElementById('officePrintForm').addEventListener('submit', function() {
            const btn = document.getElementById('officeSubmitBtn');
            btn.disabled = true;
            btn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                <span>প্রিন্টারে পাঠানো হচ্ছে...</span>
            `;
        });
    </script>
</body>
</html>
