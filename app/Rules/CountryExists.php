<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Hyperzod\HyperzodServiceFunctions\Traits\HelpersServiceTrait;

class CountryExists implements Rule
{
    use HelpersServiceTrait;
    protected $VALUE;
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
        if (!is_string($value)) return false;

        $this->VALUE = $value;

        $country_codes = $this->fetchCountries();
        $country_codes = collect($country_codes)->pluck('code')->toArray();
        $country_present = in_array($value, $country_codes);

        if (!$country_present) {
            return false;
        }
        
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid value: ' . $this->VALUE;
    }
}