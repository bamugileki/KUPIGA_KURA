<?php

namespace App\Services;

use App\Models\User;
use App\Models\SuspiciousLog;
use App\Events\FraudDetected;
use Illuminate\Http\Request;

class FraudDetectionService
{
    public function checkIdentity(User $user, array $data): array
    {
        $flags = [];

        if (isset($data['nida_number'])) {
            if (User::where('nida_number', $data['nida_number'])
                ->when($user->id, fn($q) => $q->where('id', '!=', $user->id))
                ->exists()) {
                $flags[] = 'Duplicate NIDA registration attempt';
            }
        }

        return $flags;
    }

    public function checkRegistration(array $data): array
    {
        $flags = [];

        if (isset($data['nida_number'])) {
            $nidaService = app(NidaService::class);
            if (!$nidaService->isValidNida($data['nida_number'])) {
                $flags[] = 'Invalid NIDA format detected';
            } else {
                $age = $nidaService->calculateAge($data['nida_number']);
                if ($age !== null && $age < 18) {
                    $flags[] = 'Underage registration attempt';
                }
            }
        }

        if (isset($data['nida_number'])) {
            if (User::where('nida_number', $data['nida_number'])->exists()) {
                $flags[] = 'Duplicate NIDA registration attempt';
            }
        }
        if (isset($data['driving_licence'])) {
            if (User::where('driving_licence', $data['driving_licence'])->exists()) {
                $flags[] = 'Duplicate driving licence registration attempt';
            }
        }
        if (isset($data['nhif_number'])) {
            if (User::where('nhif_number', $data['nhif_number'])->exists()) {
                $flags[] = 'Duplicate NHIF registration attempt';
            }
        }

        return $flags;
    }

    public function checkLogin(User $user): array
    {
        $flags = [];

        if ($user->failed_attempts >= 3) {
            $flags[] = 'Repeated failed login attempts';
        }

        return $flags;
    }

    public function flag(string $reason, ?User $user = null, ?string $ip = null): void
    {
        $log = SuspiciousLog::create([
            'user_id' => $user?->id,
            'reason' => $reason,
            'ip_address' => $ip ?? request()->ip(),
            'timestamp' => now(),
        ]);

        if ($user) {
            event(new FraudDetected($user, $reason));
        }
    }
}
