<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class SalarySheet extends Model
{
    use HasFactory;

    protected $table = 'salary_sheet';

    protected $fillable = [
        'sheet_no',
        'job_id',
        'status',
        'location',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Generate salary sheet number automatically
     * Format: SAL/{year}/{month}/{sequence}
     * Example: SAL/2025/01/001, SAL/2025/02/001, etc.
     */
    public static function generateSheetNumber()
    {
        $prefix = 'SAL';
        $year = date('Y');
        $month = date('n');

        // Create the base pattern for this month/year
        $basePattern = sprintf('%s/%d/%02d/', $prefix, $year, $month);

        // Get all sheet numbers that start with this pattern
        $existingSheets = self::where('sheet_no', 'like', $basePattern . '%')
            ->orderBy('sheet_no', 'desc')
            ->pluck('sheet_no')
            ->toArray();

        if (empty($existingSheets)) {
            // No sheets exist for this month/year, start with 001
            $nextSequence = 1;
        } else {
            // Find the highest sequence number
            $maxSequence = 0;
            foreach ($existingSheets as $sheetNo) {
                // Extract the sequence part (last 3 digits)
                $sequence = (int) substr($sheetNo, -3);
                $maxSequence = max($maxSequence, $sequence);
            }
            $nextSequence = $maxSequence + 1;
        }

        return sprintf('%s/%d/%02d/%03d', $prefix, $year, $month, $nextSequence);
    }

    /**
     * Get the job
     */
    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    /**
     * Get all salary sheet items for this sheet
     */
    public function items()
    {
        return $this->hasMany(EmployersSalarySheetItem::class, 'sheet_no', 'sheet_no');
    }

    /**
     * Get the user who created this salary sheet
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the status badge class
     */
    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            'draft' => 'status-draft',
            'approved' => 'status-approved',
            'paid' => 'status-paid',
            default => 'status-draft'
        };
    }

    /**
     * Get the status display text
     */
    public function getStatusDisplayAttribute()
    {
        return ucfirst($this->status);
    }

    /**
     * Get formatted month name from sheet number
     */
    public function getMonthNameAttribute()
    {
        // Extract month from sheet number like SAL/2025/01/001
        $parts = explode('/', $this->sheet_no);
        if (count($parts) >= 3) {
            $month = (int) $parts[2];
            $months = [
                1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
                5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
                9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
            ];
            return $months[$month] ?? 'Unknown';
        }
        return 'Unknown';
    }

    /**
     * Get year from sheet number
     */
    public function getYearAttribute()
    {
        // Extract year from sheet number like SAL/2025/01/001
        $parts = explode('/', $this->sheet_no);
        if (count($parts) >= 2) {
            return (int) $parts[1];
        }
        return date('Y');
    }

    /**
     * Calculate total amount from all items
     */
    public function getTotalAmountAttribute()
    {
        return $this->items->sum(function ($item) {
            return $item->payment_data['net_amount'] ?? 0;
        });
    }

    /**
     * Calculate total attendance amount from all items
     */
    public function getTotalAttendanceAmountAttribute()
    {
        return $this->items->sum(function ($item) {
            return $item->attendance_data['amount'] ?? 0;
        });
    }

    /**
     * Get all unique positions in this salary sheet
     */
    public function getPositionsAttribute()
    {
        return $this->items->map(function ($item) {
            return $item->position;
        })->unique('id');
    }

    /**
     * Scope for draft salary sheets
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope for approved salary sheets
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for paid salary sheets
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }
}
