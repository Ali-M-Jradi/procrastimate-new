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
        Schema::create('group_tasks', function (Blueprint $table) {
            $table->primaryId('id');
            $table->stirng('name')->default('user');
            $table->text('description')->nullable();
            $table->boolean('isPublic')->default(true);
            $table->integer('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_tasks');
    }
};
