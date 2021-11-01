<?php
/**
 * Validation mixin for metas.
 *
 * @author Samy Nastuzzi <samy@nastuzzi.fr>
 *
 * @copyright Copyright (c) 2021
 * @license MIT
 */

namespace Laramore\Mixins;

use Laramore\Facades\Validation;

class ValidationMeta
{
    /**
     * Return validation handler.
     *
     * @return string|null
     */
    public function getValidationHandler()
    {
        return function () {
            /** @var \Laramore\Contracts\Eloquent\LaramoreMeta $this */
            return Validation::getHandler($this->getModelClass());
        };
    }
}
