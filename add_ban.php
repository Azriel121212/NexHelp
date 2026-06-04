<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

if (!Schema::hasTable('reports')) {
    Schema::create('reports', function (Blueprint $table) {
        $table->id();
        $table->foreignId('reporter_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('reported_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('task_id')->nullable()->constrained('tasks')->onDelete('cascade');
        $table->text('reason');
        $table->string('status')->default('Pending');
        $table->timestamps();
    });
    echo "Reports table created.\n";
}

Schema::table('users', function(Blueprint $table) {
    if (!Schema::hasColumn('users', 'is_banned')) {
        $table->boolean('is_banned')->default(false)->after('points');
    }
});
echo "Done!\n";
