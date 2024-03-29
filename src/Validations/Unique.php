<?php
/**
 * Validate that the value is a unique in database.
 *
 * @author Samy Nastuzzi <samy@nastuzzi.fr>
 *
 * @copyright Copyright (c) 2020
 * @license MIT
 */

namespace Laramore\Validations;

use Illuminate\Validation\Rule;
use Laramore\Contracts\Field\{
    Field, AttributeField
};
use Laramore\Fields\Constraint\BaseConstraint;
use Laramore\Fields\Constraint\BaseIndexableConstraint;

class Unique extends BaseConstraintValidation
{
    /**
     * Indicate if the field is for this validation.
     *
     * @param  Field $field
     * @return boolean
     */
    public static function isFieldValid(Field $field): bool
    {
        return ($field instanceof AttributeField);
    }

    /**
     * Indicate if the field is for this validation.
     *
     * @param  BaseConstraint $constraint
     * @return boolean
     */
    public static function isConstraintValid(BaseConstraint $constraint): bool
    {
        return $constraint->getConstraintType() === BaseIndexableConstraint::UNIQUE;
    }

    /**
     * Return the valdation option for validations.
     *
     * @return string|Rule
     */
    public function getRule()
    {
        return 'index:'.$this->getField()->getMeta()->getModelClass();
    }
}
