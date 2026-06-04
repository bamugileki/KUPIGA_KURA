<?php

namespace App\Http\Controllers;

use App\Models\Objection;
use App\Models\Election;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class ObjectionController extends Controller
{
    public function submitForm()
    {
        $elections = Election::whereIn('status', ['objection_period', 'closed'])
            ->orderBy('end_time', 'desc')
            ->get();
        return view('objections.submit', compact('elections'));
    }

    public function submit(Request $request)
    {
        $request->validate([
            'type' => 'required|in:nomination,petition,election',
            'candidate_id' => 'nullable|integer|exists:candidates,id',
            'election_id' => 'nullable|integer|exists:elections,id',
            'reason' => 'required|string|min:20',
            'evidence' => 'nullable|string',
        ]);

        if ($request->type === 'election') {
            $election = Election::findOrFail($request->election_id);
            if (!$election->isObjectionPeriod()) {
                return back()->with('error', 'Objections for this election are not currently being accepted.');
            }
            $existing = Objection::where('objector_id', Auth::id())
                ->where('election_id', $request->election_id)
                ->where('type', 'election')
                ->exists();
            if ($existing) {
                return back()->with('error', 'You have already submitted an objection for this election.');
            }
        }

        $objection = Objection::create([
            'type' => $request->type,
            'objector_id' => Auth::id(),
            'candidate_id' => $request->candidate_id,
            'election_id' => $request->election_id,
            'reason' => $request->reason,
            'evidence' => $request->evidence,
            'status' => 'pending',
        ]);

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'OBJECTION_SUBMITTED',
            'details' => "Objection submitted: {$objection->type}",
            'ip_address' => $request->ip(),
            'device_info' => $request->userAgent(),
            'timestamp' => Carbon::now(),
        ]);

        if ($request->type === 'election' && $election->objectionThresholdReached()) {
            $election->update([
                'status' => 'returned',
                'objection_triggered' => true,
            ]);
            AuditLog::create([
                'user_id' => null,
                'action' => 'ELECTION_RETURNED',
                'details' => "Election #{$election->id} returned due to 75% objection threshold",
                'timestamp' => Carbon::now(),
            ]);
            return redirect()->route('dashboard')->with('success', 'Your objection has been submitted. The election has been flagged for review due to reaching the objection threshold.');
        }

        return redirect()->route('dashboard')->with('success', 'Your objection has been submitted for review.');
    }
}
