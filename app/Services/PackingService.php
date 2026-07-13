<?php

namespace App\Services;

class PackingService
{
    /**
     * Calculate the total production weight in KG based on finished bag counts.
     * Each bag is strictly 25 KG.
     *
     * @param int $finishedBags
     * @return float
     */
    public function calculateTotalWeight(int $finishedBags): float
    {
        return $finishedBags * 25.0;
    }

    /**
     * Calculate virtual pouch count based on total weight and packing size.
     * Fills 500 GM (0.5 KG) or 1 KG pouches.
     *
     * @param float $totalWeightKg
     * @param string $packingSize '500 GM' or '1 KG'
     * @return int
     */
    public function calculatePouchCount(float $totalWeightKg, string $packingSize): int
    {
        $sizeKg = str_contains($packingSize, '500') ? 0.5 : 1.0;
        return (int) round($totalWeightKg / $sizeKg);
    }
}
