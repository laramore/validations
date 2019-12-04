<?php
/**
 * Validate that the value corresponds to a valid pattern.
 *
 * @author Samy Nastuzzi <samy@nastuzzi.fr>
 *
 * @copyright Copyright (c) 2019
 * @license MIT
 */

namespace Laramore\Validations;

use Laramore\Fields\{
    BaseField, Pattern as PatternField
};

class Pattern extends BaseValidation
{
    /**
     * Indicate if the field is for this validation.
     *
     * @param  BaseField $field
     * @return boolean
     */
    public static function isFieldValid(BaseField $field): bool
    {
        return $field instanceof PatternField;
    }

    /**
     * Return the valdation rule for validations.
     *
     * @return string
     */
    public function getValidationRule()
    {
        return 'regex:'.$this->getField()->getPattern();
    }
}
