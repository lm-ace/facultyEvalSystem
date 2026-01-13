<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type',
        'is_read'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    /**
     * Get the user that owns the notification.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include unread notifications.
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope a query to only include read notifications.
     */
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    /**
     * Scope a query to filter by user.
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope a query to filter by type.
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Create a new notification.
     */
    public static function createNotification($userId, $title, $message, $type = 'info')
    {
        return self::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'is_read' => false,
        ]);
    }

    /**
     * Mark notification as read.
     */
    public function markAsRead()
    {
        $this->is_read = true;
        $this->read_at = now();
        $this->save();
    }

    /**
     * Mark notification as unread.
     */
    public function markAsUnread()
    {
        $this->is_read = false;
        $this->read_at = null;
        $this->save();
    }

    /**
     * Get the notification's icon based on type.
     */
    public function getIconAttribute()
    {
        $icons = [
            'info' => 'fa-info-circle',
            'success' => 'fa-check-circle',
            'warning' => 'fa-exclamation-triangle',
            'danger' => 'fa-times-circle',
        ];

        return $icons[$this->type] ?? 'fa-bell';
    }

    /**
     * Get the notification's color based on type.
     */
    public function getColorAttribute()
    {
        $colors = [
            'info' => 'blue',
            'success' => 'green',
            'warning' => 'yellow',
            'danger' => 'red',
        ];

        return $colors[$this->type] ?? 'gray';
    }
}