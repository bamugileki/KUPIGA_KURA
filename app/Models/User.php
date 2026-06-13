<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'nida_number',
        'driving_licence',
        'nhif_number',
        'password',
        'role',
        'is_voter',
        'is_candidate',
        'is_admin',
        'is_officer',
        'is_observer',
        'status',
        'language',
        'age',
        'is_verified',
        'email_verified',
        'terms_accepted',
        'accessibility_enabled',
        'disability_type',
        'accessibility_mode',
        'high_contrast',
        'text_size',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_voter' => 'boolean',
        'is_candidate' => 'boolean',
        'is_admin' => 'boolean',
        'is_officer' => 'boolean',
        'is_observer' => 'boolean',
        'is_verified' => 'boolean',
        'email_verified' => 'boolean',
        'terms_accepted' => 'boolean',
        'accessibility_enabled' => 'boolean',
        'high_contrast' => 'boolean',
        'disability_type' => 'json',
        'locked_until' => 'datetime',
        'created_at' => 'datetime',
    ];

    protected $guarded = [
        'nida_number',
        'driving_licence',
        'nhif_number',
    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function isLocked()
    {
        if ($this->locked_until && Carbon::now()->lt($this->locked_until)) {
            return true;
        }
        if ($this->locked_until && Carbon::now()->gte($this->locked_until)) {
            $this->failed_attempts = 0;
            $this->locked_until = null;
            $this->save();
        }
        return false;
    }

    public function isVoter(): bool
    {
        return $this->is_voter;
    }

    public function isCandidate(): bool
    {
        return $this->is_candidate;
    }

    public function isAdmin(): bool
    {
        return $this->is_admin;
    }

    public function isOfficer(): bool
    {
        return $this->is_officer;
    }

    public function isObserver(): bool
    {
        return $this->is_observer;
    }

    public function isElectoralOfficer(): bool
    {
        return $this->is_officer;
    }

    public function isPollingAgent(): bool
    {
        return false;
    }

    public function hasAdminAccess(): bool
    {
        return $this->is_admin || $this->is_officer;
    }

    public function canVote(): bool
    {
        return $this->is_voter || $this->is_candidate || $this->is_admin;
    }

    public function candidate()
    {
        return $this->hasOne(Candidate::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }
}
