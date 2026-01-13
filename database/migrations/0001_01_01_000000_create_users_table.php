<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('user_id', 50)->unique()->comment('Format: FAC-001 or 2023-00123-MN-0');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['admin', 'faculty', 'student'])->default('student');
            $table->string('department_code', 10)->nullable();
            $table->string('section', 50)->nullable()->comment('For students only');
            $table->text('profile_image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_login_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            
            $table->foreign('department_code')->references('code')->on('departments')->onDelete('set null');
            $table->index(['role', 'department_code', 'is_active']);
            $table->index('section');
            $table->index('is_active');
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};