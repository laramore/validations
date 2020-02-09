<?php
/**
 * Validate has a text value.
 *
 * @author Samy Nastuzzi <samy@nastuzzi.fr>
 *
 * @copyright Copyright (c) 2019
 * @license MIT
 */

namespace Laramore\Validations;

use Laramore\Fields\BaseField;

class NotBlank extends BaseValidation
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
     * @param array<string,mixed> $data
     * @return callback
     */
    public function getValidationRule(array $data)
    {
        return function ($name, $value): bool {
            return \count(\trim($value)) > 0;
        };
    }
}
