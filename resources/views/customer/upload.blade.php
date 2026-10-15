<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $shop->name }} – সেলফ-সার্ভিস প্রিন্ট পোর্টাল</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('logo.svg') }}">
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- PDF.js for Instant Client-Side Page Count Detection -->
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
                <img src="{{ asset('logo.svg') }}" alt="PrintSheba" class="w-9 h-9 rounded-xl shadow-sm">
                <div>
                    <h1 class="text-sm font-black text-slate-900 leading-tight">{{ $shop->name }}</h1>
                    @if($isAgentOnline)
                        <span class="text-[10px] text-emerald-600 font-bold flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            প্রিন্টার অনলাইন ও প্রস্তুত
                        </span>
                    @else
                        <span class="text-[10px] text-amber-600 font-bold flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                            অটো-প্রিন্টার স্ট্যান্ডবাই (পিসিতে চালু হচ্ছে)
                        </span>
                    @endif
                </div>
            </div>
            <span class="text-[10px] px-2.5 py-0.5 rounded-full bg-slate-100 font-bold text-slate-600 border border-slate-200">
                সেলফ-সার্ভিস
            </span>
        </div>
    </header>

    <div class="max-w-md mx-auto px-4 pt-5 space-y-5">

        @if(session('error_alert'))
            <!-- Flash Error Alert Dialog -->
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-300 text-rose-900 text-xs flex items-start gap-3 shadow-sm animate-bounce">
                <span class="text-xl leading-none">⚠️</span>
                <div class="space-y-1">
                    <strong class="font-bold text-sm text-rose-800 block">পেমেন্ট ভেরিফিকেশন সতর্কতা</strong>
                    <span class="text-xs text-rose-700 leading-relaxed">{{ session('error_alert') }}</span>
                </div>
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'পেমেন্ট ত্রুটি!',
                        text: '{{ session('error_alert') }}',
                        confirmButtonText: 'ঠিক আছে',
                        confirmButtonColor: '#e11d48',
                        background: '#ffffff',
                        customClass: {
                            popup: 'rounded-3xl shadow-2xl border border-slate-200'
                        }
                    });
                });
            </script>
        @endif

        @if(!$isAgentOnline)
            <!-- Offline Warning Alert -->
            <div class="p-3.5 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 text-xs flex items-start gap-2.5 shadow-xs">
                <span class="text-base leading-none">⚠️</span>
                <div>
                    <strong class="font-bold block">দোকানের প্রিন্টারটি বর্তমানে অফলাইন বা বন্ধ রয়েছে</strong>
                    <span class="text-[11px] text-amber-700">আপনি অর্ডার দিলে তা কিউতে জমা থাকবে এবং দোকানদার কম্পিউটার অন করলেই প্রিন্ট বের হবে।</span>
                </div>
            </div>
        @endif

        <!-- Intro Banner -->
        <div class="p-4 rounded-3xl bg-gradient-to-r from-slate-900 via-slate-950 to-slate-900 text-white shadow-lg space-y-1 text-center border border-slate-800">
            <h2 class="text-base font-black">মোবাইল থেকে সরাসরি প্রিন্ট</h2>
            <p class="text-[11px] text-slate-300">ফাইল আপলোড করে পেমেন্ট করলেই প্রিন্টারে পেজ বের হবে</p>
        </div>

        <form action="{{ route('customer.submit', $shop->slug) }}" method="POST" enctype="multipart/form-data" id="printForm" class="space-y-5">
            @csrf

            <!-- STEP 1: FILE UPLOAD -->
            <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-1.5">
                        <span class="w-5 h-5 rounded-full bg-rose-500 text-white text-[11px] font-bold flex items-center justify-center">১</span>
                        <span>ফাইল নির্বাচন করুন</span>
                    </span>
                    <span class="text-[10px] text-slate-400 font-medium">PDF, Word, JPG, PNG</span>
                </div>

                <div class="relative border-2 border-dashed border-slate-300 rounded-2xl p-6 text-center hover:border-rose-400 transition-colors bg-slate-50/50 cursor-pointer">
                    <input type="file" name="document" id="documentInput" required accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                        class="absolute inset-0 opacity-0 cursor-pointer w-full h-full">
                    
                    <div id="uploadPrompt" class="space-y-2 pointer-events-none">
                        <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center mx-auto text-xl shadow-xs">
                            📄
                        </div>
                        <div class="text-xs font-bold text-slate-800">
                            ফাইল সিলেক্ট করতে এখানে চাপুন
                        </div>
                        <div class="text-[10px] text-slate-400">
                            PDF দিলে পেজ সংখ্যা নিজে নিজেই গুনে নেবে
                        </div>
                    </div>

                    <div id="fileInfo" class="hidden space-y-1">
                        <span class="text-sm font-bold text-emerald-600 block truncate" id="fileNameText">Document.pdf</span>
                        <span class="text-[11px] text-slate-500 block" id="fileSizeText"></span>
                        <div id="pageDetectBadge" class="hidden inline-flex items-center gap-1 text-[11px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200 mt-1">
                            <span>✓</span> <span id="detectedPagesText">পেজ সংখ্যা ডিটেক্ট হয়েছে</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- STEP 2: PRINT PREFERENCES -->
            <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm space-y-4">
                <span class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-1.5">
                    <span class="w-5 h-5 rounded-full bg-purple-600 text-white text-[11px] font-bold flex items-center justify-center">২</span>
                    <span>প্রিন্ট সেটিংস</span>
                </span>

                <!-- Mode: B&W vs Color (Only if Shop has Color Print enabled) -->
                @if($shop->has_color_print)
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">কালার মোড:</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="border-2 border-slate-200 rounded-2xl p-3 flex items-center justify-between cursor-pointer has-[:checked]:border-slate-900 has-[:checked]:bg-slate-50 transition-all">
                            <div>
                                <span class="text-xs font-bold text-slate-900 block">সাদাকালো</span>
                                <span class="text-[10px] text-slate-500">৳{{ $shop->rate_bw_single }}/পেজ</span>
                            </div>
                            <input type="radio" name="color_mode" value="bw" checked class="accent-slate-900 rate-trigger">
                        </label>

                        <label class="border-2 border-slate-200 rounded-2xl p-3 flex items-center justify-between cursor-pointer has-[:checked]:border-rose-500 has-[:checked]:bg-rose-50/30 transition-all">
                            <div>
                                <span class="text-xs font-bold text-slate-900 block">রঙিন (Color)</span>
                                <span class="text-[10px] text-rose-600">৳{{ $shop->rate_color_single }}/পেজ</span>
                            </div>
                            <input type="radio" name="color_mode" value="color" class="accent-rose-500 rate-trigger">
                        </label>
                    </div>
                </div>
                @else
                    <input type="hidden" name="color_mode" value="bw">
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-between text-xs">
                        <span class="font-bold text-slate-700">কালার মোড: সাদাকালো লেজার</span>
                        <span class="text-[11px] font-bold text-slate-500">৳{{ $shop->rate_bw_single }}/পেজ</span>
                    </div>
                @endif

                <!-- Page count & copies -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="block text-xs font-bold text-slate-700">মোট পেজ:</label>
                            <span class="text-[9px] text-emerald-600 font-bold" id="autoCountNotice"></span>
                        </div>
                        <input type="number" id="totalPages" name="total_pages" value="1" min="1" max="500" required 
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm font-bold text-center rate-trigger">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">কপি সংখ্যা (Copies):</label>
                        <input type="number" id="copies" name="copies" value="1" min="1" max="100" required 
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm font-bold text-center rate-trigger">
                    </div>
                </div>

                <!-- Duplex / Both side checkbox (Only if Shop has Duplex enabled) -->
                @if($shop->has_duplex_print)
                <label class="flex items-center gap-2.5 p-3 rounded-xl bg-slate-50 border border-slate-200 cursor-pointer">
                    <input type="checkbox" name="is_duplex" id="isDuplex" class="rounded accent-rose-500 rate-trigger">
                    <div class="text-xs">
                        <span class="font-bold text-slate-800">এপিঠ-ওপিঠ (Duplex) প্রিন্ট</span>
                        <span class="text-[10px] text-slate-500 block">একই কাগজের দুই পাশে প্রিন্ট হবে</span>
                    </div>
                </label>
                @else
                    <input type="hidden" name="is_duplex" id="isDuplex" value="0">
                @endif

                <!-- Passport Photo Mode (Only if Shop has Passport Photo enabled) -->
                @if($shop->has_passport_photo)
                <label class="flex items-center gap-2.5 p-3 rounded-xl bg-purple-50/40 border border-purple-200 cursor-pointer">
                    <input type="checkbox" name="paper_size" id="isPassport" value="4x6_passport" class="rounded accent-purple-600 rate-trigger">
                    <div class="text-xs">
                        <span class="font-bold text-purple-900">৪×৬ পাসপোর্ট সাইজ ছবি</span>
                        <span class="text-[10px] text-purple-700 block">ফটো পেপারে ৮ কপি পাসপোর্ট গ্রিড (৳{{ $shop->rate_photo_passport }})</span>
                    </div>
                </label>
                @endif
            </div>

            <!-- STEP 3: LIVE BILL & PAYMENT -->
            <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm space-y-4">
                <span class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-1.5">
                    <span class="w-5 h-5 rounded-full bg-emerald-500 text-white text-[11px] font-bold flex items-center justify-center">৩</span>
                    <span>বিল ও পেমেন্ট</span>
                </span>

                <!-- Total Bill Highlight -->
                <div class="p-4 rounded-2xl bg-slate-900 text-white flex items-center justify-between shadow-inner">
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">সর্বমোট প্রদেয় বিল</span>
                        <span class="text-xs text-slate-300">১০০% ক্যাশলেস প্রিন্ট</span>
                    </div>
                    <div class="text-3xl font-black text-emerald-400 font-en" id="billDisplay">
                        ৳ 3.00
                    </div>
                </div>

                <!-- Payment Methods -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">পেমেন্ট মেথড নির্বাচন করুন:</label>
                    <div class="space-y-2">
                        @if($shop->bkash_number)
                            <label class="border-2 border-slate-200 rounded-2xl p-3 flex items-center justify-between cursor-pointer has-[:checked]:border-pink-500 has-[:checked]:bg-pink-50/20">
                                <div class="flex items-center gap-2.5">
                                    <span class="text-base">💳</span>
                                    <div>
                                        <span class="text-xs font-bold text-slate-900 block">বিকাশ সেন্ড মানি (bKash)</span>
                                        <span class="text-[11px] font-mono text-pink-600 font-bold">{{ $shop->bkash_number }}</span>
                                    </div>
                                </div>
                                <input type="radio" name="payment_method" value="bkash" checked class="accent-pink-500">
                            </label>
                        @endif

                        @if($shop->nagad_number)
                            <label class="border-2 border-slate-200 rounded-2xl p-3 flex items-center justify-between cursor-pointer has-[:checked]:border-amber-500 has-[:checked]:bg-amber-50/20">
                                <div class="flex items-center gap-2.5">
                                    <span class="text-base">💳</span>
                                    <div>
                                        <span class="text-xs font-bold text-slate-900 block">নগদ সেন্ড মানি (Nagad)</span>
                                        <span class="text-[11px] font-mono text-amber-600 font-bold">{{ $shop->nagad_number }}</span>
                                    </div>
                                </div>
                                <input type="radio" name="payment_method" value="nagad" class="accent-amber-500">
                            </label>
                        @endif

                        <label class="border-2 border-slate-200 rounded-2xl p-3 flex items-center justify-between cursor-pointer has-[:checked]:border-slate-800 has-[:checked]:bg-slate-50">
                            <div class="flex items-center gap-2.5">
                                <span class="text-base">💵</span>
                                <div>
                                    <span class="text-xs font-bold text-slate-900 block">কাউন্টারে নগদ ক্যাশ দেব</span>
                                    <span class="text-[10px] text-slate-500">দোকানদার ভেরিফাই করলে প্রিন্ট হবে</span>
                                </div>
                            </div>
                            <input type="radio" name="payment_method" value="cash" class="accent-slate-800">
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">বিকাশ / নগদ TrxID (যদি সেন্ড করে থাকেন):</label>
                    <input type="text" name="payment_trx_id" 
                        class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-mono uppercase focus:outline-none focus:border-rose-500"
                        placeholder="যেমন: BKH92810X">
                </div>
            </div>

            <!-- SUBMIT BUTTON -->
            <button type="submit" class="w-full py-4 rounded-2xl bg-gradient-to-r from-rose-500 via-pink-500 to-rose-600 hover:from-rose-600 hover:to-pink-600 text-white font-black text-sm shadow-xl shadow-rose-500/30 transition-all active:scale-95 flex items-center justify-center gap-2 cursor-pointer">
                <span>🖨️ প্রিন্ট অর্ডার পাঠান →</span>
            </button>
        </form>

        <div class="text-center text-[10px] text-slate-400">
            🔒 ১০০% প্রাইভেসি: প্রিন্ট হওয়া মাত্রই ফাইল পার্মানেন্টলি ডিলিট হয়ে যায়
        </div>
    </div>

    <!-- Live Calculator & PDF Page Auto-Counter Script -->
    <script>
        const rateBwSingle = {{ $shop->rate_bw_single }};
        const rateBwDouble = {{ $shop->rate_bw_double }};
        const rateColorSingle = {{ $shop->rate_color_single }};
        const rateColorDouble = {{ $shop->rate_color_double }};
        const ratePassport = {{ $shop->rate_photo_passport }};

        function calculateBill() {
            const pages = parseInt(document.getElementById('totalPages')?.value) || 1;
            const copies = parseInt(document.getElementById('copies')?.value) || 1;
            const duplexElem = document.getElementById('isDuplex');
            const isDuplex = duplexElem ? (duplexElem.type === 'checkbox' ? duplexElem.checked : false) : false;
            const passportElem = document.getElementById('isPassport');
            const isPassport = passportElem ? passportElem.checked : false;
            const colorMode = document.querySelector('input[name="color_mode"]:checked')?.value || document.querySelector('input[name="color_mode"]')?.value || 'bw';

            let total = 0;
            if (isPassport) {
                total = ratePassport * copies;
            } else {
                let unit = 0;
                if (colorMode === 'bw') {
                    unit = isDuplex ? (rateBwDouble / 2) : rateBwSingle;
                } else {
                    unit = isDuplex ? (rateColorDouble / 2) : rateColorSingle;
                }
                total = pages * unit * copies;
            }

            document.getElementById('billDisplay').innerText = '৳ ' + total.toFixed(2);
        }

        document.querySelectorAll('.rate-trigger').forEach(el => {
            el.addEventListener('input', calculateBill);
            el.addEventListener('change', calculateBill);
        });

        // File upload UI change & Automatic Page Counting
        const fileInput = document.getElementById('documentInput');
        fileInput.addEventListener('change', async (e) => {
            const file = e.target.files[0];
            if (file) {
                document.getElementById('uploadPrompt').classList.add('hidden');
                document.getElementById('fileInfo').classList.remove('hidden');
                document.getElementById('fileNameText').innerText = file.name;
                document.getElementById('fileSizeText').innerText = (file.size / (1024 * 1024)).toFixed(2) + ' MB';

                // Check if file is PDF for client-side auto counting
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
                                document.getElementById('detectedPagesText').innerText = `স্বয়ংক্রিয়ভাবে মোট ${numPages} পেজ ডিটেক্ট হয়েছে`;
                                document.getElementById('autoCountNotice').innerText = `(অটো-কাউন্ট: ${numPages})`;
                                calculateBill();
                            }
                        };
                        fileReader.readAsArrayBuffer(file);
                    } catch (err) {
                        console.log('PDF Page count fallback:', err);
                    }
                } else {
                    // For single image or others default to 1
                    document.getElementById('pageDetectBadge').classList.add('hidden');
                    document.getElementById('autoCountNotice').innerText = '';
                }
            }
        });

        calculateBill();
    </script>
</body>
</html>
