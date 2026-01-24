<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CriteriaItem extends Model
{
    use HasFactory;

    protected $table = 'criteria_items';

    public $timestamps = false;

    protected $fillable = ['section_id', 'item_number', 'question_text', 'max_score', 'position'];

    public function section(): BelongsTo
    {
        return $this->belongsTo(CriteriaSection::class);
    }
}
