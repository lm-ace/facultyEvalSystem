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
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'year_level',
        'section_id',
        'contact_no',
        'block_section'
    ];

    protected $with = ['user'];

    protected $appends = ['email'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getEmailAttribute()
    {
        return $this->user ? $this->user->email : null;
    }

    public function section()
    {
        return $this->belongsTo(ClassSection::class, 'section_id');
    }
}