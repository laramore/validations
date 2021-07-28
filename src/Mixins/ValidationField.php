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
use Laramore\Contracts\Field\ManyRelationField;
use Laramore\Contracts\Field\RelationField;
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
        return function () {
            /** @var \Laramore\Contracts\Eloquent\Field $this */

            $name = $this->getName();
            $rules = [
                $name => \array_map(function ($validation) {
                    return $validation->getRule();
                }, $this->getValidations()),
            ];

            if ($this instanceof RelationField) {
                $modelClass = $this->isRelationHeadOn() ? $this->getSourceModel() : $this->getTargetModel();
                $subRules = Validation::getHandler($modelClass)->getRules();

                if ($this instanceof ManyRelationField) {
                    $toFormatRules = $subRules;
                    $subRules = [];

                    foreach ($toFormatRules as $subName => $rule) {
                        $subRules['*.'.$subName] = $rule;
                    }
                }

                foreach ($subRules as $subName => $rule) {
                    $rules[$name.'.'.$subName] = $rule;
                }
            }

            return $rules;
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
