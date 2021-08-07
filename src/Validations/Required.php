<?php
/**
 * Validate that the value is .
 *
 * @author Samy Nastuzzi <samy@nastuzzi.fr>
 *
 * @copyright Copyright (c) 2019
 * @license MIT
 */

namespace Laramore\Validations;

use Laramore\Facades\Option;
use Laramore\Contracts\Field\{
    Field, ComposedField, AttributeField
};

class Required extends BaseValidation
{
    protected static $defaultPriority = self::MAX_PRIORITY;

    /**
     * Indicate if the field is for this validation.
     *
     * @param  Field $field
     * @return boolean
     */
    public static function isFieldValid(Field $field): bool
    {
        return !$field->hasOption(Option::nullable());
    }

    /**
     * Return the valdation option for validations.
     *
     * @return string
     */
    public function getRule()
    {
        $field = $this->getField();

        if ($field->getOwner() !== $field->getMeta()) {
            return "required_without:{$field->getOwner()->getName()}";
        }

        if ($field instanceof ComposedField) {
            return 'required_without_all:'.\implode(',', \array_map(function (AttributeField $subField) {
                return $subField->getName();
            }, $field->getFields(AttributeField::class)));
        }

        return 'required';
    }
}
