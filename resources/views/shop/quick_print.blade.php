@extends('layouts.shop')

@section('title', 'ইনস্ট্যান্ট ও হোয়াটসঅ্যাপ শেয়ার প্রিন্ট – ' . $shop->name)

@section('content')
<div class="space-y-6 max-w-6xl mx-auto">

    <!-- Top Alert Messages -->
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-500/15 border border-emerald-500/30 text-emerald-300 flex items-center justify-between shadow-lg shadow-emerald-500/10 animate-fade-in">
            <div class="flex items-center gap-3">
                <span class="text-2xl">🎉</span>
                <p class="text-sm font-semibold">{{ session('success') }}</p>
            </div>
            <a href="{{ route('shop.dashboard') }}" class="px-3 py-1.5 rounded-xl bg-emerald-500 text-slate-950 font-bold text-xs hover:bg-emerald-400 transition-colors">
                ড্যাশবোর্ড দেখুন →
            </a>
        </div>
    @endif

    @if(session('error_alert') || session('error'))
        <div class="p-4 rounded-2xl bg-rose-500/15 border border-rose-500/30 text-rose-300 flex items-center gap-3 shadow-lg shadow-rose-500/10">
            <span class="text-2xl">⚠️</span>
            <p class="text-sm font-semibold">{{ session('error_alert') ?? session('error') }}</p>
        </div>
    @endif

    @if(session('warning_alert'))
        <div class="p-4 rounded-2xl bg-amber-500/15 border border-amber-500/30 text-amber-300 flex items-center gap-3 shadow-lg shadow-amber-500/10">
            <span class="text-2xl">ℹ️</span>
            <p class="text-sm font-semibold">{{ session('warning_alert') }}</p>
        </div>
    @endif

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-gradient-to-r from-slate-900 via-slate-950 to-slate-900 border border-slate-800 p-6 rounded-3xl relative overflow-hidden shadow-2xl">
        <div class="absolute -top-16 -left-16 w-56 h-56 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-16 -right-16 w-56 h-56 bg-teal-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 space-y-1">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/15 text-emerald-400 border border-emerald-500/30 text-xs font-bold">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>দোকানদার ডিরেক্ট প্রিন্ট স্টেশন</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-white flex items-center gap-2.5">
                <span>⚡ ইনস্ট্যান্ট ও শেয়ার প্রিন্ট</span>
            </h1>
            <p class="text-xs sm:text-sm text-slate-400">
                হোয়াটসঅ্যাপ, গ্যালারি থেকে সরাসরি শেয়ার করুন অথবা ফাইল সিলেক্ট করে ১-ক্লিকে প্রিন্ট দিন
            </p>
        </div>

        <div class="relative z-10 flex items-center gap-2.5 shrink-0">
            <a href="{{ route('shop.dashboard') }}" class="px-4 py-2.5 rounded-2xl bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 font-bold text-xs transition-all flex items-center gap-2">
                <span>← ড্যাশবোর্ডে ফিরুন</span>
            </a>
        </div>
    </div>

    <!-- Main Grid: Left = Preview / Upload, Right = Print Controls -->
    <form id="quickPrintForm" action="{{ route('shop.quick_print.submit') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="scanned_image_data" id="scannedImageDataInput">

        @if($fileData)
            <input type="hidden" name="temp_file" value="{{ $fileData['temp_file'] }}">
            <input type="hidden" name="original_name" value="{{ $fileData['original_name'] }}">
            <input type="hidden" name="detected_pages" value="{{ $fileData['total_pages'] }}">
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            
            <!-- LEFT COLUMN (col-span-7): File Preview & Details -->
            <div class="lg:col-span-7 space-y-4">
                
                @if($fileData)
                    <!-- Active Shared File Card -->
                    <div class="bg-slate-900 border border-slate-800 rounded-3xl p-5 shadow-xl space-y-4 relative overflow-hidden">
                        
                        <div class="flex items-center justify-between border-b border-slate-800/80 pb-3">
                            <div class="flex items-center gap-3 overflow-hidden">
                                <div class="w-10 h-10 rounded-2xl bg-emerald-500/15 border border-emerald-500/30 flex items-center justify-center text-xl shrink-0">
                                    @if($fileData['is_pdf']) 📑
                                    @elseif($fileData['is_image']) 🖼️
                                    @else 📄 @endif
                                </div>
                                <div class="truncate">
                                    <h3 class="text-sm font-bold text-white truncate" title="{{ $fileData['original_name'] }}">
                                        {{ $fileData['original_name'] }}
                                    </h3>
                                    <div class="flex items-center gap-2 text-[11px] text-slate-400 pt-0.5">
                                        <span class="uppercase font-mono font-bold text-emerald-400 bg-emerald-500/10 px-2 py-0.5 rounded">{{ $fileData['extension'] }}</span>
                                        <span>•</span>
                                        <span>{{ $fileData['size_formatted'] }}</span>
                                        @if($fileData['is_pdf'])
                                            <span>•</span>
                                            <span class="text-teal-300 font-bold">{{ $fileData['total_pages'] }} পৃষ্ঠা</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Remove / Change File Button -->
                            <button type="button" onclick="document.getElementById('cancelTempForm').submit();" class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-rose-500/20 text-slate-400 hover:text-rose-400 border border-slate-700 hover:border-rose-500/30 text-xs font-semibold transition-all flex items-center gap-1.5 shrink-0" title="ফাইল পরিবর্তন করুন">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                <span>মুছুন</span>
                            </button>
                        </div>

                        <!-- Live Visual Preview (Mobile & Desktop Optimized) -->
                        <div class="rounded-2xl bg-slate-950 border border-slate-800/80 p-2 flex items-center justify-center min-h-[300px] max-h-[500px] overflow-hidden relative">
                            @if($fileData['is_image'])
                                <div class="w-full flex flex-col items-center justify-center">
                                    <img id="sharedPreviewImage" src="{{ $fileData['url'] }}" alt="Shared Document Preview" class="max-h-[440px] max-w-full rounded-xl object-contain shadow-md" onerror="this.onerror=null; this.src='{{ asset('icon-192.png') }}';">
                                    <div class="pt-2 w-full flex flex-wrap items-center justify-between text-xs text-slate-400 px-1 gap-2">
                                        <button type="button" onclick="openDocScannerModal(document.getElementById('sharedPreviewImage').src)" class="px-3 py-1.5 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold text-xs flex items-center gap-1.5 shadow transition-all cursor-pointer">
                                            <span>📐 কর্নার ডিটেক্ট ও সোজা করুন (Doc Scanner)</span>
                                        </button>
                                        <a href="{{ $fileData['url'] }}" target="_blank" class="text-emerald-400 hover:underline flex items-center gap-1 font-bold">
                                            ফুলস্ক্রিন ভিউ ↗
                                        </a>
                                    </div>
                                    <div id="sharedScanBadge" class="hidden mt-2 inline-flex items-center gap-1 text-[11px] font-bold text-emerald-400 bg-emerald-500/10 px-2.5 py-1 rounded-xl border border-emerald-500/20">
                                        <span>✓</span> <span>A4 সোজা ও হোয়াইট ফিল্টার প্রয়োগ করা হয়েছে</span>
                                    </div>
                                </div>
                            @elseif($fileData['is_pdf'])
                                <div class="w-full flex flex-col items-center">
                                    <!-- PDF Loading indicator -->
                                    <div id="pdfLoadingIndicator" class="flex flex-col items-center justify-center py-12 gap-2 text-emerald-400">
                                        <svg class="animate-spin h-7 w-7 text-emerald-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span class="text-xs text-slate-300 font-medium">পিডিএফ প্রিভিউ লোড হচ্ছে...</span>
                                    </div>

                                    <!-- PDF Canvas for Android Chrome, iOS & Desktop -->
                                    <div id="pdfCanvasContainer" class="w-full overflow-auto max-h-[420px] flex justify-center hidden">
                                        <canvas id="pdfCanvas" class="rounded-xl shadow-lg border border-slate-700 max-w-full"></canvas>
                                    </div>

                                    <!-- Fallback iframe for desktop if PDF.js is unavailable -->
                                    <iframe id="pdfFallbackIframe" src="{{ $fileData['url'] }}#toolbar=0" class="hidden w-full h-[400px] rounded-xl bg-white border border-slate-700"></iframe>

                                    <!-- Page navigation controls & Fullscreen view -->
                                    <div class="pt-2.5 w-full flex items-center justify-between text-xs text-slate-400 px-1 border-t border-slate-800/80 mt-2">
                                        <div class="flex items-center gap-1.5" id="pdfControls" style="display: none;">
                                            <button type="button" id="pdfPrevBtn" onclick="renderPdfPage(currentPdfPage - 1)" class="px-2.5 py-1 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 font-bold transition-all disabled:opacity-40 disabled:cursor-not-allowed">◀ আগের</button>
                                            <span class="text-xs font-mono font-bold text-slate-300 px-1">
                                                পেজ <span id="pdfCurrentPageNum" class="text-emerald-400 font-bold">1</span> / <span id="pdfTotalPagesNum">{{ $fileData['total_pages'] }}</span>
                                            </span>
                                            <button type="button" id="pdfNextBtn" onclick="renderPdfPage(currentPdfPage + 1)" class="px-2.5 py-1 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 font-bold transition-all disabled:opacity-40 disabled:cursor-not-allowed">পরের ▶</button>
                                        </div>
                                        <div class="ml-auto">
                                            <a href="{{ $fileData['url'] }}" target="_blank" class="px-2.5 py-1 rounded-lg bg-slate-800 hover:bg-slate-700 text-emerald-400 hover:text-emerald-300 font-bold border border-slate-700 flex items-center gap-1 transition-all">
                                                <span>ফুলস্ক্রিন ভিউ</span>
                                                <span>↗</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-12 space-y-3">
                                    <span class="text-5xl">📄</span>
                                    <p class="text-sm font-semibold text-slate-300">ডকুমেন্টটি প্রিন্টের জন্য প্রস্তুত</p>
                                    <a href="{{ $fileData['url'] }}" target="_blank" class="inline-flex px-3 py-1.5 rounded-xl bg-slate-800 text-xs text-slate-300 hover:text-white border border-slate-700">
                                        ফাইলটি ওপেন করে দেখুন ↗
                                    </a>
                                </div>
                            @endif
                        </div>

                    </div>
                @else
                    <!-- File Upload Dropzone (When accessed directly without share) -->
                    <div class="bg-slate-900 border-2 border-dashed border-slate-700 hover:border-emerald-500/70 rounded-3xl p-6 sm:p-8 text-center transition-all group relative cursor-pointer" id="dropzoneContainer">
                        <input type="file" id="documentInput" name="document" accept=".pdf,image/*,.doc,.docx" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20" onchange="handleDirectFileSelect(this)">
                        
                        <div class="space-y-4 pointer-events-none" id="dropzonePrompt">
                            <div class="w-16 h-16 rounded-3xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center text-3xl mx-auto group-hover:scale-110 transition-transform shadow-lg shadow-emerald-500/10">
                                📤
                            </div>
                            <div class="space-y-1">
                                <h3 class="text-base sm:text-lg font-bold text-white">প্রিন্ট করার ফাইল ড্রপ করুন অথবা ক্লিক করুন</h3>
                                <p class="text-xs text-slate-400 max-w-md mx-auto">
                                    PDF, JPG, PNG, WEBP বা Word ডকুমেন্ট সাপোর্ট করে (সর্বোচ্চ ৫০ MB)
                                </p>
                            </div>
                            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-800/80 border border-slate-700 text-slate-300 text-xs font-semibold">
                                <span>📁 ফাইল বেছে নিন</span>
                            </div>
                        </div>

                        <!-- Dynamic Instant Client Preview after local selection -->
                        <div id="clientPreviewWrapper" class="hidden space-y-3 pointer-events-auto">
                            <div class="flex items-center justify-between border-b border-slate-800 pb-2">
                                <div class="flex items-center gap-2 overflow-hidden text-left">
                                    <span class="text-xl" id="clientFileIcon">📄</span>
                                    <div class="truncate">
                                        <p class="text-xs font-bold text-white truncate" id="clientFileName">filename.pdf</p>
                                        <p class="text-[10px] text-slate-400" id="clientFileSize">0 KB</p>
                                    </div>
                                </div>
                                <button type="button" onclick="clearDirectFile()" class="p-1.5 rounded-lg bg-rose-500/20 text-rose-300 text-xs hover:bg-rose-500/30">
                                    ✕ পরিবর্তন
                                </button>
                            </div>

                            <div class="rounded-xl bg-slate-950 p-2 flex items-center justify-center min-h-[260px] max-h-[380px] overflow-hidden" id="clientPreviewContent">
                                <!-- Image or PDF frame injected via JS -->
                            </div>
                            <div id="directDocScannerBtnContainer" class="hidden pt-1">
                                <button type="button" onclick="openDocScannerModal(currentDirectImageDataUrl)" class="w-full py-2 px-3 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold text-xs flex items-center justify-center gap-1.5 shadow transition-all cursor-pointer">
                                    <span>📐 কর্নার ডিটেক্ট ও ডকুমেন্ট সোজা করুন (Doc Scanner)</span>
                                </button>
                                <div id="directScanBadge" class="hidden mt-2 inline-flex items-center gap-1 text-[11px] font-bold text-emerald-400 bg-emerald-500/10 px-2.5 py-1 rounded-xl border border-emerald-500/20">
                                    <span>✓</span> <span>A4 সোজা ও হোয়াইট ফিল্টার প্রয়োগ করা হয়েছে</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- PWA Tips & How Share Works Guide Card -->
                <div class="p-5 rounded-3xl bg-gradient-to-br from-slate-900 to-slate-950 border border-slate-800/80 space-y-3">
                    <div class="flex items-center gap-2 text-xs font-bold text-emerald-400">
                        <span>💡</span>
                        <span>মোবাইলে WhatsApp / গ্যালারি থেকে সরাসরি প্রিন্ট দেওয়ার নিয়ম:</span>
                    </div>
                    <ol class="text-xs text-slate-400 space-y-2 list-decimal list-inside leading-relaxed">
                        <li>WhatsApp বা গ্যালারিতে যেকোনো ছবি বা PDF ফাইল ওপেন করুন।</li>
                        <li>শেয়ার (<span class="font-en font-semibold text-slate-200">Share</span>) বাটনে ক্লিক করুন।</li>
                        <li>অ্যাপ লিস্ট থেকে <strong class="text-rose-400">PrintSheba</strong> সিলেক্ট করুন।</li>
                        <li>সরাসরি এই পেজটি ফাইলসহ ওপেন হবে — শুধু কপি ও কালার দিয়ে প্রিন্ট দিন!</li>
                    </ol>
                </div>

            </div>

            <!-- RIGHT COLUMN (col-span-5): Print Options & Action Button -->
            <div class="lg:col-span-5 space-y-5">
                <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-2xl space-y-5">
                    
                    <div class="border-b border-slate-800 pb-3 flex items-center justify-between">
                        <h2 class="text-base font-black text-white flex items-center gap-2">
                            <span>⚙️ প্রিন্ট কনফিগারেশন</span>
                        </h2>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                            অটো ভেরিফায়েড
                        </span>
                    </div>

                    <!-- 1. Color Mode (কালার মোড) -->
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-300">১. কালার মোড নির্বাচন করুন *</label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="cursor-pointer">
                                <input type="radio" name="color_mode" value="bw" class="peer sr-only" checked>
                                <div class="p-3.5 rounded-2xl bg-slate-950 border-2 border-slate-800 peer-checked:border-emerald-500 peer-checked:bg-emerald-500/10 hover:border-slate-700 transition-all text-center space-y-1">
                                    <div class="text-xl">⚫</div>
                                    <div class="text-xs font-bold text-white">সাদা-কালো (B&W)</div>
                                    <div class="text-[10px] text-slate-400 font-en">Mono / Grayscale</div>
                                </div>
                            </label>

                            <label class="cursor-pointer">
                                <input type="radio" name="color_mode" value="color" class="peer sr-only">
                                <div class="p-3.5 rounded-2xl bg-slate-950 border-2 border-slate-800 peer-checked:border-rose-500 peer-checked:bg-rose-500/10 hover:border-slate-700 transition-all text-center space-y-1">
                                    <div class="text-xl">🎨</div>
                                    <div class="text-xs font-bold text-white">রঙিন (Color)</div>
                                    <div class="text-[10px] text-slate-400 font-en">Full Color RGB</div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- 2. Copies (কপি সংখ্যা) -->
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-300">২. প্রিন্ট কপি সংখ্যা *</label>
                        <div class="flex items-center gap-3">
                            <button type="button" onclick="adjustCopies(-1)" class="w-11 h-11 rounded-2xl bg-slate-800 hover:bg-slate-700 active:scale-95 text-white font-black text-lg flex items-center justify-center border border-slate-700 transition-all">
                                −
                            </button>
                            <input type="number" id="copiesInput" name="copies" value="1" min="1" max="100" class="flex-1 bg-slate-950 border border-slate-800 rounded-2xl py-2.5 text-center text-lg font-black text-white font-en focus:outline-none focus:border-emerald-500">
                            <button type="button" onclick="adjustCopies(1)" class="w-11 h-11 rounded-2xl bg-slate-800 hover:bg-slate-700 active:scale-95 text-white font-black text-lg flex items-center justify-center border border-slate-700 transition-all">
                                +
                            </button>
                        </div>
                        <div class="flex items-center gap-2 pt-1">
                            <button type="button" onclick="setCopies(1)" class="px-2.5 py-1 rounded-lg bg-slate-800 hover:bg-slate-700 text-[11px] text-slate-300 font-bold">১ কপি</button>
                            <button type="button" onclick="setCopies(2)" class="px-2.5 py-1 rounded-lg bg-slate-800 hover:bg-slate-700 text-[11px] text-slate-300 font-bold">২ কপি</button>
                            <button type="button" onclick="setCopies(5)" class="px-2.5 py-1 rounded-lg bg-slate-800 hover:bg-slate-700 text-[11px] text-slate-300 font-bold">৫ কপি</button>
                            <button type="button" onclick="setCopies(10)" class="px-2.5 py-1 rounded-lg bg-slate-800 hover:bg-slate-700 text-[11px] text-slate-300 font-bold">১০ কপি</button>
                        </div>
                    </div>

                    <!-- 3. Duplex / উভয় পিঠ প্রিন্ট -->
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-300">৩. উভয় পিঠ (Duplex) প্রিন্ট</label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="cursor-pointer">
                                <input type="radio" name="is_duplex" value="0" class="peer sr-only" checked>
                                <div class="p-2.5 rounded-2xl bg-slate-950 border-2 border-slate-800 peer-checked:border-emerald-500 peer-checked:bg-emerald-500/10 hover:border-slate-700 transition-all text-center">
                                    <div class="text-xs font-bold text-white">📄 এক পিঠ (Single)</div>
                                </div>
                            </label>

                            <label class="cursor-pointer">
                                <input type="radio" name="is_duplex" value="1" class="peer sr-only">
                                <div class="p-2.5 rounded-2xl bg-slate-950 border-2 border-slate-800 peer-checked:border-emerald-500 peer-checked:bg-emerald-500/10 hover:border-slate-700 transition-all text-center">
                                    <div class="text-xs font-bold text-white">📑 উভয় পিঠ (Double)</div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- 4. Paper Size (কাগজের সাইজ) -->
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-300">৪. কাগজের সাইজ</label>
                        <select name="paper_size" class="w-full bg-slate-950 border border-slate-800 rounded-2xl px-3.5 py-2.5 text-xs font-bold text-white focus:outline-none focus:border-emerald-500 font-en">
                            <option value="A4" selected>A4 (স্ট্যান্ডার্ড কাগজ)</option>
                            <option value="Legal">Legal (দলিল / স্ট্যাম্প)</option>
                            <option value="Letter">Letter</option>
                        </select>
                    </div>

                    <!-- 5. Page Range (পৃষ্ঠার পরিসীমা) -->
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-300">৫. পৃষ্ঠার পরিসীমা (ঐচ্ছিক)</label>
                        <input type="text" name="page_range" placeholder="সব পৃষ্ঠা (অথবা যেমন: 1-3, 5)" class="w-full bg-slate-950 border border-slate-800 rounded-2xl px-3.5 py-2.5 text-xs text-white placeholder-slate-500 font-en focus:outline-none focus:border-emerald-500">
                        <p class="text-[10px] text-slate-500">খালি রাখলে সম্পূর্ণ ডকুমেন্ট প্রিন্ট হবে</p>
                    </div>

                    <!-- 6. Printer Selection (প্রিন্টার নির্বাচন) -->
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-300">৬. প্রিন্টার নির্বাচন করুন</label>
                        <select name="printer_id" class="w-full bg-slate-950 border border-slate-800 rounded-2xl px-3.5 py-2.5 text-xs font-bold text-white focus:outline-none focus:border-emerald-500">
                            @forelse($printers as $printer)
                                <option value="{{ $printer->id }}" {{ $loop->first ? 'selected' : '' }}>
                                    🖨️ {{ $printer->name }} ({{ $printer->model ?? 'Default' }})
                                </option>
                            @empty
                                <option value="">ডিফল্ট প্রিন্টার (উইন্ডোজ এজেন্ট ডিফল্ট)</option>
                            @endforelse
                        </select>
                    </div>

                    <!-- 7. Customer / Quick Tag -->
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-300">৭. কাস্টমার / রেফারেন্স নোট (ঐচ্ছিক)</label>
                        <input type="text" id="customerLabelInput" name="customer_label" value="হোয়াটসঅ্যাপ শেয়ার" placeholder="কাস্টমারের নাম বা ট্যাগ..." class="w-full bg-slate-950 border border-slate-800 rounded-2xl px-3.5 py-2.5 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500">
                        <div class="flex flex-wrap gap-1.5 pt-1">
                            <button type="button" onclick="setLabel('হোয়াটসঅ্যাপ')" class="px-2 py-0.5 rounded-md bg-slate-800 hover:bg-slate-700 text-[10px] text-slate-300">WhatsApp</button>
                            <button type="button" onclick="setLabel('গ্যালারি ফটো')" class="px-2 py-0.5 rounded-md bg-slate-800 hover:bg-slate-700 text-[10px] text-slate-300">Gallery</button>
                            <button type="button" onclick="setLabel('কাউন্টার ওয়াক-ইন')" class="px-2 py-0.5 rounded-md bg-slate-800 hover:bg-slate-700 text-[10px] text-slate-300">কাউন্টার</button>
                            <button type="button" onclick="setLabel('জরুরি')" class="px-2 py-0.5 rounded-md bg-rose-500/20 text-rose-300 text-[10px]">জরুরি</button>
                        </div>
                    </div>

                    <!-- Direct Instant Print Button -->
                    <div class="pt-2">
                        <button type="submit" id="submitPrintBtn" class="w-full py-4 rounded-2xl bg-gradient-to-r from-emerald-500 via-teal-500 to-emerald-600 hover:from-emerald-600 hover:to-teal-600 active:scale-[0.99] text-white font-black text-base shadow-xl shadow-emerald-500/25 transition-all flex items-center justify-center gap-3">
                            <span class="text-xl">🖨️</span>
                            <span id="btnText">সরাসরি দোকানে প্রিন্ট করুন</span>
                            <span class="text-xs px-2 py-0.5 rounded-md bg-black/20 font-mono font-normal">১-ট্যাপ</span>
                        </button>
                    </div>

                    <div class="text-center">
                        <p class="text-[11px] text-slate-400">
                            🔒 পেমেন্ট স্বয়ংক্রিয়ভাবে অনুমোদিত হিসেবে উইন্ডোজ এজেন্ট কিউতে সরাসরি পৌঁছে যাবে
                        </p>
                    </div>

                </div>
            </div>

        </div>
    </form>

    <!-- Hidden Form for Cancelling/Deleting Temp Share File -->
    @if($fileData)
        <form id="cancelTempForm" action="{{ route('shop.quick_print.delete_temp') }}" method="POST" class="hidden">
            @csrf
            <input type="hidden" name="temp_file" value="{{ $fileData['temp_file'] }}">
        </form>
    @endif

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
    if (window.pdfjsLib) {
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
    }

    // Copy Stepper
    function adjustCopies(delta) {
        const input = document.getElementById('copiesInput');
        let val = parseInt(input.value) || 1;
        val += delta;
        if (val < 1) val = 1;
        if (val > 100) val = 100;
        input.value = val;
    }

    function setCopies(num) {
        document.getElementById('copiesInput').value = num;
    }

    function setLabel(text) {
        document.getElementById('customerLabelInput').value = text;
    }

    // PDF.js Visual Rendering Engine (100% Mobile & Desktop Compatible)
    let pdfDocInstance = null;
    let currentPdfPage = 1;
    let totalPdfPages = 1;

    function initPdfViewer(url) {
        if (!window.pdfjsLib) {
            document.getElementById('pdfLoadingIndicator')?.classList.add('hidden');
            document.getElementById('pdfFallbackIframe')?.classList.remove('hidden');
            return;
        }

        const loadingTask = pdfjsLib.getDocument({
            url: url,
            cMapUrl: 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/cmaps/',
            cMapPacked: true,
        });

        loadingTask.promise.then(function(pdf) {
            pdfDocInstance = pdf;
            totalPdfPages = pdf.numPages;
            const totalEl = document.getElementById('pdfTotalPagesNum');
            if (totalEl) totalEl.textContent = totalPdfPages;
            const controls = document.getElementById('pdfControls');
            if (controls) controls.style.display = 'flex';
            renderPdfPage(1);
        }).catch(function(err) {
            console.warn('PDF.js render failed, falling back to desktop iframe:', err);
            document.getElementById('pdfLoadingIndicator')?.classList.add('hidden');
            document.getElementById('pdfFallbackIframe')?.classList.remove('hidden');
        });
    }

    function renderPdfPage(pageNumber) {
        if (!pdfDocInstance || pageNumber < 1 || pageNumber > totalPdfPages) return;
        currentPdfPage = pageNumber;

        const pageEl = document.getElementById('pdfCurrentPageNum');
        if (pageEl) pageEl.textContent = pageNumber;

        const prevBtn = document.getElementById('pdfPrevBtn');
        const nextBtn = document.getElementById('pdfNextBtn');
        if (prevBtn) prevBtn.disabled = (currentPdfPage <= 1);
        if (nextBtn) nextBtn.disabled = (currentPdfPage >= totalPdfPages);

        pdfDocInstance.getPage(pageNumber).then(function(page) {
            const canvas = document.getElementById('pdfCanvas');
            if (!canvas) return;
            const ctx = canvas.getContext('2d');
            
            const containerWidth = canvas.parentElement.clientWidth || 360;
            const unscaledViewport = page.getViewport({ scale: 1 });
            const dynamicScale = Math.min((containerWidth - 16) / unscaledViewport.width, 1.6);
            const viewport = page.getViewport({ scale: Math.max(dynamicScale, 0.7) });

            canvas.height = viewport.height;
            canvas.width = viewport.width;

            const renderContext = {
                canvasContext: ctx,
                viewport: viewport
            };

            page.render(renderContext).promise.then(function() {
                document.getElementById('pdfLoadingIndicator')?.classList.add('hidden');
                document.getElementById('pdfCanvasContainer')?.classList.remove('hidden');
            });
        });
    }

    @if(isset($fileData) && $fileData && $fileData['is_pdf'])
        document.addEventListener('DOMContentLoaded', function() {
            initPdfViewer("{{ $fileData['url'] }}");
        });
    @endif

    // Direct File Selection Preview Handling (for desktop/manual upload)
    function handleDirectFileSelect(input) {
        const file = input.files[0];
        if (!file) return;

        const prompt = document.getElementById('dropzonePrompt');
        const preview = document.getElementById('clientPreviewWrapper');
        const previewContent = document.getElementById('clientPreviewContent');
        const fileName = document.getElementById('clientFileName');
        const fileSize = document.getElementById('clientFileSize');
        const fileIcon = document.getElementById('clientFileIcon');

        if (!prompt || !preview) return;

        prompt.classList.add('hidden');
        preview.classList.remove('hidden');

        fileName.textContent = file.name;
        const sizeMb = (file.size / (1024 * 1024)).toFixed(2);
        fileSize.textContent = sizeMb >= 1 ? `${sizeMb} MB` : `${(file.size / 1024).toFixed(1)} KB`;

        const isImage = file.type.startsWith('image/') || /\.(jpg|jpeg|png|webp|bmp|gif|jfif)$/i.test(file.name);
        const isPdf = file.type === 'application/pdf' || file.name.toLowerCase().endsWith('.pdf');

        if (isImage) {
            fileIcon.textContent = '🖼️';
            const reader = new FileReader();
            reader.onload = (e) => {
                currentDirectImageDataUrl = e.target.result;
                previewContent.innerHTML = `<img src="${e.target.result}" class="max-h-[340px] max-w-full rounded-lg object-contain shadow">`;
                document.getElementById('directDocScannerBtnContainer')?.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        } else if (isPdf) {
            fileIcon.textContent = '📑';
            if (window.pdfjsLib) {
                previewContent.innerHTML = `
                    <div class="flex flex-col items-center justify-center py-6 gap-2 text-emerald-400">
                        <svg class="animate-spin h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <span class="text-xs text-slate-300">পিডিএফ রেন্ডার হচ্ছে...</span>
                    </div>
                `;
                const fileReader = new FileReader();
                fileReader.onload = function() {
                    const typedarray = new Uint8Array(this.result);
                    pdfjsLib.getDocument({ data: typedarray }).promise.then(function(pdf) {
                        pdf.getPage(1).then(function(page) {
                            const canvas = document.createElement('canvas');
                            const ctx = canvas.getContext('2d');
                            const viewport = page.getViewport({ scale: 1.0 });
                            canvas.height = viewport.height;
                            canvas.width = viewport.width;
                            canvas.className = 'max-h-[340px] max-w-full rounded-lg shadow border border-slate-700';

                            page.render({ canvasContext: ctx, viewport: viewport }).promise.then(function() {
                                previewContent.innerHTML = '';
                                previewContent.appendChild(canvas);
                            });
                        });
                    }).catch(function() {
                        const blobUrl = URL.createObjectURL(file);
                        previewContent.innerHTML = `<iframe src="${blobUrl}#toolbar=0" class="w-full h-[320px] rounded-lg bg-white border border-slate-700"></iframe>`;
                    });
                };
                fileReader.readAsArrayBuffer(file);
            } else {
                const blobUrl = URL.createObjectURL(file);
                previewContent.innerHTML = `<iframe src="${blobUrl}#toolbar=0" class="w-full h-[320px] rounded-lg bg-white border border-slate-700"></iframe>`;
            }
        } else {
            fileIcon.textContent = '📄';
            previewContent.innerHTML = `
                <div class="text-center py-8 space-y-2">
                    <span class="text-4xl">📄</span>
                    <p class="text-xs text-slate-300 font-bold">${file.name}</p>
                    <p class="text-[10px] text-slate-500">প্রিন্ট করার জন্য ফাইলটি প্রস্তুত</p>
                </div>
            `;
        }
    }

    function clearDirectFile() {
        const input = document.getElementById('documentInput');
        if (input) input.value = '';
        currentDirectImageDataUrl = null;
        document.getElementById('scannedImageDataInput').value = '';
        document.getElementById('directDocScannerBtnContainer')?.classList.add('hidden');
        document.getElementById('directScanBadge')?.classList.add('hidden');
        const prompt = document.getElementById('dropzonePrompt');
        const preview = document.getElementById('clientPreviewWrapper');
        if (prompt) prompt.classList.remove('hidden');
        if (preview) preview.classList.add('hidden');
    }

    // ==================== DOC SCANNER & CAMSCANNER FILTER ENGINE ====================
    let currentDirectImageDataUrl = null;
    let scannerImg = null;
    let scannerRotation = 0;
    let scannerCorners = null; // [tl, tr, br, bl]
    let activeScannerHandle = -1;
    let scannerFilterMode = 'clean_bw';

    const A4_TARGET_WIDTH = 1240;
    const A4_TARGET_HEIGHT = 1754; // Standard A4 ratio 1:1.414

    function openDocScannerModal(imgSrc) {
        if (!imgSrc) return;
        const modal = document.getElementById('docScannerModal');
        modal.classList.remove('hidden');

        scannerRotation = 0;
        scannerFilterMode = 'clean_bw';
        updateScannerFilterButtons();

        scannerImg = new Image();
        scannerImg.crossOrigin = 'anonymous';
        scannerImg.onload = function() {
            initScannerCanvas();
        };
        scannerImg.src = imgSrc;
    }

    function closeDocScannerModal() {
        const modal = document.getElementById('docScannerModal');
        modal.classList.add('hidden');
    }

    function initScannerCanvas() {
        if (!scannerImg) return;
        const canvas = document.getElementById('scannerCanvas');

        const isRotated = scannerRotation % 180 !== 0;
        const w = isRotated ? scannerImg.height : scannerImg.width;
        const h = isRotated ? scannerImg.width : scannerImg.height;

        canvas.width = w;
        canvas.height = h;

        // Auto corners with 5% inset
        const insetX = w * 0.05;
        const insetY = h * 0.05;
        scannerCorners = [
            { x: insetX, y: insetY },
            { x: w - insetX, y: insetY },
            { x: w - insetX, y: h - insetY },
            { x: insetX, y: h - insetY }
        ];

        drawScannerCanvas();
        setupScannerInteractions();
    }

    function drawScannerCanvas() {
        const canvas = document.getElementById('scannerCanvas');
        if (!canvas || !scannerImg) return;
        const ctx = canvas.getContext('2d');

        ctx.clearRect(0, 0, canvas.width, canvas.height);

        // Draw rotated base image
        ctx.save();
        ctx.translate(canvas.width / 2, canvas.height / 2);
        ctx.rotate((scannerRotation * Math.PI) / 180);
        ctx.drawImage(scannerImg, -scannerImg.width / 2, -scannerImg.height / 2);
        ctx.restore();

        if (!scannerCorners || scannerCorners.length < 4) return;

        // Darken outside quad area
        ctx.save();
        ctx.fillStyle = 'rgba(0, 0, 0, 0.45)';
        ctx.fillRect(0, 0, canvas.width, canvas.height);

        // Clip inside quad
        ctx.beginPath();
        ctx.moveTo(scannerCorners[0].x, scannerCorners[0].y);
        ctx.lineTo(scannerCorners[1].x, scannerCorners[1].y);
        ctx.lineTo(scannerCorners[2].x, scannerCorners[2].y);
        ctx.lineTo(scannerCorners[3].x, scannerCorners[3].y);
        ctx.closePath();
        ctx.clip();

        ctx.translate(canvas.width / 2, canvas.height / 2);
        ctx.rotate((scannerRotation * Math.PI) / 180);
        ctx.drawImage(scannerImg, -scannerImg.width / 2, -scannerImg.height / 2);
        ctx.restore();

        // Draw green quad border
        ctx.save();
        ctx.strokeStyle = '#10b981';
        ctx.lineWidth = Math.max(3, canvas.width / 260);
        ctx.beginPath();
        ctx.moveTo(scannerCorners[0].x, scannerCorners[0].y);
        ctx.lineTo(scannerCorners[1].x, scannerCorners[1].y);
        ctx.lineTo(scannerCorners[2].x, scannerCorners[2].y);
        ctx.lineTo(scannerCorners[3].x, scannerCorners[3].y);
        ctx.closePath();
        ctx.stroke();

        // Draw corner handles
        const handleRadius = Math.max(16, canvas.width / 45);
        scannerCorners.forEach((pt) => {
            ctx.beginPath();
            ctx.arc(pt.x, pt.y, handleRadius, 0, Math.PI * 2);
            ctx.fillStyle = '#10b981';
            ctx.fill();
            ctx.lineWidth = Math.max(2, handleRadius / 4);
            ctx.strokeStyle = '#ffffff';
            ctx.stroke();

            ctx.beginPath();
            ctx.arc(pt.x, pt.y, handleRadius / 3, 0, Math.PI * 2);
            ctx.fillStyle = '#ffffff';
            ctx.fill();
        });
        ctx.restore();
    }

    function setupScannerInteractions() {
        const canvas = document.getElementById('scannerCanvas');
        if (!canvas || canvas._hasScannerListeners) return;
        canvas._hasScannerListeners = true;

        function getPoint(evt) {
            const rect = canvas.getBoundingClientRect();
            const clientX = evt.touches ? evt.touches[0].clientX : evt.clientX;
            const clientY = evt.touches ? evt.touches[0].clientY : evt.clientY;
            const scaleX = canvas.width / rect.width;
            const scaleY = canvas.height / rect.height;
            return {
                x: (clientX - rect.left) * scaleX,
                y: (clientY - rect.top) * scaleY
            };
        }

        function onStart(evt) {
            if (!scannerCorners) return;
            const pt = getPoint(evt);
            const hitDist = Math.max(45, canvas.width / 18);

            activeScannerHandle = -1;
            let minDist = hitDist;

            scannerCorners.forEach((c, idx) => {
                const dist = Math.hypot(c.x - pt.x, c.y - pt.y);
                if (dist < minDist) {
                    minDist = dist;
                    activeScannerHandle = idx;
                }
            });

            if (activeScannerHandle !== -1) {
                evt.preventDefault();
            }
        }

        function onMove(evt) {
            if (activeScannerHandle === -1 || !scannerCorners) return;
            evt.preventDefault();
            const pt = getPoint(evt);
            scannerCorners[activeScannerHandle].x = Math.max(0, Math.min(canvas.width, pt.x));
            scannerCorners[activeScannerHandle].y = Math.max(0, Math.min(canvas.height, pt.y));
            drawScannerCanvas();
        }

        function onEnd() {
            activeScannerHandle = -1;
        }

        canvas.addEventListener('mousedown', onStart);
        window.addEventListener('mousemove', onMove);
        window.addEventListener('mouseup', onEnd);

        canvas.addEventListener('touchstart', onStart, { passive: false });
        window.addEventListener('touchmove', onMove, { passive: false });
        window.addEventListener('touchend', onEnd);
    }

    function rotateScannerDoc() {
        scannerRotation = (scannerRotation + 90) % 360;
        initScannerCanvas();
    }

    function resetScannerCorners() {
        const canvas = document.getElementById('scannerCanvas');
        if (!canvas) return;
        const w = canvas.width;
        const h = canvas.height;
        const insetX = w * 0.05;
        const insetY = h * 0.05;
        scannerCorners = [
            { x: insetX, y: insetY },
            { x: w - insetX, y: insetY },
            { x: w - insetX, y: h - insetY },
            { x: insetX, y: h - insetY }
        ];
        drawScannerCanvas();
    }

    function setDocFilter(mode) {
        scannerFilterMode = mode;
        updateScannerFilterButtons();
    }

    function updateScannerFilterButtons() {
        ['clean_bw', 'magic_color', 'original'].forEach(m => {
            const btn = document.getElementById(`docFilter_${m}`);
            if (!btn) return;
            if (m === scannerFilterMode) {
                btn.className = 'flex-1 py-1.5 px-2 rounded-xl text-xs font-bold bg-emerald-600 text-white shadow-sm border border-emerald-500 transition-all';
            } else {
                btn.className = 'flex-1 py-1.5 px-2 rounded-xl text-xs font-bold bg-slate-800 text-slate-300 border border-slate-700 hover:text-white transition-all';
            }
        });
    }

    function applyDocScanner() {
        if (!scannerImg || !scannerCorners) return;

        const isRotated = scannerRotation % 180 !== 0;
        const rw = isRotated ? scannerImg.height : scannerImg.width;
        const rh = isRotated ? scannerImg.width : scannerImg.height;

        const rotCanvas = document.createElement('canvas');
        rotCanvas.width = rw;
        rotCanvas.height = rh;
        const rCtx = rotCanvas.getContext('2d');
        rCtx.translate(rw / 2, rh / 2);
        rCtx.rotate((scannerRotation * Math.PI) / 180);
        rCtx.drawImage(scannerImg, -scannerImg.width / 2, -scannerImg.height / 2);

        // Perform subdivision mesh perspective warp to A4 (1240 x 1754)
        const a4Canvas = document.createElement('canvas');
        a4Canvas.width = A4_TARGET_WIDTH;
        a4Canvas.height = A4_TARGET_HEIGHT;
        const a4Ctx = a4Canvas.getContext('2d');

        renderScannerMesh(rotCanvas, a4Ctx, scannerCorners, A4_TARGET_WIDTH, A4_TARGET_HEIGHT, 6);

        // Apply CamScanner filter
        if (scannerFilterMode !== 'original') {
            const imgData = a4Ctx.getImageData(0, 0, A4_TARGET_WIDTH, A4_TARGET_HEIGHT);
            const d = imgData.data;
            const len = d.length;

            if (scannerFilterMode === 'clean_bw') {
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
            } else if (scannerFilterMode === 'magic_color') {
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
            a4Ctx.putImageData(imgData, 0, 0);
        }

        const dataUrl = a4Canvas.toDataURL('image/jpeg', 0.95);
        document.getElementById('scannedImageDataInput').value = dataUrl;

        // Update previews on screen
        const sharedImg = document.getElementById('sharedPreviewImage');
        if (sharedImg) {
            sharedImg.src = dataUrl;
            document.getElementById('sharedScanBadge')?.classList.remove('hidden');
        }

        const clientContent = document.getElementById('clientPreviewContent');
        if (clientContent && !sharedImg) {
            clientContent.innerHTML = `<img src="${dataUrl}" class="max-h-[340px] max-w-full rounded-lg object-contain shadow">`;
            document.getElementById('directScanBadge')?.classList.remove('hidden');
        }

        closeDocScannerModal();

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: '✓ ডকুমেন্ট সোজা ও হোয়াইট ফিল্টার প্রয়োগ হয়েছে!',
                showConfirmButton: false,
                timer: 2500
            });
        }
    }

    function renderScannerMesh(srcCanvas, destCtx, corners, destW, destH, n) {
        const [c0, c1, c2, c3] = corners;
        function interp(u, v) {
            const topX = c0.x + (c1.x - c0.x) * u, topY = c0.y + (c1.y - c0.y) * u;
            const botX = c3.x + (c2.x - c3.x) * u, botY = c3.y + (c2.y - c3.y) * u;
            return { x: topX + (botX - topX) * v, y: topY + (botY - topY) * v };
        }
        destCtx.clearRect(0, 0, destW, destH);
        for (let i = 0; i < n; i++) {
            for (let j = 0; j < n; j++) {
                const u0 = i / n, u1 = (i + 1) / n;
                const v0 = j / n, v1 = (j + 1) / n;
                const p00 = interp(u0, v0), p10 = interp(u1, v0);
                const p01 = interp(u0, v1), p11 = interp(u1, v1);
                const dX0 = u0 * destW, dX1 = u1 * destW;
                const dY0 = v0 * destH, dY1 = v1 * destH;
                drawAffineTri(destCtx, srcCanvas, p00.x, p00.y, p10.x, p10.y, p01.x, p01.y, dX0, dY0, dX1, dY0, dX0, dY1);
                drawAffineTri(destCtx, srcCanvas, p10.x, p10.y, p11.x, p11.y, p01.x, p01.y, dX1, dY0, dX1, dY1, dX0, dY1);
            }
        }
    }

    function drawAffineTri(ctx, im, x0, y0, x1, y1, x2, y2, u0, v0, u1, v1, u2, v2) {
        ctx.save();
        ctx.beginPath();
        ctx.moveTo(u0, v0);
        ctx.lineTo(u1, v1);
        ctx.lineTo(u2, v2);
        ctx.closePath();
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

    // Form submission state to prevent double clicks
    const form = document.getElementById('quickPrintForm');
    const submitBtn = document.getElementById('submitPrintBtn');
    const btnText = document.getElementById('btnText');

    if (form && submitBtn) {
        form.addEventListener('submit', function() {
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
            btnText.textContent = 'প্রিন্টারে পাঠানো হচ্ছে...';
        });
    }
</script>

<!-- Document Scanner Modal (CamScanner style 4-Corner Perspective Warper & Background Whitening) -->
<div id="docScannerModal" class="fixed inset-0 z-50 bg-slate-950/90 backdrop-blur-md hidden flex items-center justify-center p-3 sm:p-5">
    <div class="bg-slate-900 border border-slate-700 rounded-3xl max-w-4xl w-full max-h-[96vh] flex flex-col overflow-hidden shadow-2xl">
        <!-- Modal Header -->
        <div class="p-4 sm:px-6 border-b border-slate-800 flex items-center justify-between bg-slate-950/50">
            <div class="flex items-center gap-2.5">
                <span class="w-8 h-8 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-lg">📐</span>
                <div>
                    <h3 class="text-sm sm:text-base font-black text-white">ডকুমেন্ট স্ক্যানার ও কর্নার সোজা করার টুল</h3>
                    <p class="text-[11px] text-slate-400">ডকুমেন্টের ৪টি কোণা টেনে ঠিক করুন ও ক্যামস্ক্যানার হোয়াইট ফিল্টার প্রয়োগ করুন</p>
                </div>
            </div>
            <button type="button" onclick="closeDocScannerModal()" class="p-2 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800 transition text-sm cursor-pointer">
                ✕
            </button>
        </div>

        <!-- Modal Body: Interactive Canvas -->
        <div class="p-4 flex-1 overflow-auto flex flex-col items-center justify-center bg-slate-950/80 relative min-h-[320px]">
            <canvas id="scannerCanvas" class="max-h-[50vh] max-w-full rounded-xl border border-slate-700 cursor-crosshair touch-none shadow-2xl"></canvas>
            <div class="mt-2 text-[10px] sm:text-xs text-slate-400 text-center flex items-center justify-center gap-2">
                <span>💡 কোণাগুলো টেনে ডকুমেন্টের সীমানা নির্দিষ্ট করুন</span>
                <span>•</span>
                <button type="button" onclick="resetScannerCorners()" class="text-emerald-400 hover:underline font-bold cursor-pointer">📐 অটো কর্নার রিসেট</button>
                <span>•</span>
                <button type="button" onclick="rotateScannerDoc()" class="text-teal-400 hover:underline font-bold cursor-pointer">🔄 ৯০° ঘোরান</button>
            </div>
        </div>

        <!-- Filter Selection Toolbar -->
        <div class="p-3 sm:px-6 bg-slate-950/90 border-t border-slate-800 flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-1.5 flex-1 min-w-[280px]">
                <span class="text-xs font-bold text-slate-300 mr-1">ফিল্টার:</span>
                <button type="button" onclick="setDocFilter('clean_bw')" id="docFilter_clean_bw" class="flex-1 py-1.5 px-2 rounded-xl text-xs font-bold bg-emerald-600 text-white shadow-sm border border-emerald-500 transition-all cursor-pointer">
                    📄 ক্লিন সাদাকালো (CamScanner)
                </button>
                <button type="button" onclick="setDocFilter('magic_color')" id="docFilter_magic_color" class="flex-1 py-1.5 px-2 rounded-xl text-xs font-bold bg-slate-800 text-slate-300 border border-slate-700 hover:text-white transition-all cursor-pointer">
                    🎨 ম্যাজিক কালার
                </button>
                <button type="button" onclick="setDocFilter('original')" id="docFilter_original" class="flex-1 py-1.5 px-2 rounded-xl text-xs font-bold bg-slate-800 text-slate-300 border border-slate-700 hover:text-white transition-all cursor-pointer">
                    🖼️ অরিজিনাল
                </button>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="p-4 sm:px-6 border-t border-slate-800 bg-slate-900 flex items-center justify-between gap-3">
            <button type="button" onclick="closeDocScannerModal()" class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold text-xs transition cursor-pointer">
                বাতিল
            </button>
            <button type="button" onclick="applyDocScanner()" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold text-xs shadow-lg shadow-emerald-600/30 transition flex items-center gap-2 cursor-pointer">
                <span>✓ সোজা ও ফিল্টার সম্পন্ন করুন</span>
            </button>
        </div>
    </div>
</div>
@endsection
