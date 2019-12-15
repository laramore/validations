<?php
/**
 * Validate that the value has a min length.
 *
 * @author Samy Nastuzzi <samy@nastuzzi.fr>
 *
 * @copyright Copyright (c) 2019
 * @license MIT
 */

namespace Laramore\Validations;

use Laramore\Fields\BaseField;

class MinLength extends BaseValidation
{
    /**
     * Indicate if the field is for this validation.
     *
     * @param  BaseField $field
     * @return boolean
     */
    public static function isFieldValid(BaseField $field): bool
    {
        return !\is_null($field->getProperty('minLength', false));
    }

    /**
     * Return the valdation rule for validations.
     *
     * @return string
     */
    public function getValidationRule()
    {
        return 'min:'.$this->getField()->getProperty('minLength');
    }
}
