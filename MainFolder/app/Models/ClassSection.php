<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ClassSection extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Eager load course to generate names efficiently
    protected $with = ['course'];

    // Allows us to use $section->full_name in JSON
    protected $appends = ['full_name'];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    // --- 1. ADD THIS MISSING RELATIONSHIP (Fixes the Error) ---
    public function classOfferings(): HasMany
    {
        return $this->hasMany(ClassOffering::class);
    }

    // --- 2. ADD THIS TOO (Required for your Student List) ---
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    // Direct access to subjects via pivot
    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(
            Subject::class, 
            'class_offerings', 
            'class_section_id', 
            'subject_id'
        )->withPivot('faculty_id'); // Optional: lets you access faculty_id directly if needed
    }

    // Helper Attribute for "BSIT 1-A"
    public function getFullNameAttribute()
    {
        // Example: BSIT 1 - A
        $courseCode = $this->course ? $this->course->code : 'N/A';
        return "{$courseCode} {$this->year_level} - {$this->block}";
    }

    public function students(): HasMany
{
    // This connects ClassSection directly to Students via the section_id column
    return $this->hasMany(Student::class, 'section_id');
}
}