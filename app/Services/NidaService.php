<?php

namespace App\Services;

use Illuminate\Support\Carbon;

class NidaService
{
    public function validateFormat(string $nida): bool
    {
        return (bool) preg_match('/^\d{8}-\d{5}-\d{5}-\d{2}$/', $nida);
    }

    public function extractDateOfBirth(string $nida): ?Carbon
    {
        $dobStr = substr($nida, 0, 8);
        $year = (int) substr($dobStr, 0, 4);
        $month = (int) substr($dobStr, 4, 2);
        $day = (int) substr($dobStr, 6, 2);

        if (!checkdate($month, $day, $year)) {
            return null;
        }

        $dob = Carbon::createFromDate($year, $month, $day);

        if ($dob->isFuture()) {
            return null;
        }

        return $dob;
    }

    public function calculateAge(string $nida): ?int
    {
        $dob = $this->extractDateOfBirth($nida);
        if (!$dob) {
            return null;
        }
        return $dob->age;
    }

    public function isValidNida(string $nida): bool
    {
        if (!$this->validateFormat($nida)) {
            return false;
        }
        return $this->extractDateOfBirth($nida) !== null;
    }
}
