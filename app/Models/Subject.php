<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'department_code',
        'units',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the department that owns the subject.
     */
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_code', 'code');
    }

    /**
     * Get the assignments for the subject.
     */
    public function assignments()
    {
        return $this->hasMany(SubjectAssignment::class);
    }

    /**
     * Get the evaluations for the subject.
     */
    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }

    /**
     * Get the faculty members teaching this subject.
     */
    public function faculty()
    {
        return $this->belongsToMany(User::class, 'subject_assignments', 'subject_id', 'faculty_id')
            ->where('role', 'faculty')
            ->withTimestamps();
    }

    /**
     * Get the sections assigned to this subject.
     */
    public function sections()
    {
        return $this->belongsToMany(Section::class, 'subject_assignments', 'subject_id', 'section_id')
            ->withTimestamps();
    }

    /**
     * Scope a query to only include active subjects.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to filter by department.
     */
    public function scopeByDepartment($query, $departmentCode)
    {
        return $query->where('department_code', $departmentCode);
    }

    /**
     * Get the subject's average rating.
     */
    public function getAverageRatingAttribute()
    {
        return $this->evaluations()
            ->whereNotNull('completed_at')
            ->avg('average_rating');
    }
}