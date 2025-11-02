<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seller extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'special_status', 'selling_type', 'booth_type', 'days', 'logo', 'is_active', 'notes'
    ];

    protected $casts = [
        'special_status' => 'boolean',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    const SELLING_TYPES = [ /* verkooptypes */ ];
    const BOOTH_TYPES = [ /* kraantypes */ ];
    const DAYS_OPTIONS = [ /* dagen opties */ ];

    // Check of verkoper actief is
    public function isActive()
    {
        return $this->is_active;
    }

    // Check of verkoper partner is
    public function isPartner()
    {
        return $this->special_status;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePartners($query)
    {
        return $query->where('special_status', true);
    }

    // Bepaal of verkoper verwijderd kan worden
    public function canBeDeleted()
    {
        return !$this->is_active;
    }
}
