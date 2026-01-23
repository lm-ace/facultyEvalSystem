<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    use HasFactory;

    protected $table = 'students';

    protected $fillable = [
        'user_id',
        'student_number',
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'year_level',
        'section_id', // ✅ ADDED: This is required for the new database link
        'block_section', // You can keep this for display, or remove it later if unused
        'contact_no',
    ];

    // ✅ NEW: This fixes the "Call to undefined method" error
    // It tells Laravel that a Student belongs to a specific ClassSection
    public function section(): BelongsTo
    {
        return $this->belongsTo(ClassSection::class, 'section_id');
    }

    // --- Existing Relationships ---

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function enrollments(): HasMany
    {
       return $this->hasMany(Student::class, 'section_id');
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class);
    }

    public function systemRatings(): HasMany
    {
        return $this->hasMany(SystemRating::class);
    }
}