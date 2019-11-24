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
use Laramore\Facades\{
    Proxies, Validations
};
use Laramore\Observers\BaseManager;
use Laramore\Interfaces\IsALaramoreManager;
use Laramore\Exceptions\FieldValidationException;
use Laramore\Fields\BaseField;

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

    protected function addRulesForField(BaseField $field)
    {
        $handler = $this->getHandler($field->getMeta()->getModelClass());
        $propertyName = config('validations.field_property_name');
        $defaultPriority = config('validations.default_priority');

        foreach ($field->getType()->get($propertyName) as $data) {
            if (\is_string($data)) {
                [$validationClass, $priority] = [$data, $defaultPriority];
            } else {
                [$validationClass, $priority] = [$data[0], ($data[1] ?? $defaultPriority)];
            }

            if ($validationClass && $validationClass::isFieldValid($field)) {
                $handler->add(new $validationClass($field, $priority));
            }
        }
    }

    protected function addRulesForFieldRules(BaseField $field)
    {
        $handler = $this->getHandler($field->getMeta()->getModelClass());
        $propertyName = config('validations.rule_property_name');
        $priorityName = config('validations.rule_priority_name');
        $defaultPriority = config('validations.default_priority');

        foreach ($field->getRules() as $rule) {
            $validationClass = $rule->get($propertyName);
            $priority = $rule->has($priorityName) ? $rule->get($priorityName) : $defaultPriority;

            if ($validationClass && $validationClass::isFieldValid($field)) {
                $handler->add(new $validationClass($field, $priority));
            }
        }
    }

    protected function createFieldMethods(BaseField $field)
    {
        BaseField::macro('getValidations', function () {
            return Validations::getHandler($this->getMeta()->getModelClass())->get($this->getName());
        });

        BaseField::macro('getValidationErrors', function ($value) {
            return Validations::getHandler($this->getMeta()->getModelClass())->getValidationErrors($this, $value);
        });

        BaseField::macro('isValid', function ($value) {
            return $this->getValidationErrors($value)->count() === 0;
        });

        BaseField::macro('check', function ($value) {
            $errors = $this->getValidationErrors($value);

            if ($errors->count()) {
                throw new FieldValidationException($this, $errors);
            }
        });
    }

    public function createValidationsForField(BaseField $field)
    {
        $this->needsToBeUnlocked();

        if ($field->getConfig('with_validations', true) === false) {
            return;
        }

        $this->addRulesForField($field);
        $this->addRulesForFieldRules($field);
        $this->createFieldMethods($field);
    }
}
