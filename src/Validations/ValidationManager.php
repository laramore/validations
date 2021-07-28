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
    Arr, Str, Facades\Validator
};
use Laramore\Observers\{
    BaseManager, BaseHandler
};
use Laramore\Contracts\Manager\LaramoreManager;
use Laramore\Fields\BaseField;
use Laramore\Fields\Constraint\{
    BaseConstraint, BaseIndexableConstraint, BaseRelationalConstraint
};

class ValidationManager extends BaseManager implements LaramoreManager
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

    protected $validatorOptions = [
        'forbidden', 'negative', 'not_nullable', 'not_zero', 'unsigned',
    ];

    /**
     * Generate on demand a validation handler.
     *
     * @param string $modelClass
     *
     * @return BaseHandler
     */
    protected function generateHandler(string $modelClass): BaseHandler
    {
        $this->locked = false;
        $handler = $this->createHandler($modelClass);
        $this->locked = true;

        $meta = $modelClass::getMeta();

        foreach ($meta->getFields() as $field) {
            $this->createValidationsForField($field);
        }

        foreach ($meta->getConstraintHandler()->getConstraints() as $constraint) {
            $this->createValidationsForConstraint($constraint);
        }

        $handler->lock();

        return $handler;
    }

    /**
     * Return the observable handler for the given observable class.
     *
     * @param  string $modelClass
     * @return BaseHandler
     */
    public function getHandler(string $modelClass): BaseHandler
    {
        if (!$this->hasHandler($modelClass)) {
            return $this->generateHandler($modelClass);
        }

        return $this->handlers[$modelClass];
    }

    /**
     * Add all validations for a specific field, based on configurations.
     *
     * @param BaseField $field
     * @return void
     */
    public function createValidationsForField(BaseField $field)
    {
        $handler = $this->getHandler($field->getMeta()->getModelClass());

        $optionsValidations = \array_map(function ($option) {
            return $option->validations;
        }, $field->getOptions());

        $validations = \array_merge(
            $field->getValidationConfig(),
            ...\array_values($optionsValidations)
        );

        foreach ($validations as $validationClass => $data) {
            if (!\is_null($data) && $validationClass::isFieldValid($field)) {
                $handler->add($validationClass::validation($field, Arr::get($data, 'priority', Validation::MEDIUM_PRIORITY)));
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
        $field = $constraint->getAttributes()[0];
        $handler = $this->getHandler($field->getMeta()->getModelClass());

        if (\in_array($constraint->getConstraintType(), BaseIndexableConstraint::$migrable)) {
            $handler->add(
                Unique::validationConstraint($constraint, Validation::CONSTRAINT_PRIORITY)
            );
        } else if (\in_array($constraint->getConstraintType(), BaseRelationalConstraint::$migrable)) {
            $handler->add(
                Foreign::validationConstraint($constraint, Validation::CONSTRAINT_PRIORITY)
            );
        }
    }

    /**
     * Validate unsigned validation option.
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
     * Validate negative validation option.
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
     * Validate not nullable validation option.
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
     * Validate not zero validation option.
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
     * Validate unsigned validation option.
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
     * Extend multiple validation options in validator.
     *
     * @return void
     */
    public function extendValidatorOptions()
    {
        foreach ($this->validatorOptions as $validatorOption) {
            Validator::extend($validatorOption, [$this, 'validate'.Str::studly($validatorOption)]);
        }
    }
}
