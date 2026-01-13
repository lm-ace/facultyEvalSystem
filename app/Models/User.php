<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'password',
        'role',
        'department_code',
        'section',
        'profile_image',
        'is_active',
        'last_login_at'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Get the department associated with the user.
     */
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_code', 'code');
    }

    /**
     * Get the evaluations given by the user (as student).
     */
    public function evaluationsGiven()
    {
        return $this->hasMany(Evaluation::class, 'student_id');
    }

    /**
     * Get the evaluations received by the user (as faculty).
     */
    public function evaluationsReceived()
    {
        return $this->hasMany(Evaluation::class, 'faculty_id');
    }

    /**
     * Get the subjects assigned to the faculty member.
     */
    public function assignedSubjects()
    {
        return $this->hasMany(SubjectAssignment::class, 'faculty_id');
    }

    /**
     * Get the audit logs for the user.
     */
    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }

    /**
     * Get the notifications for the user.
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Scope a query to only include faculty users.
     */
    public function scopeFaculty($query)
    {
        return $query->where('role', 'faculty');
    }

    /**
     * Scope a query to only include student users.
     */
    public function scopeStudent($query)
    {
        return $query->where('role', 'student');
    }

    /**
     * Scope a query to only include admin users.
     */
    public function scopeAdmin($query)
    {
        return $query->where('role', 'admin');
    }

    /**
     * Scope a query to only include active users.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to filter by department.
     */
    public function scopeByDepartment($query, $departmentCode)
    {
        return $query->where('department_code', $departmentCode);
    }

    /**
     * Scope a query to filter by section.
     */
    public function scopeBySection($query, $section)
    {
        return $query->where('section', $section);
    }

    /**
     * Get the user's average rating (for faculty).
     */
    public function getAverageRatingAttribute()
    {
        if ($this->role !== 'faculty') {
            return null;
        }

        return $this->evaluationsReceived()
            ->whereNotNull('completed_at')
            ->avg('average_rating');
    }

    /**
     * Get the user's total evaluations count (for faculty).
     */
    public function getTotalEvaluationsAttribute()
    {
        if ($this->role !== 'faculty') {
            return null;
        }

        return $this->evaluationsReceived()
            ->whereNotNull('completed_at')
            ->count();
    }

    /**
     * Check if user is an administrator.
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is a faculty member.
     */
    public function isFaculty()
    {
        return $this->role === 'faculty';
    }

    /**
     * Check if user is a student.
     */
    public function isStudent()
    {
        return $this->role === 'student';
    }

    /**
     * Update last login timestamp.
     */
    public function updateLastLogin()
    {
        $this->last_login_at = now();
        $this->save();
    }
}