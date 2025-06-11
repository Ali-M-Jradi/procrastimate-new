<?php
// database/migrations/2025_06_12_000001_update_tasks_status_column.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            if (Schema::hasColumn('tasks', 'isCompleted')) {
                $table->dropColumn('isCompleted');
            }
            $table->enum('status', ['pending', 'approved', 'completed', 'out_of_date'])->default('pending');
        });
    }

    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->boolean('isCompleted')->default(false);
            $table->dropColumn('status');
        });
    }
};
