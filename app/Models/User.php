<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'position',
        'salary',
        'contract_start',
        'contract_end',
        'previous_experience',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'contract_start' => 'date',
        'contract_end' => 'date',
    ];

    // Metodele pentru verificarea rolurilor
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // Metodele pentru verificarea rolurilor
    public function isAdmin()
    {
        return $this->role && $this->role->name === 'Admin';
    }
    public function isHR()
    {
        return $this->role && $this->role->name === 'HR';
    }

    // Relațiile cu alte modele
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function surveys()
    {
        return $this->hasMany(Survey::class);
    }

    public function id(): int
    {
        return $this->id;
    }
}