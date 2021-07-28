<?php
/**
 * Validate that the value is a foreign in database.
 *
 * @author Samy Nastuzzi <samy@nastuzzi.fr>
 *
 * @copyright Copyright (c) 2020
 * @license MIT
 */

namespace Laramore\Validations;

use Illuminate\Validation\Rule;
use Laramore\Contracts\Field\{
    Field, RelationField
};
use Laramore\Fields\Constraint\{
    BaseConstraint, BaseRelationalConstraint
};

class Foreign extends BaseConstraintValidation
{
    /**
     * Indicate if the field is for this validation.
     *
     * @param  Field $field
     * @return boolean
     */
    public static function isFieldValid(Field $field): bool
    {
        return ($field instanceof RelationField);
    }

    /**
     * Indicate if the field is for this validation.
     *
     * @param  BaseConstraint $constraint
     * @return boolean
     */
    public static function isConstraintValid(BaseConstraint $constraint): bool
    {
        return $constraint->getConstraintType() === BaseRelationalConstraint::FOREIGN;
    }

    /**
     * Return the valdation option for validations.
     *
     * @return string|Rule
     */
    public function getRule()
    {
        $attribute = $this->getConstraint()->getTargetAttribute();

        return 'exists:'.$attribute->getModel().','.$attribute;
    }
}
