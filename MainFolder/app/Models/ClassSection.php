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

    protected $table = 'class_sections';
    protected $guarded = [];

    protected $fillable = ['course_id', 'year_level', 'block'];

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

    /**
     * The fixed subjects relationship.
     * We explicitly list the keys to ensure Laravel finds the correct columns.
     */
    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(
            Subject::class,     // The related model
            'class_offerings',  // The pivot table name
            'class_section_id', // The foreign key for THIS model (ClassSection)
            'subject_id'        // The foreign key for the RELATED model (Subject)
        );
    }
}