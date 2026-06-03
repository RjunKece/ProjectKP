<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;

final class DatabaseDate
{
    /**
     * SQL expression untuk mengelompokkan tanggal per hari (kompatibel MySQL & PostgreSQL).
     */
    public static function dayExpression(string $column): string
    {
        return DB::connection()->getDriverName() === 'pgsql'
            ? "({$column})::date"
            : "DATE({$column})";
    }

    /**
     * SQL expression untuk mengelompokkan tanggal per bulan YYYY-MM.
     */
    public static function monthKeyExpression(string $column): string
    {
        return DB::connection()->getDriverName() === 'pgsql'
            ? "to_char({$column}, 'YYYY-MM')"
            : "DATE_FORMAT({$column}, '%Y-%m')";
    }
}
