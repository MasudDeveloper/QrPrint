<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Shop extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'address',
        'phone',
        'bkash_number',
        'nagad_number',
        'rate_bw_single',
        'rate_bw_double',
        'rate_color_single',
        'rate_color_double',
        'rate_photo_passport',
        'has_color_print',
        'has_duplex_print',
        'has_passport_photo',
        'api_token',
        'is_active',
        'last_agent_heartbeat_at',
        'last_app_heartbeat_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'has_color_print' => 'boolean',
        'has_duplex_print' => 'boolean',
        'has_passport_photo' => 'boolean',
        'rate_bw_single' => 'decimal:2',
        'rate_bw_double' => 'decimal:2',
        'rate_color_single' => 'decimal:2',
        'rate_color_double' => 'decimal:2',
        'rate_photo_passport' => 'decimal:2',
        'last_agent_heartbeat_at' => 'datetime',
        'last_app_heartbeat_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($shop) {
            if (empty($shop->api_token)) {
                $shop->api_token = Str::random(40);
            }
            if (empty($shop->slug)) {
                $shop->slug = Str::slug($shop->name) . '-' . Str::lower(Str::random(5));
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function printers(): HasMany
    {
        return $this->hasMany(Printer::class);
    }

    public function printJobs(): HasMany
    {
        return $this->hasMany(PrintJob::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
