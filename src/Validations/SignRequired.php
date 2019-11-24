<?php
/**
 * Validate that the value is unsigned.
 *
 * @author Samy Nastuzzi <samy@nastuzzi.fr>
 *
 * @copyright Copyright (c) 2019
 * @license MIT
 */

namespace Laramore\Validations;

use Laramore\Facades\Rules;
use Laramore\Fields\BaseField;

class SignRequired extends Validation
{
    /**
     * Indicate if the value is correct.
     *
     * @param  mixed $value
     * @return boolean
     */
    public function isValueValid($value): bool
    {
        return ($value >= 0 && $this->getField()->hasRule(Rules::negative()))
            || ($value <= 0 && $this->getField()->hasRule(Rules::positive()));
    }

    /**
     * Return the error message.
     *
     * @return array|string
     */
    public function getMessage()
    {
        return $this->getField()->hasRule(Rules::negative()) ? $this->getNegativeMessage() : $this->getPositiveMessage();
    }

    /**
     * Return the error message for positive values.
     *
     * @return array|string
     */
    public function getPositiveMessage()
    {
        return 'This field must be positive';
    }

    /**
     * Return the error message for negative values.
     *
     * @return array|string
     */
    public function getNegativeMessage()
    {
        return 'This field must be negative';
    }

    /**
     * Indicate if the field is for this validation.
     *
     * @param  mixed $value
     * @return boolean
     */
    public static function isFieldValid(BaseField $field): bool
    {
        return true;
    }
}
