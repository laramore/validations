<?php
/**
 * Validate that the value is a datetime.
 *
 * @author Samy Nastuzzi <samy@nastuzzi.fr>
 *
 * @copyright Copyright (c) 2019
 * @license MIT
 */

namespace Laramore\Validations;

use Laramore\Fields\BaseField;

class DataTime extends BaseValidation
{
    /**
     * Indicate if the field is for this validation.
     *
     * @param  BaseField $field
     * @return boolean
     */
    public static function isFieldValid(BaseField $field): bool
    {
        return true;
    }

    /**
     * Return the valdation option for validations.
     *
     * @param array<string,mixed> $data
     * @return string
     */
    public function getValidationRule(array $data)
    {
        switch ($this->getConfig('allowed')) {
            case 'timestamp':
                return 'date_format:'.$this->getConfig('date_format', 'Y-m-d');

            case 'string':
            default:
                return 'date';
        }
    }
}
