<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DispatchStatusHistory extends Model
{
    protected $table = 'dispatch_status_history';

    protected $fillable = [
        'dispatch_id',
        'status',
        'changed_by',
        'remarks',
    ];

    public function dispatch(): BelongsTo
    {
        return $this->belongsTo(Dispatch::class);
    }

    public function changer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
