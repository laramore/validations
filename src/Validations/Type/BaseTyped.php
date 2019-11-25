<?php
/**
 * Validate that the value is of a specific type.
 *
 * @author Samy Nastuzzi <samy@nastuzzi.fr>
 *
 * @copyright Copyright (c) 2019
 * @license MIT
 */

namespace Laramore\Validations\Type;

use Laramore\Facades\Rules;
use Laramore\Fields\BaseField;
use Laramore\Validations\BaseValidation;

abstract class BaseTyped extends BaseValidation
{
    /**
     * Indicate if the value is correct.
     *
     * @param  mixed $value
     * @return boolean
     */
    public function isValueValid($value): bool
    {
        return \is_null($value) && (
            $this->getField()->hasRule(Rules::nullable()) || $this->getField()->hasRule(Rules::useCurrent())
        );
    }

    /**
     * Indicate if the field is for this validation.
     *
     * @param  mixed $value
     * @return boolean
     */
    public static function isFieldValid(BaseField $field): bool
    {
        foreach ($field->getValidations() as $validation) {
            if ($validation instanceof BaseTyped) {
                return false;
            }
        }

        return true;
    }
}
