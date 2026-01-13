<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the users for the department.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'department_code', 'code');
    }

    /**
     * Get the faculty members for the department.
     */
    public function faculty()
    {
        return $this->users()->where('role', 'faculty');
    }

    /**
     * Get the students for the department.
     */
    public function students()
    {
        return $this->users()->where('role', 'student');
    }

    /**
     * Get the subjects for the department.
     */
    public function subjects()
    {
        return $this->hasMany(Subject::class, 'department_code', 'code');
    }

    /**
     * Get the sections for the department.
     */
    public function sections()
    {
        return $this->hasMany(Section::class, 'department_code', 'code');
    }

    /**
     * Scope a query to only include active departments.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get department statistics.
     */
    public function getStatisticsAttribute()
    {
        return [
            'faculty_count' => $this->faculty()->count(),
            'student_count' => $this->students()->count(),
            'subject_count' => $this->subjects()->count(),
            'section_count' => $this->sections()->count(),
        ];
    }

    /**
     * Get the average rating for the department.
     */
    public function getAverageRatingAttribute()
    {
        $facultyIds = $this->faculty()->pluck('id');
        
        if ($facultyIds->isEmpty()) {
            return null;
        }

        return Evaluation::whereIn('faculty_id', $facultyIds)
            ->whereNotNull('completed_at')
            ->avg('average_rating');
    }
}