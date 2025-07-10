<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'pizza_id',
        'quantity',
        'unit_price',
        'total_price',
        'customizations'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'customizations' => 'array'
    ];

    // Relationships
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function pizza(): BelongsTo
    {
        return $this->belongsTo(Pizza::class);
    }

    // Model Events
    protected static function booted()
    {
        static::creating(function (OrderItem $orderItem) {
            // Calculate total_price if not provided
            if (!$orderItem->total_price) {
                $orderItem->total_price = $orderItem->quantity * $orderItem->unit_price;
            }
        });

        static::updating(function (OrderItem $orderItem) {
            // Recalculate total_price if quantity or unit_price changed
            if ($orderItem->isDirty(['quantity', 'unit_price'])) {
                $orderItem->total_price = $orderItem->quantity * $orderItem->unit_price;
            }
        });
    }

    // Scopes
    public function scopeForOrder(Builder $query, int $orderId): Builder
    {
        return $query->where('order_id', $orderId);
    }

    public function scopeForPizza(Builder $query, int $pizzaId): Builder
    {
        return $query->where('pizza_id', $pizzaId);
    }

    // Helper Methods
    public function calculateTotalPrice(): float
    {
        return $this->quantity * $this->unit_price;
    }

    public function getFormattedTotalPriceAttribute(): string
    {
        return '$' . number_format($this->total_price, 2);
    }

    public function getFormattedUnitPriceAttribute(): string
    {
        return '$' . number_format($this->unit_price, 2);
    }

    // Backwards compatibility
    public function getSubtotalAttribute(): float
    {
        return $this->total_price;
    }
}
