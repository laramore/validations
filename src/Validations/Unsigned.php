<?php
/**
 * Validate that the value is unsigned.
 *
 * @author Samy Nastuzzi <samy@nastuzzi.fr>
 *
 * @copyright Copyright (c) 2019
 * @license MIT
 */

namespace Laramore\Validations;

use Laramore\Fields\BaseField;

class Unsigned extends BaseValidation
{
    /**
     * Indicate if the field is for this validation.
     *
     * @param  BaseField $field
     * @return boolean
     */
    public static function isFieldValid(BaseField $field): bool
    {
        return true;
    }

    /**
     * Return the valdation rule for validations.
     *
     * @return callback
     */
    public function getValidationRule()
    {
        return function ($value) {
            return ((integer) $value) >= 0;
        };
    }
}
