<?php
/**
 * Define the length validation rule.
 *
 * @author Samy Nastuzzi <samy@nastuzzi.fr>
 *
 * @copyright Copyright (c) 2019
 * @license MIT
 */

namespace Laramore\Validations;

use Laramore\Fields\BaseField;

class Length extends BaseValidation
{
    public function isValueValid($value): bool
    {
        $length = strlen($value);
        $minLength = $this->getField()->getProperty('minLength', false);
        $maxLength = $this->getField()->getProperty('maxLength', false);

        return (\is_null($minLength) || $length >= $minLength) &&
            (\is_null($maxLength) || $length <= $maxLength);
    }

    public function getMessage()
    {
        $minLength = $this->getField()->getProperty('minLength', false);
        $maxLength = $this->getField()->getProperty('maxLength', false);
        $messages = [];

        if (!\is_null($minLength)) {
            $messages[] = "The length must be at least of $minLength.";
        }

        if (!\is_null($maxLength)) {
            $messages[] = "The length must be at most of $maxLength.";
        }

        return $messages;
    }

    /**
     * Indicate if the field is for this validation.
     *
     * @param  mixed $value
     * @return boolean
     */
    public static function isFieldValid(BaseField $field): bool
    {
        return $field->hasProperty('minLength') || $field->HasProperty('maxLength');
    }
}
