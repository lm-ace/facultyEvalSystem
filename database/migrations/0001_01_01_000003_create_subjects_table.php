<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('name');
            $table->string('department_code', 10);
            $table->integer('units')->default(3);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->foreign('department_code')->references('code')->on('departments')->onDelete('cascade');
            $table->index(['department_code', 'is_active']);
            $table->index('is_active');
        });
    }

    public function down()
    {
        Schema::dropIfExists('subjects');
    }
};