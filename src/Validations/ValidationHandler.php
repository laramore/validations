<?php
/**
 * Handle all observers for a specific model.
 *
 * @author Samy Nastuzzi <samy@nastuzzi.fr>
 *
 * @copyright Copyright (c) 2019
 * @license MIT
 */

namespace Laramore\Validations;

use Illuminate\Support\{
    Arr, Facades\Validator
};
use Illuminate\Validation\Validator as ValidatorResult;
use Laramore\Contracts\Field\Field;
use Laramore\Observers\{
    BaseObserver, BaseHandler
};

class ValidationHandler extends BaseHandler
{
    /**
     * The observable class.
     *
     * @var string
     */
    protected $observerClass = BaseValidation::class;

    /**
     * Add an observer to a list of observers.
     *
     * @param BaseObserver $observer
     * @param array        $observers
     * @return self
     */
    protected function push(BaseObserver $observer, array &$observers)
    {
        /** @var Field $observer */
        if (!isset($observers[$name = $observer->getField()->name])) {
            $observers[$name] = [];
        }

        /** @var BaseObserver $observer */
        return parent::push($observer, $observers[$name]);
    }

    /**
     * Return if an observe exists with the given name.
     *
     * @param  string $fieldName
     * @param  string $name
     * @return boolean
     */
    public function has(string $fieldName, string $name=null): bool
    {
        if (is_null($name)) {
            return isset($this->observers[$fieldName]);
        }

        foreach (($this->observers[$fieldName] ?? []) as $observer) {
            if ($observer->getName() === $name) {
                return true;
            }
        }

        return false;
    }

    /**
     * Return the first observer with the given name.
     *
     * @param  string $fieldName
     * @param  string $name
     * @return mixed
     */
    public function get(string $fieldName, string $name=null)
    {
        if (is_null($name)) {
            return $this->observers[$fieldName];
        }

        foreach ($this->observers[$fieldName] as $observer) {
            if ($observer->getName() === $name) {
                return $observer;
            }
        }

        throw new \Exception('The observer does not exist');
    }

    /**
     * Return the list of the handled observers.
     *
     * @param  string $fieldName
     * @return array
     */
    public function all(string $fieldName=null): array
    {
        if (\is_null($fieldName)) {
            return $this->observers;
        }

        return $this->get($fieldName);
    }

    /**
     * Return all rules for a specfic set of field names.
     *
     * @param  array   $values        Associative fieldname => value or only fieldnames.
     * @param  boolean $onlyForValues
     * @return array
     */
    public function getRules(array $values=[], bool $onlyForValues=false): array
    {
        $fieldValidations = $this->all();

        if ($onlyForValues) {
            $fieldValidations = \array_intersect_key($fieldValidations, $values);

            if (!Arr::isAssoc($values)) {
                $values = \array_fill_keys($values, null);
            }
        }

        return \array_map(function (array $validations) use ($values) {
            return \array_map(function (BaseValidation $validation) use ($values) {
                return $validation->getValidationRule($values);
            }, $validations);
        }, $fieldValidations);
    }

    /**
     * Return the validator for an array of values.
     *
     * @param  array   $values
     * @param  boolean $onlyForValues
     * @return ValidatorResult
     */
    public function getValidator(array $values, bool $onlyForValues=false): ValidatorResult
    {
        return Validator::make($values, $this->getRules($values, $onlyForValues));
    }

    /**
     * Return all errors for an array of values.
     *
     * @param  array   $values
     * @param  boolean $onlyForValues
     * @return array
     */
    public function getErrors(array $values, bool $onlyForValues=false): array
    {
        return $this->getValidator($values, $onlyForValues)->errors()->all();
    }

    /**
     * Need to lock every observer.
     *
     * @return void
     */
    protected function locking()
    {
        foreach ($this->observers as $observers) {
            foreach ($observers as $observer) {
                if (!$observer->isLocked()) {
                    $observer->lock();
                }
            }
        }
    }
}
