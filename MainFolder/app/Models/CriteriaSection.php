<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CriteriaSection extends Model
{
    public $timestamps = false;

    protected $fillable = ['template_id', 'section_number', 'section_name', 'position'];

    public function items()
    {
        return $this->hasMany(CriteriaItem::class, 'section_id')->orderBy('position');
    }
}