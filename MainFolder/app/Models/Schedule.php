<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Schedule extends Model
{
    use HasFactory;

    protected $table = 'schedules';

    protected $fillable = ['class_offering_id', 'day_of_week', 'start_time', 'end_time', 'room'];

    public function classOffering(): BelongsTo
    {
        return $this->belongsTo(ClassOffering::class);
    }
}
