<?php
/**
 * Validate an object list.
 *
 * @author Samy Nastuzzi <samy@nastuzzi.fr>
 *
 * @copyright Copyright (c) 2021
 * @license MIT
 */

namespace Laramore\Validations;

use Illuminate\Support\Arr;
use Laramore\Contracts\Field\Field;
use Laramore\Contracts\Field\ManyRelationField;
use Laramore\Fields\Json;
use Laramore\Elements\ValueCollection;

class ObjectList extends BaseValidation
{
    /**
     * Indicate if the field is for this validation.
     *
     * @param  Field $field
     * @return boolean
     */
    public static function isFieldValid(Field $field): bool
    {
        return ($field instanceof Json && $field->collectionType === ValueCollection::LIST_COLLETION)
            || ($field instanceof ManyRelationField);
    }

    /**
     * Return the valdation option for validations.
     *
     * @return callback
     */
    public function getRule()
    {
        return function ($name, $value): bool {
            if (! (is_array($value) && (empty($value) || ! Arr::isAssoc($value)))) {
                return false;
            }

            foreach ($value as $subValue) {
                if (! (is_array($subValue) && (empty($subValue) || Arr::isAssoc($subValue)))) {
                    return false;
                }
            }

            return true;
        };
    }
}
