<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Enable Row Level Security (RLS) on ALL tables in Supabase PostgreSQL.
 *
 * WHY:
 * Supabase exposes tables via its REST API (PostgREST). Without RLS,
 * anyone with the anon/public key can read/write ANY table.
 * This migration enables RLS and creates policies that:
 * - ALLOW full access for the 'postgres' role (used by Laravel server-side)
 * - DENY access via Supabase REST API (anon/authenticated) by default
 *
 * This does NOT affect Laravel functionality at all — Laravel connects
 * as the 'postgres' role which bypasses RLS or uses the USING(true) policy.
 */
return new class extends Migration
{
    /**
     * Tables to protect with RLS
     */
    private array $tables = [
        'users',
        'password_reset_tokens',
        'sessions',
        'cache',
        'cache_locks',
        'jobs',
        'job_batches',
        'failed_jobs',
        'roles',
        'divisions',
        'activities',
        'reports',
        'report_responses',
        'daily_targets',
        'migrations',
    ];

    public function up(): void
    {
        foreach ($this->tables as $table) {
            // Skip if table doesn't exist (safety)
            $exists = DB::select("SELECT to_regclass('public.{$table}') IS NOT NULL AS exists");
            if (!$exists[0]->exists) {
                continue;
            }

            // Enable RLS on the table
            DB::statement("ALTER TABLE public.{$table} ENABLE ROW LEVEL SECURITY;");

            // Create policy allowing full access for the postgres role (Laravel)
            // This ensures Laravel can still do everything normally
            DB::statement("
                DO $$
                BEGIN
                    -- Drop existing policy if any (idempotent)
                    DROP POLICY IF EXISTS laravel_full_access ON public.{$table};

                    -- Create policy: allow everything for postgres role
                    CREATE POLICY laravel_full_access ON public.{$table}
                        FOR ALL
                        TO postgres
                        USING (true)
                        WITH CHECK (true);
                END
                $$;
            ");
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $table) {
            $exists = DB::select("SELECT to_regclass('public.{$table}') IS NOT NULL AS exists");
            if (!$exists[0]->exists) {
                continue;
            }

            // Drop the policy
            DB::statement("DROP POLICY IF EXISTS laravel_full_access ON public.{$table};");

            // Disable RLS
            DB::statement("ALTER TABLE public.{$table} DISABLE ROW LEVEL SECURITY;");
        }
    }
};
