<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    use HasFactory;

    protected $table = 'subjects';

    protected $fillable = [
        'course_id',
        'department_id', // <--- ADD THIS LINE
        'name',
        'subject_code',
        'year_level',
        'credits',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    // You should likely add this relationship as well
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function classOfferings(): HasMany
    {
        return $this->hasMany(ClassOffering::class);
    }

    public function faculties()
{
    // This allows us to access $subject->faculties
    return $this->belongsToMany(Faculty::class, 'faculty_subject');
}
}