<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SystemErrorLog extends Model
{
    protected $fillable = [
        'level',
        'error_type',
        'message',
        'file',
        'line',
        'url',
        'method',
        'ip_address',
        'user_agent',
        'payload',
        'stack_trace',
        'shop_id',
        'user_id',
        'status',
        'error_hash',
        'occurrences_count',
        'last_seen_at',
        'resolved_at',
        'resolved_by',
    ];

    protected $casts = [
        'line' => 'integer',
        'occurrences_count' => 'integer',
        'last_seen_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function resolver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    public function scopeUnresolved($query)
    {
        return $query->where('status', 'unresolved');
    }

    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }

    public function scopeCritical($query)
    {
        return $query->whereIn('level', ['emergency', 'alert', 'critical']);
    }
}
