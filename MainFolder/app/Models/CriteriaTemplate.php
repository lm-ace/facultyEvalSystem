<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CriteriaTemplate extends Model
{
    use HasFactory;

    protected $table = 'criteria_templates';

    protected $fillable = ['name', 'description', 'version', 'created_by', 'active'];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function sections(): HasMany
    {
        return $this->hasMany(CriteriaSection::class, 'template_id');
    }
}
