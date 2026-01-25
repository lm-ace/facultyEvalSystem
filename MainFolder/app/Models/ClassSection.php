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

    protected $with = ['course'];

    protected $appends = ['full_name'];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function classOfferings(): HasMany
    {
        return $this->hasMany(ClassOffering::class);
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(
            Subject::class, 
            'class_offerings', 
            'class_section_id', 
            'subject_id'
        )->withPivot('faculty_id'); 
    }

    public function getFullNameAttribute()
    {
        $courseCode = $this->course ? $this->course->code : 'N/A';
        return "{$courseCode} {$this->year_level} - {$this->block}";
    }

    public function students(): HasMany
{
    return $this->hasMany(Student::class, 'section_id');
}
}