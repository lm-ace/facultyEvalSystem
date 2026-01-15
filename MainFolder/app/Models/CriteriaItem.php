<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CriteriaItem extends Model
{
    protected $table = 'criteria_items';
    protected $fillable = ['section_id', 'question_text', 'max_score', 'position'];
}