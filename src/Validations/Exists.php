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
    Field, AttributeField
};
use Laramore\Fields\Constraint\BaseConstraint;

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
        return $constraint->getConstraintType() === BaseConstraint::FOREIGN;
    }

    /**
     * Return the valdation option for validations.
     *
     * @param array<string,mixed> $data
     * @return string
     */
    public function getValidationRule(array $data)
    {
        return 'exists:'.$this->getField()->getMeta()->getTableName().','.$this->getField()->getNative();
    }
}
