<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NominationSupport extends Model
{
    protected $table = 'nomination_support';

    protected $fillable = [
        'candidate_id',
        'region',
        'supporter_name',
        'supporter_nida',
        'notes',
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }
}
