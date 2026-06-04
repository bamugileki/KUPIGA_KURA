<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    protected $fillable = [
        'user_id',
        'election_id',
        'position',
        'constituency',
        'manifesto',
        'status',
        'terms_accepted',
        'approved_at',
        'full_name',
        'gender',
        'date_of_birth',
        'nationality',
        'phone',
        'email',
        'nida_number',
        'party_name',
        'party_abbreviation',
        'party_leader',
        'party_registration_number',
        'photo',
        'party_logo',
        'documents',
        'biography',
        'education',
        'political_experience',
        'residential_address',
        'party_membership_number',
        'ward_name',
        'constituency_id',
        'party_id',
        'running_mate_name',
        'running_mate_photo',
        'nomination_submitted_at',
    ];

    protected $casts = [
        'terms_accepted' => 'boolean',
        'approved_at' => 'datetime',
        'date_of_birth' => 'date',
        'documents' => 'array',
        'nomination_submitted_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function election()
    {
        return $this->belongsTo(Election::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function constituency()
    {
        return $this->belongsTo(Constituency::class);
    }

    public function party()
    {
        return $this->belongsTo(PoliticalParty::class, 'party_id');
    }

    public function nominationSupport()
    {
        return $this->hasMany(NominationSupport::class);
    }
}
