<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coordinator extends Model
{
    use HasFactory;

    protected $fillable = [
        'coordinator_id',
        'coordinator_name',
        'nic_no',
        'phone_no',
        'bank_name',
        'bank_branch_name',
        'account_number',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Scope for active coordinators
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for inactive coordinators
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    /**
     * Scope for suspended coordinators
     */
    public function scopeSuspended($query)
    {
        return $query->where('status', 'suspended');
    }

    /**
     * Generate coordinator ID automatically
     * Format: COO/{coordinator_number}
     * Example: COO/001, COO/002, COO/003, etc.
     */
    public static function generateCoordinatorId()
    {
        $prefix = 'COO';
        
        // Get the last coordinator ID
        $lastCoordinator = self::where('coordinator_id', 'like', $prefix . '/%')
            ->orderBy('coordinator_id', 'desc')
            ->first();

        if ($lastCoordinator) {
            // Extract the number part and increment
            $lastNumber = (int) substr($lastCoordinator->coordinator_id, -3);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return sprintf('%s/%03d', $prefix, $nextNumber);
    }

    /**
     * Get the coordinator's bank details (masked)
     */
    public function getMaskedAccountNumberAttribute()
    {
        if ($this->account_number) {
            return '****' . substr($this->account_number, -4);
        }
        return 'N/A';
    }

    /**
     * Get the coordinator's full bank details
     */
    public function getBankDetailsAttribute()
    {
        return $this->bank_name . ' - ' . $this->bank_branch_name . ' (' . $this->getMaskedAccountNumberAttribute() . ')';
    }

    /**
     * Get the coordinator's status badge class
     */
    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            'active' => 'status-active',
            'inactive' => 'status-inactive',
            'suspended' => 'status-suspended',
            default => 'status-inactive'
        };
    }

    /**
     * Get the coordinator's status display text
     */
    public function getStatusDisplayAttribute()
    {
        return ucfirst($this->status);
    }
}