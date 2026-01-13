<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EvaluationCriterion extends Model
{
    use HasFactory;

    protected $table = 'evaluation_criteria';

    protected $fillable = [
        'question',
        'category',
        'order',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get all categories.
     */
    public static function getCategories()
    {
        return [
            'Instructional Competence',
            'Classroom Management',
            'Assessment and Feedback',
            'Professionalism'
        ];
    }

    /**
     * Scope a query to only include active criteria.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to filter by category.
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope a query to order by order field.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    /**
     * Get the average rating for this criterion.
     */
    public function getAverageRatingAttribute()
    {
        $evaluations = Evaluation::whereNotNull('ratings')->get();
        $total = 0;
        $count = 0;

        foreach ($evaluations as $evaluation) {
            $ratings = $evaluation->ratings;
            if (isset($ratings[$this->id])) {
                $total += $ratings[$this->id];
                $count++;
            }
        }

        return $count > 0 ? round($total / $count, 2) : null;
    }
}