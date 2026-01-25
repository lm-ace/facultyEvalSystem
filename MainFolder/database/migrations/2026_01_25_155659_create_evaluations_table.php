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
       Schema::create('evaluations', function (Blueprint $table) {
        $table->id();
        $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
        $table->foreignId('faculty_id')->constrained('faculties')->onDelete('cascade');
        $table->foreignId('class_offering_id')->constrained('class_offerings')->onDelete('cascade');
        $table->foreignId('review_period_id')->constrained('review_periods');
        $table->timestamp('submitted_at')->nullable();
        $table->decimal('overall_rating', 3, 2)->nullable();
        $table->text('feedback_text')->nullable();
        $table->tinyInteger('completed')->default(0);
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};
