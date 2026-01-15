<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CriteriaSection extends Model
{
    protected $table = 'criteria_sections';
    public function items()
    {
        return $this->hasMany(CriteriaItem::class, 'section_id')->orderBy('position');
    }
}