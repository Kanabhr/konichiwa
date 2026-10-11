<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class MaxWords implements ValidationRule
{
    public function __construct(private int $max = 50) {}

    /**
     * Validate that the value does not exceed the maximum word count.
     *
     * Words are determined by splitting on any whitespace sequence; empty
     * tokens produced by leading/trailing whitespace are excluded via
     * PREG_SPLIT_NO_EMPTY, matching the behaviour described in the spec.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $tokens = preg_split('/\s+/', trim($value), -1, PREG_SPLIT_NO_EMPTY);
        $count  = count($tokens);

        if ($count > $this->max) {
            $fail("The :attribute may not exceed {$this->max} words.");
        }
    }
}
