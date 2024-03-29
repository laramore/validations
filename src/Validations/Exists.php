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

use Laramore\Contracts\Field\{
    Field, AttributeField, EnumField
};
use Laramore\Fields\Constraint\{
    BaseConstraint, BaseIndexableConstraint
};

class Exists extends BaseConstraintValidation
{
    /**
     * Indicate if the field is for this validation.
     *
     * @param  Field $field
     * @return boolean
     */
    public static function isFieldValid(Field $field): bool
    {
        return ($field instanceof AttributeField) || ($field instanceof EnumField);
    }

    /**
     * Indicate if the field is for this validation.
     *
     * @param  BaseConstraint $constraint
     * @return boolean
     */
    public static function isConstraintValid(BaseConstraint $constraint): bool
    {
        return $constraint->getConstraintType() === BaseIndexableConstraint::FOREIGN;
    }

    /**
     * Return the valdation option for validations.
     *
     * @return string
     */
    public function getRule()
    {
        $field = $this->getField();

        if ($field instanceof EnumField) {
            return function ($name, $value) use ($field): bool {
                return $field->has($value);
            };
        }

        return 'exists:'.$this->getField()->getMeta()->getTableName().','.$this->getField()->getNative();
    }
}
