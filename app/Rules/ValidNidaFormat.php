<?php

namespace App\Rules;

use App\Services\NidaService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidNidaFormat implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $nidaService = app(NidaService::class);

        if (!$nidaService->isValidNida($value)) {
            $fail('The :attribute must be a valid NIDA number (YYYYMMDD-XXXXX-XXXXX-XX) with a valid date of birth.');
        }
    }
}
