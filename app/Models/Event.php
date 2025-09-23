<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    protected $fillable = [
        'name',
        'description',
        'location',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'is_active',
        'status',
        'base_price',
        'image_url',
        'additional_info'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'is_active' => 'boolean',
        'base_price' => 'decimal:2',
        'additional_info' => 'array'
    ];

    /**
     * Get all tickets for this event
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * Get all ticket purchases for this event
     */
    public function ticketPurchases(): HasMany
    {
        return $this->hasMany(TicketPurchase::class);
    }

    /**
     * Get active tickets for this event
     */
    public function activeTickets(): HasMany
    {
        return $this->hasMany(Ticket::class)->where('is_active', true);
    }

    /**
     * Check if event is upcoming
     */
    public function isUpcoming(): bool
    {
        return $this->status === 'upcoming' && $this->start_date > now()->toDateString();
    }

    /**
     * Check if event is ongoing
     */
    public function isOngoing(): bool
    {
        return $this->status === 'ongoing' || 
               ($this->start_date <= now()->toDateString() && $this->end_date >= now()->toDateString());
    }

    /**
     * Get total tickets sold for this event
     */
    public function getTotalTicketsSoldAttribute(): int
    {
        return $this->tickets()->sum('sold_quantity');
    }

    /**
     * Get total revenue for this event
     */
    public function getTotalRevenueAttribute(): float
    {
        return $this->ticketPurchases()
                   ->where('status', 'confirmed')
                   ->sum('total_price');
    }

    /**
     * Get total revenue for this event (method version)
     */
    public function totalRevenue(): float
    {
        return $this->ticketPurchases()->sum('total_price');
    }
}
