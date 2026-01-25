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
       Schema::create('students', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->string('student_number', 32)->unique();
        $table->string('first_name', 100);
        $table->string('middle_name', 100)->nullable();
        $table->string('last_name', 100);
        $table->string('suffix', 50)->nullable();
        $table->tinyInteger('year_level');
        $table->string('block_section', 50);
        $table->string('contact_no', 30)->nullable();
        $table->timestamps();
        $table->foreignId('section_id')->nullable()->constrained('class_sections')->onDelete('set null');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
