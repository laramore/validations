<?php
/**
 * Define a basic validation rule.
 *
 * @author Samy Nastuzzi <samy@nastuzzi.fr>
 *
 * @copyright Copyright (c) 2019
 * @license MIT
 */

namespace Laramore\Validations;

use Laramore\Facades\Rules;
use Laramore\Fields\BaseField;

class NotNullable extends BaseValidation
{
    /**
     * Indicate if the field is for this validation.
     *
     * @param  BaseField $field
     * @return boolean
     */
    public static function isFieldValid(BaseField $field): bool
    {
        return !$field->hasRule(Rules::useCurrent());
    }

    /**
     * Return the valdation rule for validations.
     *
     * @return string
     */
    public function getValidationRule()
    {
        return 'not_nullable';
    }
}
