<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ResetPasswordsSeeder extends Seeder
{
    public function run()
    {
        // Reset admin password
        $admin = User::where('user_id', 'ADMIN-001')->first();
        if ($admin) {
            $admin->password = Hash::make('admin123');
            $admin->save();
            echo "Admin password reset\n";
        }

        // Reset faculty passwords
        $facultyIds = ['FAC-001', 'FAC-002', 'FAC-003', 'FAC-004', 'FAC-005'];
        foreach ($facultyIds as $facultyId) {
            $faculty = User::where('user_id', $facultyId)->first();
            if ($faculty) {
                $faculty->password = Hash::make('faculty123');
                $faculty->save();
                echo "Faculty {$facultyId} password reset\n";
            }
        }

        // Reset student passwords
        $studentIds = ['2023-00123-MN-0', '2023-00124-MN-0'];
        foreach ($studentIds as $studentId) {
            $student = User::where('user_id', $studentId)->first();
            if ($student) {
                $student->password = Hash::make('student123');
                $student->save();
                echo "Student {$studentId} password reset\n";
            }
        }
    }
}