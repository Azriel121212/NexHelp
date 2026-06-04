<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

Schema::table('tasks', function(Blueprint $table) {
    if (!Schema::hasColumn('tasks', 'reject_reason')) {
        $table->text('reject_reason')->nullable()->after('status');
    }
});
echo "Done!\n";
