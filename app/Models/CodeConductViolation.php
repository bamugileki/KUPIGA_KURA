<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CodeConductViolation extends Model
{
    protected $table = 'code_conduct_violations';

    protected $fillable = [
        'reported_by',
        'accused_user_id',
        'candidate_id',
        'description',
        'evidence',
        'status',
        'resolution_notes',
        'resolved_by',
        'resolved_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function accused()
    {
        return $this->belongsTo(User::class, 'accused_user_id');
    }

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    public function resolver()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }
}
