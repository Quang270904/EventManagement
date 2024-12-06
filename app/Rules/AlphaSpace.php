<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AlphaSpace implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_string($value)) {
            $fail("The $attribute must be a string.");
            return;
        }

        foreach (str_split($value) as $char) {
            if (!ctype_alpha($char) && $char !== ' ') {
                $fail("The $attribute may only contain letters and spaces.");
                return;
            }
        }
        
    }
}
