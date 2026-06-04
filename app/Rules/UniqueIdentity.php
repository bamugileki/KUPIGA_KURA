<?php

namespace App\Rules;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueIdentity implements ValidationRule
{
    protected ?string $field;
    protected ?int $ignoreUserId;

    public function __construct(string $field, ?int $ignoreUserId = null)
    {
        $this->field = $field;
        $this->ignoreUserId = $ignoreUserId;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $query = User::where($this->field, $value);

        if ($this->ignoreUserId) {
            $query->where('id', '!=', $this->ignoreUserId);
        }

        if ($query->exists()) {
            $labels = [
                'nida_number' => 'NIDA',
                'driving_licence' => 'Driving licence',
                'nhif_number' => 'NHIF',
            ];
            $label = $labels[$this->field] ?? $this->field;
            $fail("This {$label} number is already registered.");
        }
    }
}
