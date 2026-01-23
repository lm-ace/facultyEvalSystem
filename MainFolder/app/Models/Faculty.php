<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany; // <--- 1. ADD THIS IMPORT

class Faculty extends Model
{
    protected $fillable = [
        'user_id',
        'faculty_code',
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'email',
        'profile_picture',
        'contact_no',
        'department_id'
    ];

    // Auto-load department
    protected $with = ['department'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function subjects(): BelongsToMany
    {
        // This tells Laravel: "I am related to Subjects via the 'faculty_subject' table"
        return $this->belongsToMany(Subject::class, 'faculty_subject');
    }
    
    public function classOfferings(): HasMany
    {
        return $this->hasMany(ClassOffering::class);
    }
    
    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class);
    }

    // --- 2. ADD THIS MISSING RELATIONSHIP ---
    public function sections(): BelongsToMany
    {
        return $this->belongsToMany(
            ClassSection::class, // The related model
            'class_offerings',   // The pivot table acting as the bridge
            'faculty_id',        // Foreign key for Faculty on the pivot table
            'class_section_id'   // Foreign key for ClassSection on the pivot table
        )->distinct();           // distinct() prevents duplicates if they teach multiple subjects to the same section
    }
}