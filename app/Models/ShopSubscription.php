<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShopSubscription extends Model
{
    protected $fillable = [
        'shop_id',
        'plan',
        'plan_name',
        'amount',
        'payment_method',
        'transaction_id',
        'sender_number',
        'status',
        'approved_at',
        'approved_by',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
