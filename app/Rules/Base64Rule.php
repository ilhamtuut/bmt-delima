<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Base64Rule implements Rule
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
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return $this->validateImg($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be base64.';
    }

    public function validateImg($data)
    {
        try {
            $binary = base64_decode(explode(',', $data)[1]);
            $data = getimagesizefromstring($binary);
        } catch (\Exception $e) {
          return false;
        }

        $allowed = ['image/jpeg', 'image/png', 'image/jpg'];

        if (!$data) {
            return false;
        }

        if (!empty($data[0]) && !empty($data[0]) && !empty($data['mime'])) {
            if (in_array($data['mime'], $allowed)) {
                return true;
            }
        }

        return false;
    }
}
