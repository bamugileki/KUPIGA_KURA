<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssistedVote extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'voter_id',
        'assistant_id',
        'election_id',
        'candidate_id',
        'assistant_name',
        'assistant_relationship',
        'voter_consent',
        'created_at',
    ];

    protected $casts = [
        'voter_consent' => 'boolean',
        'created_at' => 'datetime',
    ];

    public function voter()
    {
        return $this->belongsTo(User::class, 'voter_id');
    }

    public function assistant()
    {
        return $this->belongsTo(User::class, 'assistant_id');
    }

    public function election()
    {
        return $this->belongsTo(Election::class);
    }

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }
}
