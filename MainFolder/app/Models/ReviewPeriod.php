<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReviewPeriod extends Model
{
    use HasFactory;

    protected $table = 'review_periods';

    protected $fillable = [
        'name',
        'academic_year',
        'semester',
        'start_date',
        'end_date',
        'is_open',
    ];

    public function classOfferings(): HasMany
    {
        return $this->hasMany(ClassOffering::class, 'semester_id');
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
