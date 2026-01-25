<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'role',
        'username',
        'email',
        'password_hash', 
        'is_active',
        'last_login',
    ];

    protected $hidden = [
        'password_hash', 
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function getAuthPassword()
    {
        return $this->password_hash;
    }


    public function student(): HasOne
    {
        return $this->hasOne(Student::class);
    }

    public function faculty(): HasOne
    {
        return $this->hasOne(Faculty::class);
    }
    

    public function getNameAttribute()
    {
        if ($this->student) {
            return $this->student->first_name . ' ' . $this->student->last_name;
        }
        if ($this->faculty) {
            return $this->faculty->first_name . ' ' . $this->faculty->last_name;
        }
        return $this->username;
    }
}