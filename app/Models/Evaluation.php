<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Evaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'faculty_id',
        'subject_id',
        'academic_year',
        'semester',
        'ratings',
        'average_rating',
        'comments',
        'is_anonymous',
        'completed_at'
    ];

    protected $casts = [
        'ratings' => 'array',
        'completed_at' => 'datetime',
        'average_rating' => 'decimal:2',
        'is_anonymous' => 'boolean',
    ];

    /**
     * Get the student who submitted the evaluation.
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Get the faculty being evaluated.
     */
    public function faculty()
    {
        return $this->belongsTo(User::class, 'faculty_id');
    }

    /**
     * Get the subject being evaluated.
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Scope a query to only include completed evaluations.
     */
    public function scopeCompleted($query)
    {
        return $query->whereNotNull('completed_at');
    }

    /**
     * Scope a query to only include pending evaluations.
     */
    public function scopePending($query)
    {
        return $query->whereNull('completed_at');
    }

    /**
     * Scope a query to filter by academic year.
     */
    public function scopeByAcademicYear($query, $academicYear)
    {
        return $query->where('academic_year', $academicYear);
    }

    /**
     * Scope a query to filter by semester.
     */
    public function scopeBySemester($query, $semester)
    {
        return $query->where('semester', $semester);
    }

    /**
     * Scope a query to filter by faculty.
     */
    public function scopeByFaculty($query, $facultyId)
    {
        return $query->where('faculty_id', $facultyId);
    }

    /**
     * Scope a query to filter by student.
     */
    public function scopeByStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    /**
     * Calculate and update the average rating.
     */
    public function calculateAverageRating()
    {
        if (empty($this->ratings)) {
            $this->average_rating = 0;
            return;
        }

        $ratings = array_values($this->ratings);
        $this->average_rating = round(array_sum($ratings) / count($ratings), 2);
    }

    /**
     * Get the rating for a specific criterion.
     */
    public function getRatingForCriterion($criterionId)
    {
        return $this->ratings[$criterionId] ?? null;
    }

    /**
     * Get category averages for this evaluation.
     */
    public function getCategoryAveragesAttribute()
    {
        $categoryAverages = [];
        $criteria = EvaluationCriterion::active()->get()->keyBy('id');

        foreach ($this->ratings as $criterionId => $rating) {
            $criterion = $criteria->get($criterionId);
            if ($criterion) {
                $category = $criterion->category;
                if (!isset($categoryAverages[$category])) {
                    $categoryAverages[$category] = [
                        'total' => 0,
                        'count' => 0
                    ];
                }
                $categoryAverages[$category]['total'] += $rating;
                $categoryAverages[$category]['count']++;
            }
        }

        // Calculate averages
        foreach ($categoryAverages as $category => $data) {
            $categoryAverages[$category] = round($data['total'] / $data['count'], 2);
        }

        return $categoryAverages;
    }

    /**
     * Mark evaluation as completed.
     */
    public function markAsCompleted()
    {
        $this->calculateAverageRating();
        $this->completed_at = now();
        $this->save();
    }
}