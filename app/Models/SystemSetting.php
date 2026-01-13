<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SystemSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'description'
    ];

    /**
     * Get a setting value by key.
     */
    public static function getValue($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Set a setting value.
     */
    public static function setValue($key, $value, $type = 'string', $description = null)
    {
        return self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'description' => $description
            ]
        );
    }

    /**
     * Get evaluation status.
     */
    public static function getEvaluationStatus()
    {
        return self::getValue('evaluation_status', 'closed');
    }

    /**
     * Set evaluation status.
     */
    public static function setEvaluationStatus($status)
    {
        return self::setValue('evaluation_status', $status, 'string', 'Evaluation system status');
    }

    /**
     * Check if evaluation system is open.
     */
    public static function isEvaluationOpen()
    {
        return self::getEvaluationStatus() === 'open';
    }

    /**
     * Get current academic year.
     */
    public static function getCurrentAcademicYear()
    {
        return self::getValue('current_academic_year', date('Y') . '-' . (date('Y') + 1));
    }

    /**
     * Get current semester.
     */
    public static function getCurrentSemester()
    {
        return self::getValue('current_semester', '1st');
    }

    /**
     * Get all settings as an associative array.
     */
    public static function getAllSettings()
    {
        return self::all()->pluck('value', 'key')->toArray();
    }

    /**
     * Get system information.
     */
    public static function getSystemInfo()
    {
        return [
            'system_name' => self::getValue('system_name', 'EduRate Faculty Evaluation System'),
            'institution_name' => self::getValue('institution_name', 'Polytechnic University of the Philippines'),
            'academic_year' => self::getCurrentAcademicYear(),
            'semester' => self::getCurrentSemester(),
            'evaluation_status' => self::getEvaluationStatus(),
            'evaluation_start_date' => self::getValue('evaluation_start_date'),
            'evaluation_end_date' => self::getValue('evaluation_end_date'),
        ];
    }
}