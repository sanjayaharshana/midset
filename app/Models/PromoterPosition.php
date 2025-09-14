<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromoterPosition extends Model
{
    use HasFactory;

    protected $fillable = [
        'position_name',
        'description',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Scope for active positions
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for inactive positions
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    /**
     * Get the position's status badge class
     */
    public function getStatusBadgeClassAttribute()
    {
        return $this->status === 'active' ? 'status-active' : 'status-inactive';
    }

    /**
     * Get the position's status display text
     */
    public function getStatusDisplayAttribute()
    {
        return ucfirst($this->status);
    }
}