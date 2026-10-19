<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\PrintJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class CustomerPrintController extends Controller
{
    public function showUploadPage($slug)
    {
        $shop = Shop::where('slug', $slug)->firstOrFail();
        if (!$shop->is_active || !$shop->isSubscriptionActive()) {
            return response()->view('customer.shop_closed', compact('shop'), 403);
        }

        if ($shop->is_office_mode) {
            return redirect()->route('customer.office_upload', $slug);
        }

        return view('customer.upload', compact('shop'));
    }

    public function submitJob(Request $request, $slug)
    {
        $shop = Shop::where('slug', $slug)->firstOrFail();
        if (!$shop->is_active || !$shop->isSubscriptionActive()) {
            return response()->view('customer.shop_closed', compact('shop'), 403);
        }

        $request->validate([
            'document' => 'required|file|max:51200|mimes:pdf,doc,docx,jpg,jpeg,png',
            'color_mode' => 'required|in:bw,color',
            'is_duplex' => 'nullable',
            'copies' => 'required|integer|min:1|max:100',
            'paper_size' => 'nullable|string',
            'pickup_reference' => 'nullable|string|max:100',
            'payment_method' => 'required|in:bkash,nagad,cash',
            'payment_trx_id' => 'nullable|string|max:50',
            'customer_phone' => 'nullable|string|max:20',
        ]);

        $file = $request->file('document');
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $mime = $file->getClientMimeType();

        // Detect pages: if user sent page count, take it; if PDF, parse page count as server-side verification
        $totalPages = (int) ($request->input('total_pages', 1));
        if (strtolower($extension) === 'pdf') {
            try {
                $content = file_get_contents($file->getRealPath());
                $matches = [];
                // Count pages using PDF /Type /Page pattern or Count
                if (preg_match_all("/\/Type\s*\/Page\b/", $content, $matches)) {
                    $detectedPages = count($matches[0]);
                    if ($detectedPages > 0) {
                        $totalPages = $detectedPages;
                    }
                }
            } catch (\Exception $e) {
                // Keep user input or default
            }
        }
        if ($totalPages < 1) $totalPages = 1;

        $copies = (int) $request->copies;
        $isDuplex = $request->boolean('is_duplex');
        $colorMode = $request->color_mode;
        $paperSize = $request->input('paper_size', 'A4');

        // Calculate rate
        if ($paperSize === '4x6_passport') {
            $totalAmount = $shop->rate_photo_passport * $copies;
        } else {
            if ($colorMode === 'bw') {
                $unitRate = $isDuplex ? ($shop->rate_bw_double / 2) : $shop->rate_bw_single;
            } else {
                $unitRate = $isDuplex ? ($shop->rate_color_double / 2) : $shop->rate_color_single;
            }
            $totalAmount = round($totalPages * $unitRate * $copies, 2);
        }

        // Strict TrxID and Payment Validation
        $paymentMethod = $request->payment_method;
        $trxId = strtoupper(trim($request->input('payment_trx_id', '')));
        $paymentStatus = 'pending';
        $matchedTx = null;

        if (in_array($paymentMethod, ['bkash', 'nagad'])) {
            if (!empty($trxId)) {
                // 1. Check if this TrxID has already been claimed by a completed or paid print job
                $alreadyUsedJob = PrintJob::where('shop_id', $shop->id)
                    ->where('payment_trx_id', $trxId)
                    ->where('payment_status', 'paid')
                    ->first();

                if ($alreadyUsedJob) {
                    return back()->withInput()->with('error_alert', "এই TrxID ({$trxId}) ইতিমধ্যে ব্যবহার করা হয়েছে! অনুগ্রহ করে সঠিক বা নতুন ট্রানজেকশন আইডি দিন।");
                }

                // 2. Check if transaction exists in database (received via Android SMS app)
                $matchedTx = \App\Models\Transaction::where('shop_id', $shop->id)
                    ->where('trx_id', $trxId)
                    ->where('status', 'completed')
                    ->first();

                if ($matchedTx) {
                    // Check if transaction was already linked to an existing paid job
                    if ($matchedTx->print_job_id) {
                        return back()->withInput()->with('error_alert', "এই পেমেন্ট ট্রানজেকশনটি ({$trxId}) ইতিমধ্যে অন্য একটি অর্ডারে সম্পন্ন হয়ে গেছে!");
                    }

                    // Check if paid amount is sufficient for the job total amount
                    if ((float)$matchedTx->amount < (float)$totalAmount) {
                        return back()->withInput()->with('error_alert', "প্রদত্ত TrxID-এর পেমেন্ট পরিমাণ (৳{$matchedTx->amount}) আপনার মোট বিলের (৳{$totalAmount}) চেয়ে কম! অনুগ্রহ করে সঠিক পরিমাণের পেমেন্ট করুন অথবা কাউন্টারে ক্যাশ দিন।");
                    }

                    // Payment verified!
                    $paymentStatus = 'paid';
                } else {
                    // If SMS has not synced yet from phone, leave payment_status as 'pending'
                    // Once Android SMS App detects the SMS, it will verify and mark as paid.
                    $paymentStatus = 'pending';
                }
            } else {
                $paymentStatus = 'pending';
            }
        } elseif ($paymentMethod === 'cash') {
            $paymentStatus = 'pending';
        }

        // Store file
        $fileName = 'job_' . time() . '_' . Str::random(8) . '.' . $extension;
        $path = $file->storeAs('print_uploads', $fileName, 'public');

        $jobToken = 'PS-' . strtoupper(Str::random(8));

        $job = PrintJob::create([
            'shop_id' => $shop->id,
            'job_token' => $jobToken,
            'customer_name' => $request->input('customer_name', 'কাস্টমার'),
            'customer_phone' => $request->customer_phone,
            'pickup_reference' => $request->input('pickup_reference'),
            'file_name' => $fileName,
            'original_name' => $originalName,
            'file_path' => $path,
            'file_type' => $extension,
            'total_pages' => $totalPages,
            'copies' => $copies,
            'color_mode' => $colorMode,
            'is_duplex' => $isDuplex,
            'paper_size' => $paperSize,
            'page_range' => $request->input('page_range'),
            'total_amount' => $totalAmount,
            'payment_method' => $paymentMethod,
            'payment_trx_id' => $trxId ?: null,
            'payment_status' => $paymentStatus,
            'print_status' => 'queued',
        ]);

        // Link matched transaction if found
        if ($matchedTx && $paymentStatus === 'paid') {
            $matchedTx->update([
                'print_job_id' => $job->id,
                'sender_number' => $request->customer_phone ?: $matchedTx->sender_number,
            ]);
        }

        return redirect()->route('customer.status', $jobToken);
    }

    public function showNidUploadPage($slug)
    {
        $shop = Shop::where('slug', $slug)->firstOrFail();
        if (!$shop->is_active || !$shop->isSubscriptionActive()) {
            return response()->view('customer.shop_closed', compact('shop'), 403);
        }

        if ($shop->is_office_mode) {
            return redirect()->route('customer.office_nid_upload', $slug);
        }

        if (!$shop->has_nid_print) {
            return redirect()->route('customer.upload', $slug)->with('error_alert', 'এই দোকানে এনআইডি প্রিন্ট সেবাটি বর্তমানে বন্ধ রয়েছে।');
        }

        return view('customer.nid_upload', compact('shop'));
    }

    public function submitNidJob(Request $request, $slug, \App\Services\NidPdfService $nidPdfService)
    {
        $shop = Shop::where('slug', $slug)->firstOrFail();
        if (!$shop->is_active || !$shop->isSubscriptionActive()) {
            return response()->view('customer.shop_closed', compact('shop'), 403);
        }

        if (!$shop->has_nid_print) {
            return redirect()->route('customer.upload', $slug)->with('error_alert', 'এই দোকানে এনআইডি প্রিন্ট সেবাটি বন্ধ রয়েছে।');
        }

        $request->validate([
            'color_mode' => 'required|in:bw,color',
            'copies' => 'required|integer|min:1|max:100',
            'pickup_reference' => 'nullable|string|max:100',
            'payment_method' => 'required|in:bkash,nagad,cash',
            'payment_trx_id' => 'nullable|string|max:50',
            'customer_phone' => 'nullable|string|max:20',
        ]);

        // Ensure both front and back images are provided (either as file or base64 data URI)
        $hasFront = $request->hasFile('front_file') || !empty($request->input('front_cropped_data'));
        $hasBack  = $request->hasFile('back_file') || !empty($request->input('back_cropped_data'));

        if (!$hasFront || !$hasBack) {
            return back()->withInput()->with('error_alert', 'এনআইডি কার্ডের সামনের অংশ (Front) এবং পেছনের অংশ (Back) উভয় পাশের ছবি প্রদান করতে হবে।');
        }

        $copies = (int) $request->copies;
        $colorMode = $request->color_mode;

        // Calculate rate based on Shop NID pricing
        if ($colorMode === 'bw') {
            $unitRate = (float) ($shop->rate_nid_bw ?: $shop->rate_bw_single);
        } else {
            $unitRate = (float) ($shop->rate_nid_color ?: $shop->rate_color_single);
        }
        $totalAmount = round($unitRate * $copies, 2);

        // Strict TrxID and Payment Validation (matching system standard)
        $paymentMethod = $request->payment_method;
        $trxId = strtoupper(trim($request->input('payment_trx_id', '')));
        $paymentStatus = 'pending';
        $matchedTx = null;

        if (in_array($paymentMethod, ['bkash', 'nagad'])) {
            if (!empty($trxId)) {
                $alreadyUsedJob = PrintJob::where('shop_id', $shop->id)
                    ->where('payment_trx_id', $trxId)
                    ->where('payment_status', 'paid')
                    ->first();

                if ($alreadyUsedJob) {
                    return back()->withInput()->with('error_alert', "এই TrxID ({$trxId}) ইতিমধ্যে ব্যবহার করা হয়েছে! অনুগ্রহ করে সঠিক বা নতুন ট্রানজেকশন আইডি দিন।");
                }

                $matchedTx = \App\Models\Transaction::where('shop_id', $shop->id)
                    ->where('trx_id', $trxId)
                    ->where('status', 'completed')
                    ->first();

                if ($matchedTx) {
                    if ($matchedTx->print_job_id) {
                        return back()->withInput()->with('error_alert', "এই পেমেন্ট ট্রানজেকশনটি ({$trxId}) ইতিমধ্যে অন্য একটি অর্ডারে সম্পন্ন হয়ে গেছে!");
                    }

                    if ((float)$matchedTx->amount < (float)$totalAmount) {
                        return back()->withInput()->with('error_alert', "প্রদত্ত TrxID-এর পেমেন্ট পরিমাণ (৳{$matchedTx->amount}) আপনার মোট বিলের (৳{$totalAmount}) চেয়ে কম! অনুগ্রহ করে সঠিক পরিমাণের পেমেন্ট করুন অথবা কাউন্টারে ক্যাশ দিন।");
                    }

                    $paymentStatus = 'paid';
                } else {
                    $paymentStatus = 'pending';
                }
            } else {
                $paymentStatus = 'pending';
            }
        } elseif ($paymentMethod === 'cash') {
            $paymentStatus = 'pending';
        }

        // Save temporary front and back images
        $tempFrontPath = storage_path('app/temp_in_front_' . Str::random(10) . '.jpg');
        $tempBackPath  = storage_path('app/temp_in_back_' . Str::random(10) . '.jpg');

        $this->saveImageInput($request, 'front', $tempFrontPath);
        $this->saveImageInput($request, 'back', $tempBackPath);

        // Generate standardized A4 PDF
        $fileName = 'job_nid_' . time() . '_' . Str::random(8) . '.pdf';
        $relativePdfPath = 'print_uploads/' . $fileName;
        $fullPdfPath = storage_path('app/public/' . $relativePdfPath);

        // Ensure directory exists
        if (!file_exists(dirname($fullPdfPath))) {
            @mkdir(dirname($fullPdfPath), 0777, true);
        }

        try {
            $nidPdfService->generateA4NidPdf($tempFrontPath, $tempBackPath, $colorMode, $fullPdfPath);
        } catch (\Exception $e) {
            @unlink($tempFrontPath);
            @unlink($tempBackPath);
            return back()->withInput()->with('error_alert', 'এনআইডি প্রিন্ট ফাইল তৈরি করতে সমস্যা হয়েছে: ' . $e->getMessage());
        }

        // Clean up input temp files
        @unlink($tempFrontPath);
        @unlink($tempBackPath);

        $jobToken = 'PS-' . strtoupper(Str::random(8));

        $job = PrintJob::create([
            'shop_id' => $shop->id,
            'job_token' => $jobToken,
            'customer_name' => $request->input('customer_name', 'এনআইডি কাস্টমার'),
            'customer_phone' => $request->customer_phone,
            'pickup_reference' => $request->input('pickup_reference'),
            'file_name' => $fileName,
            'original_name' => 'NID_Card_' . date('Ymd_His') . '.pdf',
            'file_path' => $relativePdfPath,
            'file_type' => 'pdf',
            'total_pages' => 1,
            'copies' => $copies,
            'color_mode' => $colorMode,
            'is_duplex' => false,
            'paper_size' => 'A4',
            'page_range' => '1',
            'total_amount' => $totalAmount,
            'payment_method' => $paymentMethod,
            'payment_trx_id' => $trxId ?: null,
            'payment_status' => $paymentStatus,
            'print_status' => 'queued',
        ]);

        if ($matchedTx && $paymentStatus === 'paid') {
            $matchedTx->update([
                'print_job_id' => $job->id,
                'sender_number' => $request->customer_phone ?: $matchedTx->sender_number,
            ]);
        }

        return redirect()->route('customer.status', $jobToken);
    }

    protected function saveImageInput(Request $request, string $side, string $destPath): void
    {
        $base64Key = "{$side}_cropped_data";
        $fileKey = "{$side}_file";

        if (!empty($request->input($base64Key))) {
            $data = $request->input($base64Key);
            if (preg_match('/^data:image\/(\w+);base64,/', $data, $matches)) {
                $data = substr($data, strpos($data, ',') + 1);
            }
            $binary = base64_decode($data);
            file_put_contents($destPath, $binary);
        } elseif ($request->hasFile($fileKey)) {
            $file = $request->file($fileKey);
            copy($file->getRealPath(), $destPath);
        } else {
            throw new \Exception("Missing image for {$side} side");
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Office / Corporate Internal Printing (Zero Payment, Instant Auto-Print)
    |--------------------------------------------------------------------------
    */

    public function showOfficeUploadPage($slug)
    {
        $shop = Shop::where('slug', $slug)->firstOrFail();
        if (!$shop->is_active || !$shop->isSubscriptionActive()) {
            return response()->view('customer.shop_closed', compact('shop'), 403);
        }

        return view('customer.office_upload', compact('shop'));
    }

    public function submitOfficeJob(Request $request, $slug)
    {
        $shop = Shop::where('slug', $slug)->firstOrFail();
        if (!$shop->is_active || !$shop->isSubscriptionActive()) {
            return response()->view('customer.shop_closed', compact('shop'), 403);
        }

        $request->validate([
            'document' => 'required|file|max:51200|mimes:pdf,doc,docx,jpg,jpeg,png',
            'color_mode' => 'required|in:bw,color',
            'is_duplex' => 'nullable',
            'copies' => 'required|integer|min:1|max:100',
            'paper_size' => 'nullable|string',
            'employee_name' => 'nullable|string|max:100',
            'pickup_reference' => 'nullable|string|max:100',
        ]);

        $file = $request->file('document');
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();

        $totalPages = (int) ($request->input('total_pages', 1));
        if (strtolower($extension) === 'pdf') {
            try {
                $content = file_get_contents($file->getRealPath());
                $matches = [];
                if (preg_match_all("/\/Type\s*\/Page\b/", $content, $matches)) {
                    $detectedPages = count($matches[0]);
                    if ($detectedPages > 0) {
                        $totalPages = $detectedPages;
                    }
                }
            } catch (\Exception $e) {}
        }
        if ($totalPages < 1) $totalPages = 1;

        $copies = (int) $request->copies;
        $isDuplex = $request->boolean('is_duplex');
        $colorMode = $request->color_mode;
        $paperSize = $request->input('paper_size', 'A4');

        $fileName = 'office_' . time() . '_' . Str::random(8) . '.' . $extension;
        $path = $file->storeAs('print_uploads', $fileName, 'public');

        $jobToken = 'PS-' . strtoupper(Str::random(8));

        $job = PrintJob::create([
            'shop_id' => $shop->id,
            'job_token' => $jobToken,
            'customer_name' => $request->input('employee_name', 'অফিস কর্মী'),
            'customer_phone' => null,
            'pickup_reference' => $request->input('pickup_reference'),
            'file_name' => $fileName,
            'original_name' => $originalName,
            'file_path' => $path,
            'file_type' => $extension,
            'total_pages' => $totalPages,
            'copies' => $copies,
            'color_mode' => $colorMode,
            'is_duplex' => $isDuplex,
            'paper_size' => $paperSize,
            'page_range' => $request->input('page_range'),
            'total_amount' => 0.00,
            'payment_method' => 'cash',
            'payment_trx_id' => 'OFFICE-INTERNAL',
            'payment_status' => 'paid', // Auto-approved: Office zero-friction print
            'print_status' => 'queued', // Spooled straight to the shared printer!
            'is_office_job' => true,
        ]);

        return redirect()->route('customer.status', $jobToken);
    }

    public function showOfficeNidUploadPage($slug)
    {
        $shop = Shop::where('slug', $slug)->firstOrFail();
        if (!$shop->is_active || !$shop->isSubscriptionActive()) {
            return response()->view('customer.shop_closed', compact('shop'), 403);
        }

        return view('customer.office_nid_upload', compact('shop'));
    }

    public function submitOfficeNidJob(Request $request, $slug, \App\Services\NidPdfService $nidPdfService)
    {
        $shop = Shop::where('slug', $slug)->firstOrFail();
        if (!$shop->is_active || !$shop->isSubscriptionActive()) {
            return response()->view('customer.shop_closed', compact('shop'), 403);
        }

        $request->validate([
            'color_mode' => 'required|in:bw,color',
            'copies' => 'required|integer|min:1|max:100',
            'employee_name' => 'nullable|string|max:100',
            'pickup_reference' => 'nullable|string|max:100',
        ]);

        $hasFront = $request->hasFile('front_file') || !empty($request->input('front_cropped_data'));
        $hasBack  = $request->hasFile('back_file') || !empty($request->input('back_cropped_data'));

        if (!$hasFront || !$hasBack) {
            return back()->withInput()->with('error_alert', 'এনআইডি কার্ডের সামনের ও পেছনের উভয় অংশের ছবি প্রদান করতে হবে।');
        }

        $copies = (int) $request->copies;
        $colorMode = $request->color_mode;

        $tempFrontPath = storage_path('app/temp_in_front_' . Str::random(10) . '.jpg');
        $tempBackPath  = storage_path('app/temp_in_back_' . Str::random(10) . '.jpg');

        $this->saveImageInput($request, 'front', $tempFrontPath);
        $this->saveImageInput($request, 'back', $tempBackPath);

        $fileName = 'office_nid_' . time() . '_' . Str::random(8) . '.pdf';
        $relativePdfPath = 'print_uploads/' . $fileName;
        $fullPdfPath = storage_path('app/public/' . $relativePdfPath);

        if (!file_exists(dirname($fullPdfPath))) {
            @mkdir(dirname($fullPdfPath), 0777, true);
        }

        try {
            $nidPdfService->generateA4NidPdf($tempFrontPath, $tempBackPath, $colorMode, $fullPdfPath);
        } catch (\Exception $e) {
            @unlink($tempFrontPath);
            @unlink($tempBackPath);
            return back()->withInput()->with('error_alert', 'ফাইল প্রসেসিং ত্রুটি: ' . $e->getMessage());
        }

        @unlink($tempFrontPath);
        @unlink($tempBackPath);

        $jobToken = 'PS-' . strtoupper(Str::random(8));

        $job = PrintJob::create([
            'shop_id' => $shop->id,
            'job_token' => $jobToken,
            'customer_name' => $request->input('employee_name', 'অফিস কর্মী'),
            'customer_phone' => null,
            'pickup_reference' => $request->input('pickup_reference'),
            'file_name' => $fileName,
            'original_name' => 'Office_NID_' . date('Ymd_His') . '.pdf',
            'file_path' => $relativePdfPath,
            'file_type' => 'pdf',
            'total_pages' => 1,
            'copies' => $copies,
            'color_mode' => $colorMode,
            'is_duplex' => false,
            'paper_size' => 'A4',
            'page_range' => '1',
            'total_amount' => 0.00,
            'payment_method' => 'cash',
            'payment_trx_id' => 'OFFICE-INTERNAL',
            'payment_status' => 'paid',
            'print_status' => 'queued',
            'is_office_job' => true,
        ]);

        return redirect()->route('customer.status', $jobToken);
    }

    public function jobStatus($jobToken)
    {
        $job = PrintJob::with('shop')->where('job_token', $jobToken)->firstOrFail();
        return view('customer.status', compact('job'));
    }

    public function apiJobStatus($jobToken)
    {
        $job = PrintJob::where('job_token', $jobToken)->firstOrFail();
        return response()->json([
            'job_token' => $job->job_token,
            'payment_status' => $job->payment_status,
            'print_status' => $job->print_status,
            'total_amount' => $job->total_amount,
            'printed_at' => $job->printed_at ? $job->printed_at->format('h:i A') : null,
        ]);
    }

    /**
     * AI-powered document / NID auto-crop API gateway.
     */
    public function autoCropDocument(Request $request)
    {
        $file = $request->file('file') ?? $request->file('image');
        $fileContent = null;
        $fileName = 'document.jpg';

        if ($file && $file->isValid()) {
            $fileContent = file_get_contents($file->getRealPath());
            $fileName = $file->getClientOriginalName() ?: 'document.jpg';
        } elseif ($request->filled('image_data')) {
            $dataUri = $request->input('image_data');
            if (preg_match('/^data:image\/(\w+);base64,/', $dataUri)) {
                $fileContent = base64_decode(substr($dataUri, strpos($dataUri, ',') + 1));
            } else {
                $fileContent = base64_decode($dataUri);
            }
        }

        if (!$fileContent) {
            return response()->json([
                'success' => false,
                'message' => 'কোনো ছবি পাওয়া যায়নি। অনুগ্রহ করে ফাইল আপলোড বা পেস্ট করুন।'
            ], 400);
        }

        $endpointUrl = $this->getAiEndpointUrl('auto-crop-document');
        $aiKey = config('services.python_ai.key', 'penghor_secret_key_121');

        try {
            $response = Http::withHeaders([
                'x-api-key' => $aiKey,
            ])->timeout(30)->attach(
                'file',
                $fileContent,
                $fileName
            )->post($endpointUrl);

            if ($response->successful()) {
                $croppedBinary = $response->body();
                return response()->json([
                    'success' => true,
                    'image_data' => 'data:image/jpeg;base64,' . base64_encode($croppedBinary),
                    'source' => 'python_ai',
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Python AI ক্রপিং প্রসেস করতে ব্যর্থ হয়েছে (স্ট্যাটাস: ' . $response->status() . ')',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Python AI সার্ভারে সংযোগ করা যায়নি: ' . $e->getMessage(),
            ], 200);
        }
    }

    /**
     * AI-powered document / NID 4-corner detection API gateway.
     */
    public function detectDocumentCorners(Request $request)
    {
        $file = $request->file('file') ?? $request->file('image');
        $fileContent = null;
        $fileName = 'document.jpg';

        if ($file && $file->isValid()) {
            $fileContent = file_get_contents($file->getRealPath());
            $fileName = $file->getClientOriginalName() ?: 'document.jpg';
        } elseif ($request->filled('image_data')) {
            $dataUri = $request->input('image_data');
            if (preg_match('/^data:image\/(\w+);base64,/', $dataUri)) {
                $fileContent = base64_decode(substr($dataUri, strpos($dataUri, ',') + 1));
            } else {
                $fileContent = base64_decode($dataUri);
            }
        }

        if (!$fileContent) {
            return response()->json([
                'success' => false,
                'message' => 'কোনো ছবি পাওয়া যায়নি।'
            ], 400);
        }

        $endpointUrl = $this->getAiEndpointUrl('detect-document-corners');
        $aiKey = config('services.python_ai.key', 'penghor_secret_key_121');

        try {
            $response = Http::withHeaders([
                'x-api-key' => $aiKey,
            ])->timeout(30)->attach(
                'file',
                $fileContent,
                $fileName
            )->post($endpointUrl);

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json([
                'success' => false,
                'message' => 'AI কর্নার ডিটেকশন ব্যর্থ হয়েছে (স্ট্যাটাস: ' . $response->status() . ')',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Python AI সার্ভারে সংযোগ পাওয়া যায়নি: ' . $e->getMessage(),
            ], 200);
        }
    }

    /**
     * Resolve the target URL for a given AI endpoint.
     * Supports both direct FastAPI URLs (e.g. http://127.0.0.1:8000/endpoint)
     * and VPS PHP proxy URLs (e.g. http://importentapi.rootvabd.com/python_api/proxy.php?endpoint=...)
     */
    protected function getAiEndpointUrl(string $endpoint): string
    {
        $rawUrl = config('services.python_ai.url', 'http://importentapi.rootvabd.com/python_api/proxy.php');
        $rawUrl = trim($rawUrl);

        // If the URL already contains proxy.php or has query parameters:
        if (str_contains($rawUrl, 'proxy.php') || str_contains($rawUrl, '?endpoint=')) {
            // Strip any existing ?endpoint=... or &endpoint=...
            $baseUrl = preg_replace('/(\?|&)endpoint=[^&]*/', '', $rawUrl);
            $separator = str_contains($baseUrl, '?') ? '&' : '?';
            return $baseUrl . $separator . 'endpoint=' . urlencode($endpoint);
        }

        // Standard direct FastAPI endpoint
        return rtrim($rawUrl, '/') . '/' . ltrim($endpoint, '/');
    }
}
