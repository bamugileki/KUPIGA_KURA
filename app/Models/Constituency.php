<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Constituency extends Model
{
    protected $fillable = [
        'name',
        'region',
        'ward',
    ];

    public function candidates()
    {
        return $this->hasMany(Candidate::class);
    }
}
