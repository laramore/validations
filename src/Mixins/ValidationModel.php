<?php
/**
 * Validation mixin for models.
 *
 * @author Samy Nastuzzi <samy@nastuzzi.fr>
 *
 * @copyright Copyright (c) 2021
 * @license MIT
 */

namespace Laramore\Mixins;

class ValidationModel
{
    /**
     * Return field validations.
     *
     * @return string|null
     */
    public function getValidations()
    {
        return function (string $fieldName=null) {
            /** @var \Laramore\Eloquent\Model $this */
            return static::getMeta()->getValidationHandler()->all($fieldName);
        };
    }

    /**
     * Return validation rules.
     *
     * @return array
     */
    public function getRules()
    {
        return function (array $keys = []) {
            /** @var \Laramore\Eloquent\Model $this */
            return static::getMeta()->getValidationHandler()->getRules($keys);
        };
    }

    /**
     * Get errors based on field value.
     *
     * @return mixed
     */
    public function getErrors()
    {
        return function (array $values) {
            /** @var \Laramore\Eloquent\Model $this */
            return static::getMeta()->getValidationHandler()->getErrors($values);
        };
    }

    /**
     * Check errors for field value.
     *
     * @return mixed
     */
    public function isValid()
    {
        return function (array $values) {
            /** @var \Laramore\Eloquent\Model $this */
            return static::getMeta()->getValidationHandler()->getValidator($values)->passes();
        };
    }
}
