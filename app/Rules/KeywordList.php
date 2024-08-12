<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class KeywordList implements ValidationRule
{
    private string $keywords;

    public function __construct(
        array $keywords
    ) {
        $this->keywords = implode('|', $keywords);
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (preg_match("/^(?<keywords>($this->keywords)+)(,(?&keywords))*$/i", $value) !== 1) {
            $fail('The :attribute must be a comma-separated list of keywords.');
        }
    }
}
