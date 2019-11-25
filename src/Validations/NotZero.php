<?php
/**
 * Validate that the value is not zero.
 *
 * @author Samy Nastuzzi <samy@nastuzzi.fr>
 *
 * @copyright Copyright (c) 2019
 * @license MIT
 */

namespace Laramore\Validations;

use Laramore\Fields\BaseField;

class NotZero extends BaseValidation
{
    public function isValueValid($value): bool
    {
        return $value !== 0;
    }

    public function getMessage()
    {
        return "The value cannot be equal to 0.";
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
