<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promoter extends Model
{
    use HasFactory;

    protected $fillable = [
        'promoter_id',
        'position_id',
        'promoter_name',
        'identity_card_no',
        'phone_no',
        'bank_name',
        'bank_branch_name',
        'bank_account_number',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Scope for active promoters
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for inactive promoters
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    /**
     * Scope for suspended promoters
     */
    public function scopeSuspended($query)
    {
        return $query->where('status', 'suspended');
    }

    /**
     * Generate promoter ID automatically
     * Format: {year}/MIND/{promoter_number}
     * Example: 2025/MIND/0001, 2025/MIND/0002, etc.
     */
    public static function generatePromoterId()
    {
        $year = date('Y'); // 4-digit year
        $companyCode = 'MIND';
        
        // Get the last promoter ID for this year
        $lastPromoter = self::where('promoter_id', 'like', $year . '/' . $companyCode . '/%')
            ->orderBy('promoter_id', 'desc')
            ->first();

        if ($lastPromoter) {
            // Extract the number part and increment
            $lastNumber = (int) substr($lastPromoter->promoter_id, -4);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return sprintf('%s/%s/%04d', $year, $companyCode, $nextNumber);
    }

    /**
     * Get the promoter's bank details (masked)
     */
    public function getMaskedBankAccountAttribute()
    {
        if ($this->bank_account_number) {
            return '****' . substr($this->bank_account_number, -4);
        }
        return 'N/A';
    }

    /**
     * Get the promoter's full bank details
     */
    public function getBankDetailsAttribute()
    {
        return $this->bank_name . ' - ' . $this->bank_branch_name . ' (' . $this->getMaskedBankAccountAttribute() . ')';
    }

    /**
     * Get the promoter's position
     */
    public function position()
    {
        return $this->belongsTo(PromoterPosition::class);
    }
}