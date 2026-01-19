<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    use HasFactory;

    protected $table = 'departments';

    protected $fillable = ['name', 'code'];

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    public function faculties(): HasMany
    {
        return $this->hasMany(Faculty::class);
    }
}
