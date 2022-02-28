<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Log;
use Validator;

class EmailCurrentOrUnique implements Rule
{
    private ?string $currentEmail;
    private ?string $table;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(string $table, string $field, ?string $currentEmail = null)
    {
        $this->field = $field;
        $this->table = $table;
        $this->currentEmail = $currentEmail;
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
        if (isset($this->currentEmail) && strtolower($this->currentEmail) === strtolower($value)) return true;
        Log::error($this->currentEmail);
        Log::error($value);
        Log::error('unique:' . $this->table . ',' . $this->field);
        $validate = Validator::make(['email' => $this->currentEmail], ['email' => 'unique:' . $this->table . ',' . $this->field,]);
        //Log::error($validate);
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The email address must either be the current one, or not already exist in the system.';
    }
}
