<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SystemRating extends Model
{
    use HasFactory;

    protected $table = 'system_ratings';

    protected $fillable = ['student_id', 'review_period_id', 'rating', 'feedback_text'];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function reviewPeriod(): BelongsTo
    {
        return $this->belongsTo(ReviewPeriod::class);
    }
}
