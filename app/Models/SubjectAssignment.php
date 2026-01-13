<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubjectAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id',
        'faculty_id',
        'section_id',
        'academic_year',
        'semester',
        'room',
        'schedule'
    ];

    /**
     * Get the subject for the assignment.
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the faculty for the assignment.
     */
    public function faculty()
    {
        return $this->belongsTo(User::class, 'faculty_id');
    }

    /**
     * Get the section for the assignment.
     */
    public function section()
    {
        return $this->belongsTo(Section::class);
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
     * Scope a query to filter by section.
     */
    public function scopeBySection($query, $sectionId)
    {
        return $query->where('section_id', $sectionId);
    }

    /**
     * Get the assignment details as string.
     */
    public function getDetailsAttribute()
    {
        return sprintf(
            '%s - %s (%s %s)',
            $this->subject->code ?? 'N/A',
            $this->section->name ?? 'N/A',
            $this->academic_year,
            $this->semester
        );
    }
}