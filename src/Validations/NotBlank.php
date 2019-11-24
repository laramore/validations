<?php
/**
 * Validate that the value is not blank/empty.
 *
 * @author Samy Nastuzzi <samy@nastuzzi.fr>
 *
 * @copyright Copyright (c) 2019
 * @license MIT
 */

namespace Laramore\Validations;

use Laramore\Fields\BaseField;

class NotBlank extends Validation
{
    public function isValueValid($value): bool
    {
        return !empty(trim($value));
    }

    public function getMessage()
    {
        return "This field cannot be blank.";
    }

    /**
     * Indicate if the field is for this validation.
     *
     * @param  mixed $value
     * @return boolean
     */
    public static function isFieldValid(BaseField $field): bool
    {
        return true;
    }
}
