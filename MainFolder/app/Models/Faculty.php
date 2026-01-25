<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany; 

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

    public function sections(): BelongsToMany
    {
        return $this->belongsToMany(
            ClassSection::class, 
            'class_offerings',   
            'faculty_id',        
            'class_section_id'   
        )->distinct();          
    }
}