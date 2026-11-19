<?php

namespace App\Http\Controllers;

use App\Models\PrintJob;
use App\Models\Transaction;
use App\Services\BkashPgwService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function initiateBkash($jobToken, BkashPgwService $bkash)
    {
        $job = PrintJob::where('job_token', $jobToken)->firstOrFail();

        if ($job->payment_status === 'paid') {
            return redirect()->route('customer.status', $job->job_token)->with('info', 'এই অর্ডারটির বিল ইতিমধ্যে পরিশোধিত হয়েছে।');
        }

        if (!BkashPgwService::isEnabled()) {
            return redirect()->route('customer.status', $job->job_token)->with('error_alert', 'বিকাশ অনলাইন পেমেন্ট গেটওয়ে বর্তমানে নিষ্ক্রিয় রয়েছে। অনুগ্রহ করে ম্যানুয়াল পেমেন্ট বা ক্যাশ ব্যবহার করুন।');
        }

        $callbackUrl = route('payment.bkash.callback', ['jobToken' => $job->job_token]);
        $result = $bkash->createPayment($job->total_amount, $job->job_token, $callbackUrl);

        if ($result['status'] === 'success') {
            session(['bkash_payment_id_' . $job->job_token => $result['paymentID']]);
            return redirect()->away($result['bkashURL']);
        }

        return redirect()->route('customer.status', $job->job_token)->with('error_alert', 'বিকাশ পেমেন্ট শুরু করতে সমস্যা: ' . $result['message']);
    }

    public function bkashCallback(Request $request, $jobToken, BkashPgwService $bkash)
    {
        $job = PrintJob::where('job_token', $jobToken)->firstOrFail();
        $status = $request->input('status');
        $paymentId = $request->input('paymentID') ?: session('bkash_payment_id_' . $job->job_token);

        if ($status === 'success' && $paymentId) {
            $execResult = $bkash->executePayment($paymentId);

            if ($execResult['status'] === 'success') {
                $trxId = $execResult['trxID'];
                $paidAmount = $execResult['amount'];
                $senderPhone = $execResult['customerMsisdn'];

                // Mark job paid
                $job->update([
                    'payment_status' => 'paid',
                    'payment_trx_id' => $trxId,
                    'payment_sender_number' => $senderPhone ?: $job->payment_sender_number,
                ]);

                // Create or update transaction
                Transaction::create([
                    'shop_id' => $job->shop_id,
                    'print_job_id' => $job->id,
                    'amount' => $paidAmount,
                    'payment_method' => 'bkash',
                    'trx_id' => $trxId,
                    'sender_number' => $senderPhone,
                    'status' => 'completed',
                ]);

                return redirect()->route('customer.status', $job->job_token)->with('success', '✅ বিকাশ পেমেন্ট সফলভাবে সম্পন্ন হয়েছে! আপনার ডকুমেন্ট স্বয়ংক্রিয়ভাবে প্রিন্ট হচ্ছে...');
            }

            return redirect()->route('customer.status', $job->job_token)->with('error_alert', 'বিকাশ পেমেন্ট কনফার্মেশন ব্যর্থ: ' . $execResult['message']);
        }

        if ($status === 'cancel') {
            return redirect()->route('customer.status', $job->job_token)->with('info', 'বিকাশ পেমেন্ট বাতিল করা হয়েছে। আপনি চাইলে পুনরায় চেষ্টা করতে পারেন বা কাউন্টারে ক্যাশ দিতে পারেন।');
        }

        return redirect()->route('customer.status', $job->job_token)->with('error_alert', 'পেমেন্ট সম্পন্ন হয়নি। কোনো সমস্যা হলে কাউন্টারে জানান।');
    }
}

