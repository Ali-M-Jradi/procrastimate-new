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
        Schema::table('notifications', function (Blueprint $table) {
            // Drop existing foreign key constraints
            $table->dropForeign(['user_id']);
            $table->dropForeign(['to_user_id']);
            
            // Drop existing columns
            $table->dropColumn(['user_id', 'to_user_id']);
            
            // Add new notifiable columns
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            // Remove new columns
            $table->dropColumn(['type', 'notifiable_type', 'notifiable_id', 'data', 'read_at']);
            
            // Add back original columns
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('to_user_id')->nullable();
            
            // Add back foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('to_user_id')->references('id')->on('users')->onDelete('set null');
        });
    }
};
