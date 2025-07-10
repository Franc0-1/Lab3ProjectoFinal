<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'phone',
        'email',
        'address',
        'neighborhood',
        'latitude',
        'longitude',
        'frequent_customer',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'frequent_customer' => 'boolean',
    ];

    /**
     * Get the orders for the customer.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get recent orders for the customer.
     */
    public function recentOrders(): HasMany
    {
        return $this->hasMany(Order::class)
                    ->orderBy('created_at', 'desc')
                    ->limit(5);
    }

    /**
     * Scope a query to only include frequent customers.
     */
    public function scopeFrequent($query)
    {
        return $query->where('frequent_customer', true);
    }

    /**
     * Get customer's full address.
     */
    public function getFullAddressAttribute()
    {
        $parts = array_filter([$this->address, $this->neighborhood]);
        return implode(', ', $parts);
    }
}
