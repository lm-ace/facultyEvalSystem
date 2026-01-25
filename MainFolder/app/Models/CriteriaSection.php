<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CriteriaSection extends Model
{
    use HasFactory;

    protected $table = 'criteria_sections';
    
    public $timestamps = false;

    protected $fillable = ['section_number', 'section_name', 'position'];

    public function items(): HasMany
    {
        return $this->hasMany(CriteriaItem::class, 'section_id');
    }
}
