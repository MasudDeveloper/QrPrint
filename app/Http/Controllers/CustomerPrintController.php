<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\PrintJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CustomerPrintController extends Controller
{
    public function showUploadPage($slug)
    {
        $shop = Shop::where('slug', $slug)->firstOrFail();
        if (!$shop->is_active) {
            return response()->view('customer.shop_closed', compact('shop'), 403);
        }

        return view('customer.upload', compact('shop'));
    }

    public function submitJob(Request $request, $slug)
    {
        $shop = Shop::where('slug', $slug)->firstOrFail();

        $request->validate([
            'document' => 'required|file|max:51200|mimes:pdf,doc,docx,jpg,jpeg,png',
            'color_mode' => 'required|in:bw,color',
            'is_duplex' => 'nullable',
            'copies' => 'required|integer|min:1|max:100',
            'paper_size' => 'nullable|string',
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

        // Store file
        $fileName = 'job_' . time() . '_' . Str::random(8) . '.' . $extension;
        $path = $file->storeAs('print_uploads', $fileName, 'public');

        $jobToken = 'PS-' . strtoupper(Str::random(8));

        $job = PrintJob::create([
            'shop_id' => $shop->id,
            'job_token' => $jobToken,
            'customer_name' => $request->input('customer_name', 'কাস্টমার'),
            'customer_phone' => $request->customer_phone,
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
            'payment_method' => $request->payment_method,
            'payment_trx_id' => $request->payment_trx_id,
            'payment_status' => $request->payment_method === 'cash' ? 'pending' : ($request->filled('payment_trx_id') ? 'paid' : 'pending'),
            'print_status' => 'queued',
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
}
