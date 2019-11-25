<?php
/**
 * Validate that the value is an array.
 *
 * @author Samy Nastuzzi <samy@nastuzzi.fr>
 *
 * @copyright Copyright (c) 2019
 * @license MIT
 */

namespace Laramore\Validations\Type;

use Laramore\Fields\BaseField;

class array extends BaseTyped
{
    /**
     * Indicate if the value is correct.
     *
     * @param  mixed $value
     * @return boolean
     */
    public function isValueValid($value): bool
    {
        return parent::isValueValid($value) || \is_array($value);
    }

    /**
     * Return the error message.
     *
     * @return array|string
     */
    public function getMessage()
    {
        return 'The field must be an array.';
    }
}
