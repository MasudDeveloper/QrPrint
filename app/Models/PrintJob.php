<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class PrintJob extends Model
{
    protected $fillable = [
        'shop_id',
        'job_token',
        'customer_name',
        'customer_phone',
        'file_name',
        'original_name',
        'file_path',
        'file_type',
        'total_pages',
        'copies',
        'color_mode',
        'is_duplex',
        'paper_size',
        'page_range',
        'total_amount',
        'payment_method',
        'payment_trx_id',
        'payment_sender_number',
        'payment_status',
        'print_status',
        'is_deleted_from_disk',
        'printed_at',
    ];

    protected $casts = [
        'is_duplex' => 'boolean',
        'is_deleted_from_disk' => 'boolean',
        'total_amount' => 'decimal:2',
        'printed_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($job) {
            if (empty($job->job_token)) {
                $job->job_token = 'PS-' . strtoupper(Str::random(8));
            }
        });
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }
}
