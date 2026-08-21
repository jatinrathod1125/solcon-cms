<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MarketingOrder extends Model
{
    protected $fillable = [
        'order_number',
        'party_name',
        'city',
        'coupon',
        'vehicle_number',
        'order_date',
        'priority',
        'status',
        'availability',
        'remarks',
        'created_by',
        'approved_by',
        'approved_at',
        'completed_at',
        'cancelled_at',
        'cancel_reason',
        'sort_order',
        'is_edited',
    ];

    protected $casts = [
        'order_date' => 'date',
        'delivery_date' => 'date',
        'approved_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'sort_order' => 'integer',
        'is_edited' => 'boolean',
    ];

    /**
     * Priority options with labels and colors.
     */
    public const PRIORITIES = [
        'low' => ['label' => 'Low', 'color' => 'green', 'icon' => '🟢'],
        'medium' => ['label' => 'Medium', 'color' => 'yellow', 'icon' => '🟡'],
        'high' => ['label' => 'High', 'color' => 'red', 'icon' => '🔴'],
        'urgent' => ['label' => 'Urgent', 'color' => 'orange', 'icon' => '🔥'],
    ];

    /**
     * Status options with labels and colors.
     */
    public const STATUSES = [
        'pending' => ['label' => 'Pending', 'color' => '#f59e0b', 'bg' => '#fef3c7'],
        'in_progress' => ['label' => 'In Progress', 'color' => '#3b82f6', 'bg' => '#dbeafe'],
        'completed' => ['label' => 'Completed', 'color' => '#10b981', 'bg' => '#d1fae5'],
        'cancelled' => ['label' => 'Cancelled', 'color' => '#ef4444', 'bg' => '#fee2e2'],
    ];

    // ─── Relationships ───────────────────────────

    /**
     * Get the order items.
     */
    public function items(): HasMany
    {
        return $this->hasMany(MarketingOrderItem::class);
    }

    /**
     * Get the user who created this order.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who approved this order.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // ─── Scopes ──────────────────────────────────

    public function scopeApproved($query)
    {
        return $query->whereNotNull('approved_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    /**
     * Scope a query to include orders belonging to a specific brand context.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \App\Models\Brand|int|string|null  $brand
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForBrand($query, $brand = null)
    {
        $brandId = $brand instanceof Brand ? $brand->id : $brand;
        if (!$brandId) {
            return $query;
        }

        return $query->where(function ($q) use ($brandId) {
            // Must not contain items belonging to a different brand
            $q->whereDoesntHave('items', function ($itemQ) use ($brandId) {
                $itemQ->where(function ($iQ) use ($brandId) {
                    $iQ->whereHas('grade', fn ($gQ) => $gQ->whereNotNull('brand_id')->where('brand_id', '!=', $brandId))
                       ->orWhereHas('color', fn ($cQ) => $cQ->whereNotNull('brand_id')->where('brand_id', '!=', $brandId));
                });
            })
            // And must have items matching this brand (or common/epoxy items)
            ->whereHas('items', function ($itemQ) use ($brandId) {
                $itemQ->where(function ($iQ) use ($brandId) {
                    $iQ->whereHas('grade', fn ($gQ) => $gQ->forBrand($brandId))
                       ->orWhereHas('color', fn ($cQ) => $cQ->forBrand($brandId))
                       ->orWhere('department_code', 'EPX');
                });
            });
        });
    }

    /**
     * Scope a query to include orders for the current session brand.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForCurrentBrand($query)
    {
        $currentBrand = function_exists('currentBrand') ? currentBrand() : null;
        return $this->scopeForBrand($query, $currentBrand?->id);
    }

    /**
     * Get the brand associated with this order.
     */
    public function getBrandAttribute(): ?Brand
    {
        foreach ($this->items as $item) {
            if ($item->brand) {
                return $item->brand;
            }
        }
        return null;
    }

    // ─── Accessors ───────────────────────────────

    /**
     * Get a short summary of all items in this order.
     */
    public function getItemsSummaryAttribute(): string
    {
        return $this->items->map(function ($item) {
            return $item->product_name . ' × ' . $item->quantity_bags;
        })->implode(', ');
    }

    /**
     * Get the availability badge data.
     */
    public function getAvailabilityBadgeAttribute(): array
    {
        $items = $this->items;

        if ($items->isEmpty()) {
            return ['label' => 'No Items', 'class' => 'unknown'];
        }

        $allAvailable = true;
        $noneAvailable = true;

        foreach ($items as $item) {
            $productOk = $item->is_product_available;
            $couponOk = $item->is_coupon_available === null ? true : $item->is_coupon_available;

            if ($productOk && $couponOk) {
                $noneAvailable = false;
            } else {
                $allAvailable = false;
            }
        }

        if ($allAvailable) {
            return ['label' => 'Available', 'class' => 'available'];
        }
        if ($noneAvailable) {
            return ['label' => 'Not Available', 'class' => 'unavailable'];
        }
        return ['label' => 'Partial', 'class' => 'partial'];
    }

    /**
     * Get priority info.
     */
    public function getPriorityInfoAttribute(): array
    {
        return self::PRIORITIES[$this->priority] ?? self::PRIORITIES['medium'];
    }

    /**
     * Get status info.
     */
    public function getStatusInfoAttribute(): array
    {
        return self::STATUSES[$this->status] ?? self::STATUSES['pending'];
    }

    /**
     * Check if order quantity and production quantity match (all items available in finished goods stock).
     */
    public function isProductionReady(): bool
    {
        if ($this->items->isEmpty()) {
            return false;
        }

        foreach ($this->items as $item) {
            $stockInfo = $item->stock_info;
            if (!$stockInfo['is_available']) {
                return false;
            }
        }

        return true;
    }
}
