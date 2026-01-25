<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            // Stores who performed the action (Admin ID)
            $table->unsignedBigInteger('admin_id')->nullable(); 

            // Details of the action
            $table->string('action');       // e.g., "Created", "Deleted"
            $table->string('module');       // e.g., "Faculty", "Review Period"
            $table->text('description');    // e.g., "Added faculty: John Doe"
            $table->string('ip_address')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('activity_logs');
    }
};