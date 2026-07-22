<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dispatch extends Model
{
    protected $fillable = [
        'dispatch_number',
        'dispatch_type',
        'party_name',
        'city',
        'place',
        'full_address',
        'google_map_url',
        'vehicle_number',
        'driver_mobile',
        'expected_arrival_at',
        'payment_required',
        'is_released',
        'released_by',
        'released_at',
        'status',
        'remarks',
        'created_by',
        'loaded_by',
        'loaded_at',
    ];

    protected $casts = [
        'expected_arrival_at' => 'datetime',
        'payment_required' => 'boolean',
        'is_released' => 'boolean',
        'released_at' => 'datetime',
        'loaded_at' => 'datetime',
    ];

    public const STATUSES = [
        'planned' => ['label' => 'Planned', 'color' => '#3b82f6', 'bg' => '#dbeafe', 'icon' => 'calendar'],
        'waiting_for_truck' => ['label' => 'Waiting Truck', 'color' => '#f59e0b', 'bg' => '#fef3c7', 'icon' => 'clock'],
        'truck_arrived' => ['label' => 'Truck Arrived', 'color' => '#8b5cf6', 'bg' => '#ede9fe', 'icon' => 'truck'],
        'loading' => ['label' => 'Loading', 'color' => '#0284c7', 'bg' => '#e0f2fe', 'icon' => 'package-check'],
        'completed' => ['label' => 'Completed', 'color' => '#10b981', 'bg' => '#d1fae5', 'icon' => 'check-circle'],
        'cancelled' => ['label' => 'Cancelled', 'color' => '#ef4444', 'bg' => '#fee2e2', 'icon' => 'x-circle'],
    ];

    public const TYPES = [
        'factory_pickup' => ['label' => 'Factory Pickup', 'badge_bg' => '#f1f5f9', 'badge_color' => '#334155'],
        'crossing_delivery' => ['label' => 'Crossing Delivery', 'badge_bg' => '#eff6ff', 'badge_color' => '#1d4ed8'],
    ];

    // ─── Relationships ───────────────────────────

    public function items(): HasMany
    {
        return $this->hasMany(DispatchItem::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function releaser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'released_by');
    }

    public function loader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'loaded_by');
    }

    public function loadingLogs(): HasMany
    {
        return $this->hasMany(DispatchLoadingLog::class)->orderByDesc('created_at');
    }

    public function statusHistory(): HasMany
    {
        return $this->hasMany(DispatchStatusHistory::class)->orderByDesc('created_at');
    }

    // ─── Accessors ───────────────────────────────

    public function getStatusInfoAttribute(): array
    {
        return self::STATUSES[$this->status] ?? self::STATUSES['planned'];
    }

    public function getTypeInfoAttribute(): array
    {
        return self::TYPES[$this->dispatch_type] ?? self::TYPES['factory_pickup'];
    }

    public function getTotalBagsAttribute(): int
    {
        return (int) $this->items->sum('quantity_bags');
    }

    public function getTotalWeightAttribute(): float
    {
        return (float) $this->items->sum(function ($item) {
            return $item->calculated_weight_kg;
        });
    }

    public function getTotalTonsAttribute(): float
    {
        return round($this->total_weight / 1000, 2);
    }

    /**
     * Get structured product summary breakdown for cards and cards display.
     */
    public function getProductSummaryAttribute(): array
    {
        $summary = [];
        foreach ($this->items as $item) {
            $deptLabel = $item->department_label;
            if (!isset($summary[$deptLabel])) {
                $summary[$deptLabel] = [
                    'department' => $deptLabel,
                    'total_bags' => 0,
                    'total_weight' => 0,
                    'products' => [],
                ];
            }
            $summary[$deptLabel]['total_bags'] += $item->quantity_bags;
            $summary[$deptLabel]['total_weight'] += $item->calculated_weight_kg;
            $summary[$deptLabel]['products'][] = [
                'name' => $item->product_name,
                'bags' => $item->quantity_bags,
                'packing' => $item->packing,
                'coupon' => $item->coupon_name,
                'weight' => $item->calculated_weight_kg,
            ];
        }
        return $summary;
    }

    /**
     * Convert Google Map Link (including maps.app.goo.gl short links) to clean embeddable iframe URL.
     */
    public function getEmbedGoogleMapUrlAttribute(): ?string
    {
        return static::resolveGoogleMapEmbedUrl($this->google_map_url);
    }

    /**
     * Resolve any Google Map URL (short link, place link, coordinates) to clean iframe embed URL.
     */
    public static function resolveGoogleMapEmbedUrl(?string $url): ?string
    {
        if (empty($url)) {
            return null;
        }

        $url = trim($url);

        // If it's already a clean embed URL
        if (str_contains($url, '/maps/embed') || (str_contains($url, 'output=embed') && !str_contains($url, 'goo.gl'))) {
            return $url;
        }

        // 1. Expand short URLs (maps.app.goo.gl or goo.gl/maps) via cURL if needed
        if (str_contains($url, 'goo.gl') || str_contains($url, 'maps.app.goo.gl')) {
            try {
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)');
                curl_setopt($ch, CURLOPT_TIMEOUT, 3);
                curl_exec($ch);
                $expanded = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
                curl_close($ch);

                if (!empty($expanded) && $expanded !== $url) {
                    $url = $expanded;
                }
            } catch (\Exception $e) {
                // Fallback to original url
            }
        }

        // 2. Extract 3d/4d coordinates (!3d22.8234789!4d70.8100803)
        if (preg_match('/!3d(-?\d+\.\d+)!4d(-?\d+\.\d+)/', $url, $matches)) {
            return "https://maps.google.com/maps?q={$matches[1]},{$matches[2]}&z=15&output=embed";
        }

        // 3. Extract @lat,lng coordinates (/@22.8205618,70.8238872,15z/)
        if (preg_match('/@(-?\d+\.\d+),(-?\d+\.\d+)/', $url, $matches)) {
            return "https://maps.google.com/maps?q={$matches[1]},{$matches[2]}&z=15&output=embed";
        }

        // 4. Extract place name (/place/The+36+BOX+CRICKET/)
        if (preg_match('/\/place\/([^\/\?]+)/', $url, $matches)) {
            $placeName = urldecode(str_replace('+', ' ', $matches[1]));
            $query = urlencode($placeName);
            return "https://maps.google.com/maps?q={$query}&z=15&output=embed";
        }

        $query = urlencode($url);
        return "https://maps.google.com/maps?q={$query}&z=15&output=embed";
    }
}
