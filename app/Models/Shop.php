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
        'rate_nid_bw',
        'rate_nid_color',
        'has_color_print',
        'has_duplex_print',
        'has_passport_photo',
        'has_nid_print',
        'is_office_mode',
        'api_token',
        'is_active',
        'last_agent_heartbeat_at',
        'last_app_heartbeat_at',
        'subscription_plan',
        'subscription_expires_at',
        'is_lifetime',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_lifetime' => 'boolean',
        'is_office_mode' => 'boolean',
        'subscription_expires_at' => 'datetime',
        'has_color_print' => 'boolean',
        'has_duplex_print' => 'boolean',
        'has_passport_photo' => 'boolean',
        'has_nid_print' => 'boolean',
        'rate_bw_single' => 'decimal:2',
        'rate_bw_double' => 'decimal:2',
        'rate_color_single' => 'decimal:2',
        'rate_color_double' => 'decimal:2',
        'rate_photo_passport' => 'decimal:2',
        'rate_nid_bw' => 'decimal:2',
        'rate_nid_color' => 'decimal:2',
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

    public function subscriptions(): HasMany
    {
        return $this->hasMany(ShopSubscription::class);
    }

    /**
     * Check if shop subscription is currently valid and active
     */
    public function isSubscriptionActive(): bool
    {
        if ($this->is_lifetime) {
            return true;
        }

        if (!$this->subscription_expires_at) {
            // Default free trial logic or newly registered shop: 30 days trial from created_at
            return $this->created_at ? $this->created_at->addDays(30)->isFuture() : true;
        }

        return $this->subscription_expires_at->isFuture();
    }

    /**
     * Get remaining days of current subscription
     */
    public function getDaysRemainingAttribute(): ?int
    {
        if ($this->is_lifetime) {
            return null; // Unlimited
        }

        $expiresAt = $this->subscription_expires_at ?? ($this->created_at ? $this->created_at->addDays(30) : null);
        if (!$expiresAt) {
            return 0;
        }

        return max(0, (int) now()->diffInDays($expiresAt, false));
    }

    /**
     * Get friendly Bangla plan label
     */
    public function getPlanLabelAttribute(): string
    {
        if ($this->is_lifetime) {
            return 'লাইফটাইম আনলিমিটেড (Lifetime)';
        }

        return match ($this->subscription_plan) {
            'monthly_starter' => 'মান্থলি স্টার্টার',
            'yearly_pro' => 'ইয়ারলি প্রো (১ বছর)',
            'lifetime' => 'লাইফটাইম',
            default => 'ফ্রি ট্রায়াল (Free Trial)',
        };
    }
}
