<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'students'; // Dapat match sa Workbench mo
    protected $primaryKey = 'student_number'; // Dahil ito ang PK mo
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'student_number', 
        'full_name', 
        'email', 
        'password', 
        'section_id'
    ];
}