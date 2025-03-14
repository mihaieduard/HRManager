<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// app/Models/Attendance.php
class Attendance extends Model
{
    protected $fillable = ['user_id', 'date', 'clock_in', 'clock_out', 'notes'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
