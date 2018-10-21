<?php
namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Lukaswhite\UkPostcode\UkPostcode;

/**
 * Class Postcode
 *
 * Validates a UK postcode
 *
 * @package App
 */
class Postcode implements Rule
{
    /**
     * Validate a postcode
     *
     * @param string $attribute
     * @param string $value
     * @return bool
     */
    public function passes( $attribute, $value )
    {
        return UkPostcode::validate( urldecode( $value ) );
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Incorrectly formatted postcode.';
    }
}