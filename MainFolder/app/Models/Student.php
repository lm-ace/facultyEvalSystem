<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'student_number',
        // 'email', <--- Make sure this is REMOVED from fillable
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'year_level',
        'section_id',
        'contact_no',
        'block_section'
    ];

    // =========================================================
    // 1. AUTO-LOAD THE USER (The Data Source)
    // =========================================================
    // This tells Laravel: "Always grab the User info when loading a Student"
    protected $with = ['user'];

    // =========================================================
    // 2. APPEND THE FIELD (The Label)
    // =========================================================
    // This tells Laravel: "Add a fake field called 'email' to the JSON output"
    protected $appends = ['email'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // =========================================================
    // 3. DEFINE THE ACCESSOR (The Logic)
    // =========================================================
    // This defines what goes inside that fake 'email' field
    public function getEmailAttribute()
    {
        // If the student has a User account, grab that email. Otherwise, return null.
        return $this->user ? $this->user->email : null;
    }

    public function section()
    {
        return $this->belongsTo(ClassSection::class, 'section_id');
    }
}