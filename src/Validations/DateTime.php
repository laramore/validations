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

use Laramore\Contracts\Field\Field;

class DateTime extends BaseValidation
{
    /**
     * Indicate if the field is for this validation.
     *
     * @param  Field $field
     * @return boolean
     */
    public static function isFieldValid(Field $field): bool
    {
        return true;
    }

    /**
     * Return the valdation option for validations.
     *
     * @return string
     */
    public function getRule()
    {
        return 'date_format:Y-m-d';
    }
}
