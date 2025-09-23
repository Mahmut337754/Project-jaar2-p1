<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    protected $fillable = [
        'event_id',
        'name',
        'description',
        'day',
        'admission_time',
        'price',
        'total_quantity',
        'sold_quantity',
        'is_active',
        'features'
    ];

    protected $casts = [
        'admission_time' => 'datetime:H:i',
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'features' => 'array'
    ];

    /**
     * Get the event this ticket belongs to
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get all purchases for this ticket
     */
    public function purchases(): HasMany
    {
        return $this->hasMany(TicketPurchase::class);
    }

    /**
     * Get available quantity (calculated field from migration)
     */
    public function getAvailableQuantityAttribute(): int
    {
        return $this->total_quantity - $this->sold_quantity;
    }

    /**
     * Check if tickets are available
     */
    public function isAvailable(): bool
    {
        return $this->is_active && $this->available_quantity > 0;
    }

    /**
     * Check if ticket is sold out
     */
    public function isSoldOut(): bool
    {
        return $this->sold_quantity >= $this->total_quantity;
    }

    /**
     * Get formatted admission time
     */
    public function getFormattedAdmissionTimeAttribute(): string
    {
        return $this->admission_time->format('H:i');
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute(): string
    {
        return '€' . number_format((float) $this->price, 2);
    }

    /**
     * Get day in Dutch
     */
    public function getDayInDutchAttribute(): string
    {
        return $this->day === 'saturday' ? 'Zaterdag' : 'Zondag';
    }

    /**
     * Scope for available tickets
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_active', true)
                    ->whereRaw('sold_quantity < total_quantity');
    }

    /**
     * Scope for specific day
     */
    public function scopeForDay($query, $day)
    {
        return $query->where('day', $day);
    }
}
