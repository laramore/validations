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

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rule as ValidationRule;
use Laramore\Fields\{
    BaseField, AttributeField, Constraint\BaseConstraint
};

class Unique extends BaseConstraintValidation
{
    /**
     * Indicate if the field is for this validation.
     *
     * @param  BaseField $field
     * @return boolean
     */
    public static function isFieldValid(BaseField $field): bool
    {
        return ($field instanceof AttributeField);
    }

    /**
     * Indicate if the field is for this validation.
     *
     * @param  BaseField $field
     * @return boolean
     */
    public static function isConstraintValid(BaseConstraint $constraint): bool
    {
        return $constraint->getConstraintName() === BaseConstraint::UNIQUE;
    }

    /**
     * Return the valdation rule for validations.
     *
     * @param array<string,mixed> $data
     * @return string|ValidationRule
     */
    public function getValidationRule(array $data)
    {
        $constraint = $this->getConstraint();

        if (!\is_null($constraint) && $constraint->isComposed()) {
            $validationRule = ValidationRule::unique($constraint->getMainTableName());
            $attributes = $constraint->getAttributes();
            \array_shift($attributes);

            foreach ($attributes as $attribute) {
                if (isset($data[$name = $attribute->getNative()])) {
                    $validationRule->where($name, $attribute->dry($data[$name]));
                }
            }

            return $validationRule;
        }

        return 'unique:'.$this->getField()->getMeta()->getTableName().','.$this->getField()->getNative();
    }
}
