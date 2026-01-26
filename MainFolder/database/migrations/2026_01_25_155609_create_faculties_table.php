<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('faculties', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->string('faculty_code', 64)->unique();
        $table->string('first_name', 100);
        $table->string('middle_name', 100)->nullable();
        $table->string('last_name', 100);
        $table->string('suffix', 50)->nullable();
        $table->string('email', 255);
        $table->string('profile_picture', 500)->nullable();
        $table->string('contact_no', 30)->nullable();
        $table->foreignId('department_id')->constrained('departments'); 
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faculties');
    }
};
