<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Admin extends Model
{
    use HasFactory;

    protected $table = 'admins';

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'role_label',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
