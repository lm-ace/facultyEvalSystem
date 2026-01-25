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
       Schema::create('class_offerings', function (Blueprint $table) {
        $table->id();
        $table->foreignId('class_section_id')->constrained('class_sections')->onDelete('cascade');
        $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
        $table->foreignId('faculty_id')->nullable()->constrained('faculties')->onDelete('cascade');
        $table->foreignId('semester_id')->constrained('review_periods'); // Points to review_periods
        $table->timestamps();

        $table->unique(['class_section_id', 'subject_id', 'semester_id'], 'offering_unique_idx');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_offerings');
    }
};
