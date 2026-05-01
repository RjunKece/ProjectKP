<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->string('file_path')->nullable()->after('status');
            $table->string('file_name')->nullable()->after('file_path');
            $table->string('link')->nullable()->after('file_name');
            $table->foreignId('target_id')->nullable()->after('link')
                  ->constrained('daily_targets')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropConstrainedForeignId('target_id');
            $table->dropColumn(['file_path', 'file_name', 'link']);
        });
    }
};
