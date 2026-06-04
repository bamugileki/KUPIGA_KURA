<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Election extends Model
{
    protected $fillable = [
        'title_en',
        'title_sw',
        'election_type',
        'start_time',
        'end_time',
        'nomination_start',
        'nomination_end',
        'campaign_start',
        'campaign_end',
        'status',
        'candidates_published',
        'voting_enabled',
        'created_by',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'nomination_start' => 'datetime',
        'nomination_end' => 'datetime',
        'campaign_start' => 'datetime',
        'campaign_end' => 'datetime',
        'objection_deadline' => 'datetime',
        'objection_triggered' => 'boolean',
        'candidates_published' => 'boolean',
        'voting_enabled' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function candidates()
    {
        return $this->hasMany(Candidate::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function isVotingOpen()
    {
        $now = Carbon::now();
        return $this->status === 'active'
            && $now->gte($this->start_time)
            && $now->lte($this->end_time);
    }

    public function isNominationOpen()
    {
        $now = Carbon::now();
        return $this->status === 'nomination_open'
            && $this->nomination_start
            && $this->nomination_end
            && $now->gte($this->nomination_start)
            && $now->lte($this->nomination_end);
    }

    public function isCampaignPeriod()
    {
        $now = Carbon::now();
        return $this->status === 'campaign_period'
            && $this->campaign_start
            && $this->campaign_end
            && $now->gte($this->campaign_start)
            && $now->lte($this->campaign_end);
    }

    public function statusLabel(): string
    {
        $labels = [
            'draft' => 'Draft',
            'nomination_open' => 'Nomination Open',
            'published' => 'Published',
            'campaign_period' => 'Campaign Period',
            'active' => 'Voting Active',
            'closed' => 'Closed',
            'objection_period' => 'Objection Period',
            'returned' => 'Returned',
        ];
        return $labels[$this->status] ?? $this->status;
    }

    public function nextStatus(): ?string
    {
        $flow = [
            'draft' => 'nomination_open',
            'nomination_open' => 'published',
            'published' => 'campaign_period',
            'campaign_period' => 'active',
            'active' => 'closed',
            'closed' => 'objection_period',
            'objection_period' => null,
            'returned' => null,
        ];
        return $flow[$this->status] ?? null;
    }

    public function isObjectionPeriod(): bool
    {
        $now = Carbon::now();
        return $this->status === 'objection_period'
            && $this->objection_deadline
            && $now->lte($this->objection_deadline);
    }

    public function totalVoters(): int
    {
        return User::where('status', 'active')
            ->where(function ($q) {
                $q->where('is_voter', true)->orWhere('is_candidate', true);
            })
            ->count();
    }

    public function totalObjections(): int
    {
        return $this->objections()->where('type', 'election')->count();
    }

    public function objectionThresholdReached(): bool
    {
        $totalVoters = $this->totalVoters();
        if ($totalVoters === 0) return false;
        return ($this->totalObjections() / $totalVoters) >= 0.75;
    }

    public function objections()
    {
        return $this->hasMany(\App\Models\Objection::class);
    }

    public function scopeVisible($query)
    {
        $now = Carbon::now();
        return $query->where('status', 'published')
            ->where('start_time', '<=', $now)
            ->where('end_time', '>=', $now)
            ->whereHas('candidates', function ($q) {
                $q->where('status', 'approved');
            })
            ->whereHas('creator', function ($q) {
                $q->where('status', 'active');
            });
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function isVisible(): bool
    {
        if ($this->status !== 'published') {
            return false;
        }
        $now = Carbon::now();
        if ($now->lt($this->start_time) || $now->gt($this->end_time)) {
            return false;
        }
        $hasApprovedCandidates = $this->candidates()->where('status', 'approved')->exists();
        if (!$hasApprovedCandidates) {
            return false;
        }
        $hasActiveVoters = User::where('status', 'active')
            ->where(function ($q) {
                $q->where('is_voter', true)->orWhere('is_candidate', true);
            })
            ->exists();
        if (!$hasActiveVoters) {
            return false;
        }
        return true;
    }
}
