<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// app/Models/ChatbotQuestion.php
class ChatbotQuestion extends Model
{
    protected $fillable = ['question', 'answer'];
}
