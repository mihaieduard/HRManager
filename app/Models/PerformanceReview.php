<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerformanceReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reviewer_id',
        'review_date',
        'period_start_month',
        'period_start_year',
        'period_end_month',
        'period_end_year',
        'accomplishments',
        'areas_for_improvement',
        'goals',
        'technical_skills_rating',
        'technical_skills_comments',
        'communication_rating',
        'communication_comments',
        'teamwork_rating',
        'teamwork_comments',
        'initiative_rating',
        'initiative_comments',
        'reliability_rating',
        'reliability_comments',
        'overall_rating',
        'overall_comments',
        'employee_comments',
        'employee_acknowledged',
        'employee_acknowledged_date',
        'status',
    ];

    protected $casts = [
        'review_date' => 'date',
        'employee_acknowledged_date' => 'date',
        'employee_acknowledged' => 'boolean',
    ];

    public function employee()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function getAverageRatingAttribute()
    {
        $sum = $this->technical_skills_rating +
               $this->communication_rating +
               $this->teamwork_rating +
               $this->initiative_rating +
               $this->reliability_rating;
        
        $count = 5; // numărul de categorii
        
        return $sum > 0 ? round($sum / $count, 1) : 0;
    }
    
    public function getPeriodAttribute()
    {
        $startMonth = date('F', mktime(0, 0, 0, $this->period_start_month, 1));
        $endMonth = date('F', mktime(0, 0, 0, $this->period_end_month, 1));
        
        return "{$startMonth} {$this->period_start_year} - {$endMonth} {$this->period_end_year}";
    }
}