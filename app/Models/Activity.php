<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = [
        'user_id',
        'tanggal',
        'deskripsi',
        'status',
        'file_path',
        'file_name',
        'link',
        'target_id',
    ];

    protected $casts = [
        'tanggal' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function target()
    {
        return $this->belongsTo(DailyTarget::class, 'target_id');
    }
}