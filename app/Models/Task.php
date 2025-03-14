<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// app/Models/Task.php
class Task extends Model
{
    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'assigned_to',
        'created_by',
        'deadline',
        'tags',
    ];

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
    protected $casts = [
        'completed' => 'boolean',
        'due_date' => 'date',
        'completed_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
