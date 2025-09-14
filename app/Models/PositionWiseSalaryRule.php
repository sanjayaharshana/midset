<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PositionWiseSalaryRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'position_id',
        'job_id',
        'amount',
        'description',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the position that owns the salary rule
     */
    public function position()
    {
        return $this->belongsTo(PromoterPosition::class);
    }

    /**
     * Get the job that owns the salary rule
     */
    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    /**
     * Scope for active salary rules
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for inactive salary rules
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }
}