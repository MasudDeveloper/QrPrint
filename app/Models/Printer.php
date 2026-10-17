<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Printer extends Model
{
    protected $fillable = [
        'shop_id',
        'name',
        'system_printer_name',
        'type',
        'is_default',
        'status',
        'last_heartbeat_at',
    ];

    protected $casts = [
        'shop_id' => 'integer',
        'is_default' => 'boolean',
        'last_heartbeat_at' => 'datetime',
    ];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }
}
