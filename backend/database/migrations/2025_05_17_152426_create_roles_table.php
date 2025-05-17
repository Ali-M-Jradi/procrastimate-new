<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Role extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->primaryId('id');
            $table->foreignId('user_id')->constrained('user')->onDelete('cascade')->primary();
            $table->string('title')->default('user');
            $table->string('description')->nullable();
            $table->boolean('isCompleted')->default(false);
            $table->timestamp('dueDate')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
