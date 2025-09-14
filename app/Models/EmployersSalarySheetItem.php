<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployersSalarySheetItem extends Model
{
    use HasFactory;

    protected $table = 'employers_salary_sheet_item';

    protected $fillable = [
        'no',
        'location',
        'position_id',
        'attendance_data',
        'payment_data',
        'coordinator_details',
        'job_id',
        'sheet_no',
    ];

    protected $casts = [
        'attendance_data' => 'array',
        'payment_data' => 'array',
        'coordinator_details' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Generate unique item number
     * Format: ITM/{year}/{month}/{sequence}
     */
    public static function generateItemNumber()
    {
        $prefix = 'ITM';
        $year = date('Y');
        $month = date('n');
        
        // Get the last item number for this month/year
        $lastItem = self::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('no', 'desc')
            ->first();

        if ($lastItem) {
            // Extract the sequence part and increment
            $lastSequence = (int) substr($lastItem->no, -3);
            $nextSequence = $lastSequence + 1;
        } else {
            $nextSequence = 1;
        }

        return sprintf('%s/%d/%02d/%03d', $prefix, $year, $month, $nextSequence);
    }

    /**
     * Get the position
     */
    public function position()
    {
        return $this->belongsTo(PromoterPosition::class, 'position_id');
    }

    /**
     * Get the job
     */
    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    /**
     * Get the salary sheet
     */
    public function salarySheet()
    {
        return $this->belongsTo(SalarySheet::class, 'sheet_no', 'sheet_no');
    }

    /**
     * Get attendance total from attendance_data
     */
    public function getAttendanceTotalAttribute()
    {
        return $this->attendance_data['total'] ?? 0;
    }

    /**
     * Get attendance amount from attendance_data
     */
    public function getAttendanceAmountAttribute()
    {
        return $this->attendance_data['amount'] ?? 0;
    }

    /**
     * Get base amount from payment_data
     */
    public function getBaseAmountAttribute()
    {
        return $this->payment_data['amount'] ?? 0;
    }

    /**
     * Get food allowance from payment_data
     */
    public function getFoodAllowanceAttribute()
    {
        return $this->payment_data['food_allowance'] ?? 0;
    }

    /**
     * Get expenses from payment_data
     */
    public function getExpensesAttribute()
    {
        return $this->payment_data['expenses'] ?? 0;
    }

    /**
     * Get accommodation allowance from payment_data
     */
    public function getAccommodationAllowanceAttribute()
    {
        return $this->payment_data['accommodation_allowance'] ?? 0;
    }

    /**
     * Get hold for weeks from payment_data
     */
    public function getHoldForWeeksAttribute()
    {
        return $this->payment_data['hold_for_weeks'] ?? 0;
    }

    /**
     * Get net amount from payment_data
     */
    public function getNetAmountAttribute()
    {
        return $this->payment_data['net_amount'] ?? 0;
    }

    /**
     * Get coordinator ID from coordinator_details
     */
    public function getCoordinatorIdAttribute()
    {
        return $this->coordinator_details['coordinator_id'] ?? null;
    }

    /**
     * Get coordinator name from coordinator_details
     */
    public function getCoordinatorNameAttribute()
    {
        return $this->coordinator_details['current_coordinator'] ?? null;
    }

    /**
     * Get coordinator amount from coordinator_details
     */
    public function getCoordinatorAmountAttribute()
    {
        return $this->coordinator_details['amount'] ?? 0;
    }

    /**
     * Get daily attendance as array
     */
    public function getDailyAttendanceAttribute()
    {
        return $this->attendance_data['attendance'] ?? [];
    }

    /**
     * Calculate total earnings (base amount + allowances)
     */
    public function getTotalEarningsAttribute()
    {
        return ($this->base_amount ?? 0) + 
               ($this->food_allowance ?? 0) + 
               ($this->accommodation_allowance ?? 0) + 
               ($this->coordinator_amount ?? 0);
    }

    /**
     * Calculate total deductions
     */
    public function getTotalDeductionsAttribute()
    {
        return ($this->expenses ?? 0) + ($this->hold_for_weeks ?? 0);
    }

    /**
     * Get formatted attendance data for display
     */
    public function getFormattedAttendanceDataAttribute()
    {
        $attendance = $this->daily_attendance;
        $formatted = [];
        
        foreach ($attendance as $date => $value) {
            $formatted[] = [
                'date' => $date,
                'value' => $value,
                'formatted_date' => \Carbon\Carbon::parse($date)->format('M d'),
                'status' => $value > 0 ? 'Present' : 'Absent'
            ];
        }
        
        return $formatted;
    }
}
