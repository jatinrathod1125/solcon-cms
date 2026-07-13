<?php

namespace App\Services;

use App\Models\Formula;

class FormulaService
{
    /**
     * Get the active formula version for a grade.
     */
    public static function getActiveFormula(int $gradeId): ?Formula
    {
        return Formula::where('grade_id', $gradeId)
            ->where('is_active', true)
            ->with(['items.rawMaterial', 'items.unit'])
            ->first();
    }
}
