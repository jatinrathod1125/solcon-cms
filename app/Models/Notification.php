<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'body',
        'type',
        'department_id',
        'user_id',
        'status',
        'sent_at',
        'read_at',
        'payload',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'read_at' => 'datetime',
        'payload' => 'array',
    ];

    /**
     * Get the user that owns the notification.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the department associated with the notification.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
