<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportResponse extends Model
{
    protected $fillable = [
        'report_id',
        'user_id',
        'message',
        'type',
    ];

    /**
     * Relasi ke laporan
     */
    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }

    /**
     * Relasi ke user yang membalas
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
