<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'short_code',
        'email',
        'phone',
        'company_name',
        'company_address',
        'bank_name',
        'bank_account_number',
        'bank_routing_number',
        'contact_person',
        'notes',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Scope for active clients
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for inactive clients
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    /**
     * Get the client's full address
     */
    public function getFullAddressAttribute()
    {
        return $this->company_address ?: 'No address provided';
    }

    /**
     * Get the client's bank details
     */
    public function getBankDetailsAttribute()
    {
        if ($this->bank_name && $this->bank_account_number) {
            return $this->bank_name . ' - ****' . substr($this->bank_account_number, -4);
        }
        return 'No bank details';
    }

    /**
     * Set the short_code attribute to uppercase
     */
    public function setShortCodeAttribute($value)
    {
        $this->attributes['short_code'] = strtoupper($value);
    }
}