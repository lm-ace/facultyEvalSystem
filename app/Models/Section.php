<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Section extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'department_code',
        'year_level',
        'schedule_type',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the department that owns the section.
     */
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_code', 'code');
    }

    /**
     * Get the students in the section.
     */
    public function students()
    {
        return $this->hasMany(User::class, 'section', 'name')->where('role', 'student');
    }

    /**
     * Get the assignments for the section.
     */
    public function assignments()
    {
        return $this->hasMany(SubjectAssignment::class);
    }

    /**
     * Get the subjects assigned to this section.
     */
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'subject_assignments', 'section_id', 'subject_id')
            ->withTimestamps();
    }

    /**
     * Get the faculty teaching in this section.
     */
    public function faculty()
    {
        return $this->belongsToMany(User::class, 'subject_assignments', 'section_id', 'faculty_id')
            ->where('role', 'faculty')
            ->withTimestamps();
    }

    /**
     * Scope a query to only include active sections.
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
     * Scope a query to filter by year level.
     */
    public function scopeByYearLevel($query, $yearLevel)
    {
        return $query->where('year_level', $yearLevel);
    }

    /**
     * Get the section's student count.
     */
    public function getStudentCountAttribute()
    {
        return $this->students()->count();
    }
}