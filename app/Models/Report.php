<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Report extends Model
{
    protected $fillable = [
        'title',
        'type',
        'scope',
        'division_id',
        'description',
        'start_date',
        'end_date',
        'status',
        'priority',
        'created_by',
    ];

    /**
     * Pembuat laporan
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Divisi tujuan (hanya jika scope = 'division')
     */
    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    /**
     * Balasan/tanggapan terhadap laporan
     */
    public function responses(): HasMany
    {
        return $this->hasMany(ReportResponse::class);
    }

    /**
     * Hanya balasan (bukan acknowledgment)
     */
    public function replies(): HasMany
    {
        return $this->hasMany(ReportResponse::class)->where('type', 'reply');
    }

    /**
     * Acknowledgments
     */
    public function acknowledgments(): HasMany
    {
        return $this->hasMany(ReportResponse::class)->where('type', 'acknowledgment');
    }

    /**
     * Scope: Laporan Perusahaan
     */
    public function scopeOfCompany($query)
    {
        return $query->where('scope', 'company');
    }

    /**
     * Scope: Laporan Divisi
     */
    public function scopeOfDivision($query, $divisionId = null)
    {
        $q = $query->where('scope', 'division');
        if ($divisionId) {
            $q->where('division_id', $divisionId);
        }
        return $q;
    }
}
