<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    protected $fillable = [
        'slug',
        'name_en',
        'name_sw',
        'description',
        'min_age',
        'requires_constituency',
        'requires_running_mate',
        'sort_order',
    ];
}
