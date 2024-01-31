<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class AcademicYear implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $yearArr = explode('/', $value);

        foreach ($yearArr as $year) {
            if (!is_numeric($year)) return false;
        }

        return ($yearArr[1] - $yearArr[0]) == 1;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ':attribute not in the right format';
    }
}
