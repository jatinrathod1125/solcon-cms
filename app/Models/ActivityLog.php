<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'user_id',
    'action',
    'description',
    'module',
    'ip_address',
    'user_agent',
])]
class ActivityLog extends Model
{
    /**
     * Get the user associated with this activity log.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
