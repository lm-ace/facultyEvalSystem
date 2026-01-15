<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    use HasFactory;

    protected $table = 'audit_logs';

    protected $fillable = ['admin_user_id', 'action', 'table_name', 'record_id', 'old_values', 'new_values', 'reason'];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_user_id');
    }
}
