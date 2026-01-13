<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('department_code', 10);
            $table->integer('year_level')->default(1);
            $table->enum('schedule_type', ['day', 'night'])->default('day');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->foreign('department_code')->references('code')->on('departments')->onDelete('cascade');
            $table->unique(['name', 'department_code']);
            $table->index('department_code');
        });
    }

    public function down()
    {
        Schema::dropIfExists('sections');
    }
};