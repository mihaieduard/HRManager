<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingEnrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'training_id',
        'progress',
        'score',
        'completed_at',
        'certificate_issued',
    ];

    protected $casts = [
        'progress' => 'integer',
        'score' => 'integer',
        'completed_at' => 'datetime',
        'certificate_issued' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function training()
    {
        return $this->belongsTo(Training::class);
    }
}