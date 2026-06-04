<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuspiciousLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'reason',
        'ip_address',
        'timestamp',
    ];

    protected $casts = [
        'timestamp' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
