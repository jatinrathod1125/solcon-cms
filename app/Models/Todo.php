<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'department_id',
        'created_by',
        'assigned_to',
        'priority',
        'status',
        'due_date',
        'completed_at',
        'sort_order',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'due_date' => 'date',
    ];

    /**
     * Get the user who created the todo.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user assigned to the todo.
     */
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get the department associated with the todo.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
