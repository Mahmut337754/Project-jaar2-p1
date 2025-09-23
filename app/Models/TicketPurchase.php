<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketPurchase extends Model
{
    protected $fillable = [
        'user_id',
        'ticket_id',
        'event_id',
        'quantity',
        'unit_price',
        'total_price',
        'purchase_reference',
        'status',
        'buyer_name',
        'buyer_email',
        'buyer_phone',
        'attendee_details',
        'purchased_at'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'attendee_details' => 'array',
        'purchased_at' => 'datetime'
    ];

    /**
     * Get the user who purchased the ticket
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the ticket that was purchased
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * Get the event for this purchase
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Generate unique purchase reference
     */
    public static function generateReference(): string
    {
        return 'SN-' . date('Y') . '-' . strtoupper(substr(uniqid(), -8));
    }

    /**
     * Check if purchase is confirmed
     */
    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed';
    }

    /**
     * Check if purchase can be cancelled
     */
    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'confirmed']) && 
               $this->event->start_date > now()->addDays(7); // Can cancel up to 7 days before event
    }

    /**
     * Get formatted total price
     */
    public function getFormattedTotalPriceAttribute(): string
    {
        return '€' . number_format((float) $this->total_price, 2);
    }

    /**
     * Scope for confirmed purchases
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    /**
     * Scope for user purchases
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
