<?php
/**
 * Validation mixin for fields.
 *
 * @author Samy Nastuzzi <samy@nastuzzi.fr>
 *
 * @copyright Copyright (c) 2020
 * @license MIT
 */

namespace Laramore\Mixins;

use Illuminate\Support\Arr;
use Laramore\Facades\Validation;


class ValidationField
{
    /**
     * Return validation field config.
     *
     * @return mixed
     */
    public function getValidationConfig()
    {
        return function (string $path=null, $default=null) {
            /** @var \Laramore\Contracts\Eloquent\Field $this */
            if (\is_null($path)) {
                return $this->config['validations'];
            }

            return Arr::get($this->config['validations'], $path, $default);
        };
    }

    /**
     * Return field validations.
     *
     * @return string|null
     */
    public function getValidations()
    {
        return function () {
            /** @var \Laramore\Contracts\Eloquent\Field $this */
            $handler = Validation::getHandler($this->getMeta()->getModelClass());

            return ($handler->has($this->getName())) ? $handler->get($this->getName()) : [];
        };
    }

    /**
     * Return validation rules.
     *
     * @return array
     */
    public function getRules()
    {
        return function (array $values=[]) {
            /** @var \Laramore\Contracts\Eloquent\Field $this */
            return \array_map(function ($validation) use ($values) {
                return $validation->getValidationRule($values);
            }, $this->getValidations());
        };
    }

    /**
     * Get errors based on field value.
     *
     * @return mixed
     */
    public function getErrors()
    {
        return function ($value) {
            /** @var \Laramore\Contracts\Eloquent\Field $this */
            return Validation::getHandler($this->getMeta()->getModelClass())
                ->getErrors([$this->getName() => $value], true);
        };
    }

    /**
     * Check errors for field value.
     *
     * @return mixed
     */
    public function isValid()
    {
        return function ($value) {
            /** @var \Laramore\Contracts\Eloquent\Field $this */
            return Validation::getHandler($this->getMeta()->getModelClass())
                ->getValidator([$this->getName() => $value], true)->passes();
        };
    }
}
