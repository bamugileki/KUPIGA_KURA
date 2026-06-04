<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PoliticalParty extends Model
{
    protected $fillable = [
        'name',
        'abbreviation',
        'logo',
        'registration_number',
        'status',
    ];

    public function candidates()
    {
        return $this->hasMany(Candidate::class, 'party_id');
    }
}
