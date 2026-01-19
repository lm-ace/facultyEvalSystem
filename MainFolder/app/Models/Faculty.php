<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    public function classOfferings(): HasMany
    {
        return $this->hasMany(ClassOffering::class);
    }
    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class);
    }
}
