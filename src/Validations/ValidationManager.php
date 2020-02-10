<?php
/**
 * Define the validation manager class.
 *
 * @author Samy Nastuzzi <samy@nastuzzi.fr>
 *
 * @copyright Copyright (c) 2019
 * @license MIT
 */

namespace Laramore\Validations;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\{
    Str, Facades\Validator
};
use Laramore\Observers\BaseManager;
use Laramore\Interfaces\IsALaramoreManager;
use Laramore\Fields\BaseField;
use Laramore\Fields\Constraint\BaseConstraint;

class ValidationManager extends BaseManager implements IsALaramoreManager
{
    /**
     * Allowed observable sub class.
     *
     * @var string
     */
    protected $managedClass = Model::class;

    /**
     * The observable handler class to generate.
     *
     * @var string
     */
    protected $handlerClass = ValidationHandler::class;

    protected $validatorRules = [
        'forbidden', 'negative', 'not_nullable', 'not_zero', 'unsigned',
    ];

    /**
     * Add all validations for a specific field, based on configurations.
     *
     * @param BaseField $field
     * @return void
     */
    public function createValidationsForField(BaseField $field)
    {
        $handler = $this->getHandler($field->getMeta()->getModelClass());
        $propertyName = config('validation.property_name');
        $defaultPriority = config('validation.default_priority');

        $rulesValidations = \array_map(function ($rule) use ($propertyName) {
            return $rule->get($propertyName);
        }, $field->getRules());

        $validations = \array_merge(
            $field->getConfig($propertyName, []),
            $field->getType()->get($propertyName),
            ...\array_values($rulesValidations)
        );

        foreach ($validations as $data) {
            if (\is_string($data)) {
                [$validationClass, $priority] = [$data, $defaultPriority];
            } else {
                [$validationClass, $priority] = [$data[0], ($data[1] ?? $defaultPriority)];
            }

            if ($validationClass && $validationClass::isFieldValid($field)) {
                $handler->add($validationClass::validation($field, $priority));
            }
        }
    }

    /**
     * Add all validations for a specific constraint, based on configurations.
     *
     * @param BaseConstraint $constraint
     * @return void
     */
    public function createValidationsForConstraint(BaseConstraint $constraint)
    {
        $field = $constraint->getMainAttribute();
        $handler = $this->getHandler($field->getMeta()->getModelClass());
        $propertyName = config('validation.property_name');
        $defaultPriority = config('validation.default_priority');
        $validations = $constraint->getConfig($propertyName, []);

        foreach ($validations as $data) {
            if (\is_string($data)) {
                [$validationClass, $priority] = [$data, $defaultPriority];
            } else {
                [$validationClass, $priority] = [$data[0], ($data[1] ?? $defaultPriority)];
            }

            if ($validationClass && $validationClass::isConstraintValid($constraint)
                && $validationClass::isFieldValid($field)) {
                $handler->add($validationClass::validationConstraint($constraint, $priority));
            }
        }
    }

    /**
     * Validate unsigned validation rule.
     *
     * @param mixed $attribute
     * @param mixed $value
     * @param mixed $parameters
     * @param mixed $validator
     *
     * @return boolean
     */
    public function validateForbidden($attribute, $value, $parameters, $validator): bool
    {
        return false;
    }

    /**
     * Validate negative validation rule.
     *
     * @param mixed $attribute
     * @param mixed $value
     * @param mixed $parameters
     * @param mixed $validator
     *
     * @return boolean
     */
    public function validateNegative($attribute, $value, $parameters, $validator): bool
    {
        return ((int) $value) <= 0;
    }

    /**
     * Validate not nullable validation rule.
     *
     * @param mixed $attribute
     * @param mixed $value
     * @param mixed $parameters
     * @param mixed $validator
     *
     * @return boolean
     */
    public function validateNotNullable($attribute, $value, $parameters, $validator): bool
    {
        return !\is_null($value);
    }

    /**
     * Validate not zero validation rule.
     *
     * @param mixed $attribute
     * @param mixed $value
     * @param mixed $parameters
     * @param mixed $validator
     *
     * @return boolean
     */
    public function validateNotZero($attribute, $value, $parameters, $validator): bool
    {
        return ((int) $value) !== 0;
    }

    /**
     * Validate forbidden validation rule.
     *
     * @param mixed $attribute
     * @param mixed $value
     * @param mixed $parameters
     * @param mixed $validator
     *
     * @return boolean
     */
    public function validateUnsigned($attribute, $value, $parameters, $validator): bool
    {
        return ((int) $value) >= 0;
    }

    /**
     * Extend multiple validation rules in validator.
     *
     * @return void
     */
    public function extendValidatorRules()
    {
        foreach ($this->validatorRules as $validatorRule) {
            Validator::extend($validatorRule, [$this, 'validate'.Str::studly($validatorRule)]);
        }
    }
}
