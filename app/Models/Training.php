<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TrainingEnrollment;

class Training extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'type',
        'duration',
        'modules',
        'status',
        'created_by',
        'syllabus',
        'target_groups',
    ];

    protected $casts = [
        'modules' => 'integer',
        'duration' => 'integer',
        'target_groups' => 'array',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function enrollments()
    {
        return $this->hasMany(TrainingEnrollment::class);
    }
}