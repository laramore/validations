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

class NotNullable extends Validation
{
    public function isValueValid($value): bool
    {
        return !is_null($value);
    }

    public function getMessage()
    {
        return 'This field cannot be null.';
    }

    /**
     * Indicate if the field is for this validation.
     *
     * @param  mixed $value
     * @return boolean
     */
    public static function isFieldValid(BaseField $field): bool
    {
        return !$field->hasRule(Rules::useCurrent());
    }
}
