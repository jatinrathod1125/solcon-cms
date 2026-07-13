<?php

namespace App\Services;

use App\Models\GroutFormula;
use App\Models\Color;
use Illuminate\Validation\ValidationException;

class GroutFormulaService
{
    /**
     * Load the active formula version for a given color.
     *
     * @param int $colorId
     * @return GroutFormula|null
     */
    public function loadActiveFormula(int $colorId): ?GroutFormula
    {
        return GroutFormula::where('color_id', $colorId)
            ->where('is_active', true)
            ->with(['items.rawMaterial', 'items.unit'])
            ->first();
    }

    /**
     * Determine the next version number for a color's formulas.
     *
     * @param int $colorId
     * @return int
     */
    public function incrementVersion(int $colorId): int
    {
        $maxVersion = GroutFormula::where('color_id', $colorId)->max('version');
        return $maxVersion ? ($maxVersion + 1) : 1;
    }

    /**
     * Validate the ingredients of a formula.
     *
     * @param array $items Array of items, each with raw_material_id, quantity, unit_id, mix_stage
     * @return array Modified/validated array of items
     * @throws ValidationException
     */
    public function validateFormula(array $items): array
    {
        if (empty($items)) {
            throw ValidationException::withMessages([
                'items' => 'At least one ingredient/raw material is required in the formula.'
            ]);
        }

        $rawMaterialIds = [];
        $hasStage1 = false;
        $hasStage2 = false;

        foreach ($items as $index => $item) {
            $rawMatId = $item['raw_material_id'] ?? null;
            $quantity = $item['quantity'] ?? null;
            $stage = $item['mix_stage'] ?? null;

            if (!$rawMatId) {
                throw ValidationException::withMessages([
                    "items.{$index}.raw_material_id" => 'Raw material selection is required.'
                ]);
            }

            // 1. Raw material cannot repeat within same formula
            if (in_array($rawMatId, $rawMaterialIds)) {
                throw ValidationException::withMessages([
                    'items' => 'Duplicate raw materials are not allowed in the same formula.'
                ]);
            }
            $rawMaterialIds[] = $rawMatId;

            // Quantity validation
            if (is_null($quantity) || $quantity <= 0) {
                throw ValidationException::withMessages([
                    "items.{$index}.quantity" => 'Quantity must be greater than 0.'
                ]);
            }

            // 2. Mix stage validation
            if ($stage === 'Stage 1') {
                $hasStage1 = true;
            } elseif ($stage === 'Stage 2') {
                $hasStage2 = true;
            } else {
                throw ValidationException::withMessages([
                    "items.{$index}.mix_stage" => 'Each item must have a valid mix stage (Stage 1 or Stage 2).'
                ]);
            }
        }

        // 3. At least one Stage 1 material required
        if (!$hasStage1) {
            throw ValidationException::withMessages([
                'items' => 'At least one Stage 1 material is required in the formula.'
            ]);
        }

        // 4. At least one Stage 2 material required
        if (!$hasStage2) {
            throw ValidationException::withMessages([
                'items' => 'At least one Stage 2 material is required in the formula.'
            ]);
        }

        return $items;
    }
}
