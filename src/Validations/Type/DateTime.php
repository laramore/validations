<?php
/**
 * Validate that the value is a datetime.
 *
 * @author Samy Nastuzzi <samy@nastuzzi.fr>
 *
 * @copyright Copyright (c) 2019
 * @license MIT
 */

namespace Laramore\Validations\Type;

use Laramore\Fields\BaseField;
use Carbon\Carbon;

class DataTime extends BaseTyped
{
    /**
     * Indicate if the value is correct.
     *
     * @param  mixed $value
     * @return boolean
     */
    public function isValueValid($value): bool
    {
        if (parent::isValueValid($value)) {
            return true;
        }

        switch ($this->getConfig('allowed')) {
            case 'timestamp':
                return (\is_integer($value) && $value > 0);

            case 'format':
                try {
                    Carbon::createFromFormat($this->getConfig('date_format', 'Y-m-d'), $value);
                } catch (\InvalidArgumentException $e) {
                    return false;
                }
                return true;

            case 'any':
                if (\is_integer($value) && $value > 0) {
                    return true;
                }

            case 'string':
            default:
                return (integer) strtotime($value) > 0;
        }
    }

    /**
     * Return the error message.
     *
     * @return array|string
     */
    public function getMessage()
    {
        return 'The field must be a datetime.';
    }
}
