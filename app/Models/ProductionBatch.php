<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'batch_no',
    'machine_id',
    'grade_id',
    'formula_id',
    'formula_snapshot',
    'supervisor_id',
    'start_time',
    'end_time',
    'output_bags',
    'output_kg',
    'status',
    'remarks',
])]
class ProductionBatch extends Model
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'start_time' => 'datetime',
            'end_time' => 'datetime',
            'output_bags' => 'decimal:4',
            'output_kg' => 'decimal:4',
            'formula_snapshot' => 'array',
        ];
    }

    /**
     * Get the machine where this batch is processed.
     */
    public function machine(): BelongsTo
    {
        return $this->belongsTo(Machine::class);
    }

    /**
     * Get the grade of adhesive produced in this batch.
     */
    public function grade(): BelongsTo
    {
        return $this->belongsTo(Grade::class);
    }

    /**
     * Get the formula version used for raw material deductions.
     */
    public function formula(): BelongsTo
    {
        return $this->belongsTo(Formula::class);
    }

    /**
     * Get the supervisor who created/run the batch.
     */
    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    /**
     * Get the stock ledger records created by this batch.
     */
    public function ledgers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(StockLedger::class, 'batch_id');
    }
}
