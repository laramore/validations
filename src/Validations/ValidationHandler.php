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

use Illuminate\Database\Eloquent\Model;
use Laramore\Validations\ValidationErrorBag;
use Laramore\Fields\BaseField;
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
        if (!isset($observers[$name = $observer->getField()->name])) {
            $observers[$name] = [];
        }

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

        foreach (($this->observers[$fieldName] ?? []) as $key => $observer) {
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

        foreach ($this->observers[$fieldName] as $key => $observer) {
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

    public function getErrors(array $values, bool $withAllErrors=false): ValidationErrorBag
    {
        $bag = new ValidationErrorBag;
        $priority = BaseValidation::MAX_PRIORITY;
        $validations = [];

        // Get all validations for all values keys.
        // ex: $values = ['password' => 'password', 'name' => '1'];
        // $validations = [validations of the field 'password' + validations of the field 'name'];
        //
        // The push method order validations by top priorities.
        foreach (\array_intersect_key($this->all(), $values) as $fieldValidations) {
            foreach ($fieldValidations as $validation) {
                parent::push($validation, $validations);
            }
        }

        foreach ($validations as $validation) {
            // When checking all validations, if a validation fails, we don't end up directly.
            // The validations are grouped by priority. If a validation failed, we can fail after
            // testing all validations with the same priorirty.
            if (!$withAllErrors && $priority !== $validation->getPriority()) {
                if ($bag->count()) {
                    break;
                }

                $priority = $validation->getPriority();
            }

            $name = $validation->getField()->getName();
            $value = $values[$name];

            if (!$validation->isValueValid($value)) {
                $bagError = new ValidationErrorBag;
                $bagError->add($validation->getName(), $validation->getMessage());

                $bag->add($name, $bagError);
            }
        }

        return $bag;
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
