<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CriteriaItem extends Model
{
    public $timestamps = false; 

    protected $fillable = ['section_id', 'item_number', 'question_text', 'max_score', 'position'];

    public function section()
    {
        return $this->belongsTo(CriteriaSection::class, 'section_id');
    }
}