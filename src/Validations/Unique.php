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
        return $constraint->getConstraintType() === BaseConstraint::UNIQUE;
    }

    /**
     * Return the valdation option for validations.
     *
     * @param array<string,mixed> $data
     * @return string|Rule
     */
    public function getValidationRule(array $data)
    {
        $constraint = $this->getConstraint();

        if (!\is_null($constraint) && $constraint->isComposed()) {
            $validationOption = Rule::unique($constraint->getMainTableName());
            $attributes = $constraint->getAttributes();
            \array_shift($attributes);

            foreach ($attributes as $attribute) {
                if (isset($data[$name = $attribute->getNative()])) {
                    $validationOption->where($name, $attribute->dry($data[$name]));
                }
            }

            return $validationOption;
        }

        return 'unique:'.$this->getField()->getMeta()->getTableName().','.$this->getField()->getNative();
    }
}
