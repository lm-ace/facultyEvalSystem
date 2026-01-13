<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('evaluation_criteria', function (Blueprint $table) {
            $table->id();
            $table->text('question');
            $table->string('category', 100);
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index('category');
            $table->index('is_active');
            $table->index(['category', 'order']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('evaluation_criteria');
    }
};