<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'description',
        'ip_address',
        'user_agent'
    ];

    protected $appends = ['action_name'];

    /**
     * Get the user who performed the action.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to filter by user.
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope a query to filter by action.
     */
    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope a query to filter by date range.
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Log an action.
     */
    public static function log(string $action, ?string $description = null, ?int $userId = null): self
    {
        // Check if we're in a console/artisan context
        $request = app('request');
        $ipAddress = $request ? $request->ip() : '127.0.0.1';
        $userAgent = $request ? $request->userAgent() : 'Console/Artisan';

        return self::create([
            'user_id' => $userId ?? (Auth::check() ? Auth::id() : null),
            'action' => $action,
            'description' => $description,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
        ]);
    }

    /**
     * Get readable action name.
     */
    public function getActionNameAttribute(): string
    {
        $actions = [
            'LOGIN' => 'User Login',
            'LOGOUT' => 'User Logout',
            'USER_CREATE' => 'Create User',
            'USER_UPDATE' => 'Update User',
            'USER_DELETE' => 'Delete User',
            'CRITERIA_CREATE' => 'Create Evaluation Criteria',
            'CRITERIA_UPDATE' => 'Update Evaluation Criteria',
            'CRITERIA_DELETE' => 'Delete Evaluation Criteria',
            'SUBJECT_CREATE' => 'Create Subject',
            'SECTION_CREATE' => 'Create Section',
            'EVALUATION_COMPLETED' => 'Evaluation Completed',
            'SYSTEM_STATUS_CHANGE' => 'System Status Changed',
            'SYSTEM_SETTINGS_UPDATE' => 'System Settings Updated',
        ];

        return $actions[$this->action] ?? ucfirst(strtolower(str_replace('_', ' ', $this->action)));
    }
}