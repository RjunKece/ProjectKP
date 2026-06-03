<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->index('tanggal', 'activities_tanggal_index');
            $table->index(['user_id', 'tanggal'], 'activities_user_tanggal_index');
            $table->index('status', 'activities_status_index');
        });

        Schema::table('reports', function (Blueprint $table) {
            $table->index('created_at', 'reports_created_at_index');
            $table->index('status', 'reports_status_index');
            $table->index('scope', 'reports_scope_index');
        });

        Schema::table('report_responses', function (Blueprint $table) {
            $table->index('report_id', 'report_responses_report_id_index');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->index(['role_id', 'division_id'], 'users_role_division_index');
        });
    }

    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropIndex('activities_tanggal_index');
            $table->dropIndex('activities_user_tanggal_index');
            $table->dropIndex('activities_status_index');
        });

        Schema::table('reports', function (Blueprint $table) {
            $table->dropIndex('reports_created_at_index');
            $table->dropIndex('reports_status_index');
            $table->dropIndex('reports_scope_index');
        });

        Schema::table('report_responses', function (Blueprint $table) {
            $table->dropIndex('report_responses_report_id_index');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_role_division_index');
        });
    }
};
