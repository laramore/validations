<?php
/**
 * Validate that the value is .
 *
 * @author Samy Nastuzzi <samy@nastuzzi.fr>
 *
 * @copyright Copyright (c) 2019
 * @license MIT
 */

namespace Laramore\Validations;

use Laramore\Facades\Option;
use Laramore\Fields\BaseField;

class Required extends BaseValidation
{
    /**
     * Indicate if the field is for this validation.
     *
     * @param  BaseField $field
     * @return boolean
     */
    public static function isFieldValid(BaseField $field): bool
    {
        return !$field->hasOption(Option::nullable());
    }

    /**
     * Return the valdation option for validations.
     *
     * @param array<string,mixed> $data
     * @return string
     */
    public function getValidationRule(array $data)
    {
        return 'required';
    }
}
