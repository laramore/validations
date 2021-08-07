<?php
/**
 * Validate that the value is a number.
 *
 * @author Samy Nastuzzi <samy@nastuzzi.fr>
 *
 * @copyright Copyright (c) 2019
 * @license MIT
 */

namespace Laramore\Validations;

use Laramore\Contracts\Field\Field;

class Numeric extends BaseValidation
{
    protected static $defaultPriority = self::TYPE_PRIORITY;

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
        return 'numeric';
    }
}
