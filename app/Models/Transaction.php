<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $fillable = [
        'shop_id',
        'print_job_id',
        'amount',
        'payment_method',
        'trx_id',
        'sender_number',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function printJob(): BelongsTo
    {
        return $this->belongsTo(PrintJob::class);
    }
}
