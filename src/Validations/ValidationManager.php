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
use Illuminate\Validation\Validator as ValidationValidator;
use Laramore\Contracts\Field\Field;
use Laramore\Observers\{
    BaseManager, BaseHandler
};
use Laramore\Contracts\Manager\LaramoreManager;
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
        'forbidden', 'negative', 'not_nullable', 'not_zero', 'unsigned', 'index',
    ];

    protected $indexes = [];

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
     * @param Field $field
     * @return void
     */
    public function createValidationsForField(Field $field)
    {
        $handler = $this->getHandler($field->getMeta()->getModelClass());

        $optionsValidations = \array_map(function ($option) {
            return $option->validations;
        }, $field->getOptions());

        $validations = \array_merge(
            $field->getValidationConfig(),
            ...\array_values($optionsValidations)
        );

        foreach ($validations as $validationClass) {
            if ($validationClass::isFieldValid($field)) {
                $handler->add($validationClass::validation($field));
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
     * Validate unsigned validation option.
     *
     * @param mixed $attribute
     * @param mixed $value
     * @param mixed $parameters
     * @param mixed $validator
     *
     * @return boolean
     */
    public function validateIndex($attribute, $value, $parameters, $validator): bool
    {
        $id = spl_object_id($validator);
        $name = preg_replace('/[0-9]+/', '*', $attribute);
        $parts = explode('.', $name);
        $attname = end($parts);

        if (! isset($this->indexes[$id])) {
            $validator->after([$this, 'checkIndexes']);
        }

        if (! isset($this->indexes[$id][$name])) {
            $this->indexes[$id][$name] = [
                'model_class' => $parameters[0],
                'attributes' => []
            ];
        }

        $attributes = &$this->indexes[$id][$name]['attributes'];

        if (! isset($attributes[$attname])) {
            $attributes[$attname] = [];
        } else if (in_array($value, $attributes[$attname])) {
            return false;
        }

        $attributes[$attname][] = $value;

        return true;
    }

    public function checkIndexes(ValidationValidator $validator)
    {
        if ($validator->errors()->isNotEmpty()) {
            return false;
        }

        $id = spl_object_id($validator);
        $indexes = $this->indexes[$id];
        $return = true;

        foreach ($indexes as $name => $data) {
            $query = $data['model_class']::query();

            foreach ($data['attributes'] as $attname => $values) {
                $query->whereIn($attname, $values);
            }

            if ($query->count() > 0) {
                $validator->addFailure($name, 'index');

                $return = false;
            }
        }

        return $return;
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
