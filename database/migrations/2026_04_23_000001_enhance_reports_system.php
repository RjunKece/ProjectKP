<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // ===== ENHANCE REPORTS TABLE =====
        Schema::table('reports', function (Blueprint $table) {
            // scope: 'company' = Laporan Perusahaan (pengumuman), 'division' = Laporan Divisi
            $table->string('scope')->default('company')->after('type');

            // division_id: nullable, only set when scope='division'
            $table->foreignId('division_id')
                  ->nullable()
                  ->after('scope')
                  ->constrained('divisions')
                  ->nullOnDelete();

            // priority for company announcements
            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])
                  ->default('normal')
                  ->after('status');
        });

        // ===== REPORT RESPONSES TABLE =====
        Schema::create('report_responses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('report_id')
                  ->constrained('reports')
                  ->cascadeOnDelete();

            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->text('message');

            // type: 'reply' = balasan, 'acknowledgment' = tanda sudah baca
            $table->enum('type', ['reply', 'acknowledgment'])
                  ->default('reply');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_responses');

        Schema::table('reports', function (Blueprint $table) {
            $table->dropForeign(['division_id']);
            $table->dropColumn(['scope', 'division_id', 'priority']);
        });
    }
};
