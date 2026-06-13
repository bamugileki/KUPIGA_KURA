<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccessibilityLog extends Model
{
    protected $fillable = [
        'user_id', 'old_mode', 'new_mode', 'notes', 'changed_at',
    ];

    public $timestamps = false;

    protected $casts = [
        'changed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
