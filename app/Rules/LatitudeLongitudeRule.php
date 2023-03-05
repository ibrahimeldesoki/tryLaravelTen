<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class LatitudeLongitudeRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty(preg_match('/^[-]?(([0-8]?[0-9])(\.\d{1,18})?|90(\.0{1,18})?)$/', $value))) {
            $fail('The :attribute must be a valid latitude/longitude coordinate in decimal format.');
        }
    }
}
