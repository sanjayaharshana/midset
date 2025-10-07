<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Job extends Model
{
    use HasFactory;

    protected $table = 'custom_jobs';

    protected $fillable = [
        'job_number',
        'job_name',
        'description',
        'client_id',
        'officer_name',
        'reporter_officer_name',
        'officer_id',
        'reporter_id',
        'status',
        'start_date',
        'end_date',
        'default_coordinator_fee',
        'default_hold_for_8_weeks',
        'default_food_allowance',
        'default_accommodation_allowance',
        'default_expenses',
        'default_location',
        'location_notes',
        'allowance',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'allowance' => 'array',
    ];

    /**
     * Get the client that owns the job.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the officer assigned to the job.
     */
    public function officer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'officer_id');
    }

    /**
     * Get the reporter assigned to the job.
     */
    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    /**
     * Scope for pending jobs
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for in progress jobs
     */
    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    /**
     * Scope for completed jobs
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for cancelled jobs
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    /**
     * Generate job number automatically
     */
    public static function generateJobNumber($clientId)
    {
        $client = Client::find($clientId);
        if (!$client) {
            throw new \Exception('Client not found');
        }

        $year = date('y'); // 2-digit year
        $clientCode = $client->short_code;
        
        // Get all job numbers for this year and find the highest sequential number
        $jobs = self::where('job_number', 'like', $year . '/%')
            ->pluck('job_number')
            ->toArray();

        $maxNumber = 0;
        foreach ($jobs as $jobNumber) {
            // Extract the number part (last 3 digits)
            $number = (int) substr($jobNumber, -3);
            if ($number > $maxNumber) {
                $maxNumber = $number;
            }
        }

        $nextNumber = $maxNumber + 1;

        return sprintf('%s/%s/%03d', $year, $clientCode, $nextNumber);
    }

    /**
     * Get the job duration in days
     */
    public function getDurationAttribute()
    {
        if ($this->start_date && $this->end_date) {
            return $this->start_date->diffInDays($this->end_date);
        }
        return null;
    }

    /**
     * Check if job is overdue
     */
    public function getIsOverdueAttribute()
    {
        if ($this->end_date && $this->status !== 'completed' && $this->status !== 'cancelled') {
            return $this->end_date->isPast();
        }
        return false;
    }
}