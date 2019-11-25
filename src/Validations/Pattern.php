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

use Laramore\Fields\BaseField;

class Pattern extends BaseValidation
{
    public function isValueValid($value): bool
    {
        $field = $this->getField();

        return \preg_match($field->getPattern(), $value, $_, $field->getFlags()) === 1;
    }

    public function getMessage()
    {
        return 'This field does not correspond to a valid '.$this->getField()->getType()->native.'.';
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
