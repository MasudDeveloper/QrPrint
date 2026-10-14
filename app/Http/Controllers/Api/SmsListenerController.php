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

        // Check if there is a pending print job waiting with this trxId or matching amount
        $job = PrintJob::where('shop_id', $shop->id)
            ->where('payment_status', 'pending')
            ->where(function ($query) use ($trxId, $amount) {
                $query->where('payment_trx_id', $trxId)
                      ->orWhere('total_amount', $amount);
            })
            ->latest()
            ->first();

        if ($job) {
            $job->update([
                'payment_status' => 'paid',
                'payment_trx_id' => $trxId,
                'payment_sender_number' => $sender,
            ]);

            Transaction::create([
                'shop_id' => $shop->id,
                'print_job_id' => $job->id,
                'amount' => $amount,
                'payment_method' => $method,
                'trx_id' => $trxId,
                'sender_number' => $sender,
                'status' => 'completed',
            ]);

            return response()->json([
                'status' => 'matched',
                'job_token' => $job->job_token,
                'message' => 'Payment verified, job released for printing.',
            ]);
        }

        // Record standalone transaction if no immediate job matched
        Transaction::create([
            'shop_id' => $shop->id,
            'amount' => $amount,
            'payment_method' => $method,
            'trx_id' => $trxId,
            'sender_number' => $sender,
            'status' => 'completed',
        ]);

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
