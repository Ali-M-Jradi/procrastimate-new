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
            // Remove the old foreign key constraint first
            $table->dropForeign(['user_id']);
            
            // Rename the column
            $table->renameColumn('user_id', 'from_user_id');
            
            // Add the new foreign key constraint
            $table->foreign('from_user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            // Remove the new foreign key constraint
            $table->dropForeign(['from_user_id']);
            
            // Rename back to original
            $table->renameColumn('from_user_id', 'user_id');
            
            // Add back the original foreign key constraint
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
        });
    }
};
