<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'raw_material_id',
    'batch_id',
    'grout_batch_id',
    'epoxy_assembly_id',
    'transaction_type',
    'quantity',
    'balance_after',
    'remarks',
    'created_by',
])]
class StockLedger extends Model
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:4',
            'balance_after' => 'decimal:4',
        ];
    }

    /**
     * Get the raw material associated with this ledger entry.
     */
    public function rawMaterial(): BelongsTo
    {
        return $this->belongsTo(RawMaterial::class);
    }

    /**
     * Get the production batch associated with this ledger entry.
     */
    public function batch(): BelongsTo
    {
        return $this->belongsTo(ProductionBatch::class, 'batch_id');
    }

    /**
     * Get the Grout production batch associated with this ledger entry.
     */
    public function groutBatch(): BelongsTo
    {
        return $this->belongsTo(GroutProductionBatch::class, 'grout_batch_id');
    }

    /**
     * Get the Epoxy assembly associated with this ledger entry.
     */
    public function epoxyAssembly(): BelongsTo
    {
        return $this->belongsTo(EpoxyAssembly::class, 'epoxy_assembly_id');
    }

    /**
     * Get the previous stock level before this transaction.
     */
    public function getPreviousStockAttribute(): float
    {
        return (float) $this->balance_after - (float) $this->quantity;
    }

    /**
     * Get the user who recorded this ledger entry.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
