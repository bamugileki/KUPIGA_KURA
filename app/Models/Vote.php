<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Vote extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'candidate_id',
        'election_id',
        'timestamp',
        'vote_hash',
    ];

    protected $casts = [
        'timestamp' => 'datetime',
    ];

    protected $hidden = [
        'user_id',
    ];

    public function setCandidateIdAttribute($value)
    {
        $this->attributes['candidate_id'] = $value ? Crypt::encryptString((string) $value) : null;
    }

    public function getCandidateIdAttribute($value)
    {
        if ($value === null) return null;
        try {
            return (int) Crypt::decryptString($value);
        } catch (\Exception $e) {
            return (int) $value;
        }
    }

    public function voter()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    public function election()
    {
        return $this->belongsTo(Election::class);
    }
}
