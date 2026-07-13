<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'epoxy_product_id',
    'color_id',
    'epoxy_filler_color_id',
    'formula_snapshot',
    'quantity',
    'operator_id',
    'remarks',
])]
class EpoxyAssembly extends Model
{
    protected function casts(): array
    {
        return [
            'formula_snapshot' => 'array',
            'quantity' => 'integer',
        ];
    }

    /**
     * Get the product.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(EpoxyProduct::class, 'epoxy_product_id');
    }

    /**
     * Get the color (from Grout Colors).
     */
    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class, 'color_id');
    }

    /**
     * Get the epoxy filler color.
     */
    public function epoxyFillerColor(): BelongsTo
    {
        return $this->belongsTo(EpoxyFillerColor::class, 'epoxy_filler_color_id');
    }

    /**
     * Get the operator who assembled this.
     */
    public function operator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'operator_id');
    }

    /**
     * Get the ledgers for this assembly.
     */
    public function ledgers(): HasMany
    {
        return $this->hasMany(StockLedger::class, 'epoxy_assembly_id');
    }
}
