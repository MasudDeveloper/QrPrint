<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\PrintJob;
use App\Models\Transaction;
use Illuminate\Http\Request;

class SmsListenerController extends Controller
{
    public function receiveSms(Request $request)
    {
        $token = $request->bearerToken() ?: $request->header('X-Agent-Token');
        $shop = Shop::where('api_token', $token)->first();
        if (!$shop) {
            return response()->json(['error' => 'Invalid shop token'], 401);
        }

        $shop->update(['last_app_heartbeat_at' => now()]);

        $trxId = strtoupper(trim($request->input('trx_id', '')));
        $amount = (float) $request->input('amount', 0);
        $sender = $request->input('sender_phone', '');
        $method = strtolower($request->input('method', 'bkash')); // bkash or nagad

        if (empty($trxId) || $amount <= 0) {
            return response()->json(['error' => 'Missing transaction data'], 400);
        }

        // Check if this TrxID is already recorded in transactions
        $existingTx = Transaction::where('shop_id', $shop->id)
            ->where('trx_id', $trxId)
            ->first();

        if ($existingTx && $existingTx->print_job_id) {
            return response()->json([
                'status' => 'already_used',
                'message' => 'Transaction ID already recorded and linked to a job.',
            ]);
        }

        // Check if there is a pending print job waiting for this exact TrxID, 
        // OR submitted by the same sender phone where amount >= total_amount
        $job = PrintJob::where('shop_id', $shop->id)
            ->where('payment_status', 'pending')
            ->where(function ($query) use ($trxId, $sender, $amount) {
                $query->where('payment_trx_id', $trxId);
                if (!empty($sender)) {
                    $cleanSender = preg_replace('/[^0-9]/', '', $sender);
                    $last8 = substr($cleanSender, -8);
                    $query->orWhere(function ($subQ) use ($last8, $amount) {
                        $subQ->where('customer_phone', 'like', "%{$last8}%")
                             ->where('total_amount', '<=', $amount);
                    });
                }
            })
            ->where('total_amount', '<=', $amount) // Amount must be sufficient!
            ->latest()
            ->first();

        if ($job) {
            $job->update([
                'payment_status' => 'paid',
                'payment_trx_id' => $trxId,
                'payment_sender_number' => $sender ?: $job->payment_sender_number,
            ]);

            if ($existingTx) {
                $existingTx->update([
                    'print_job_id' => $job->id,
                    'amount' => $amount,
                    'sender_number' => $sender ?: $existingTx->sender_number,
                    'status' => 'completed',
                ]);
            } else {
                Transaction::create([
                    'shop_id' => $shop->id,
                    'print_job_id' => $job->id,
                    'amount' => $amount,
                    'payment_method' => $method,
                    'trx_id' => $trxId,
                    'sender_number' => $sender,
                    'status' => 'completed',
                ]);
            }

            return response()->json([
                'status' => 'matched',
                'job_token' => $job->job_token,
                'message' => 'Payment verified, job released for printing.',
            ]);
        }

        if (!$existingTx) {
            // Record standalone transaction if no immediate job matched
            Transaction::create([
                'shop_id' => $shop->id,
                'amount' => $amount,
                'payment_method' => $method,
                'trx_id' => $trxId,
                'sender_number' => $sender,
                'status' => 'completed',
            ]);
        }

        return response()->json([
            'status' => 'recorded',
            'message' => 'Transaction logged, awaiting customer claim.',
        ]);
    }

    public function appHeartbeat(Request $request)
    {
        $token = $request->bearerToken() ?: $request->header('X-Agent-Token');
        $shop = Shop::where('api_token', $token)->first();
        if (!$shop) {
            return response()->json(['error' => 'Invalid shop token'], 401);
        }

        $shop->update(['last_app_heartbeat_at' => now()]);

        return response()->json([
            'status' => 'ok',
            'shop_name' => $shop->name,
            'server_time' => now()->format('Y-m-d H:i:s'),
            'message' => 'Android SMS App connected successfully.',
        ]);
    }
}
