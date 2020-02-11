<?php
/**
 * Define a validation with a specific option.
 *
 * @author Samy Nastuzzi <samy@nastuzzi.fr>
 *
 * @copyright Copyright (c) 2019
 * @license MIT
 */

namespace Laramore\Validations;

use Laramore\Fields\BaseField;
use Illuminate\Contracts\Validation\Rule;
use Closure;

class Validation extends BaseValidation
{
    public const TYPE_PRIORITY = ((self::MAX_PRIORITY + self::HIGH_PRIORITY) / 2);

    protected $option;

    /**
     * An observer needs at least a name and a Closure.
     *
     * @param string|Rule|Closure $option
     * @param BaseField           $field
     * @param integer             $priority
     */
    public function __construct($option, BaseField $field, int $priority=self::MEDIUM_PRIORITY)
    {
        $this->option = $option;

        parent::__construct($field, $priority);
    }

    /**
     * Return the generated option name.
     *
     * @return string
     */
    public function getRuleName(): string
    {
        return \is_string($this->option) ? $this->option : parent::getRuleName();
    }

    /**
     * Define the option.
     *
     * @param string|Rule|Closure $option
     * @return self
     */
    public function setRule($option)
    {
        $this->needsToBeUnlocked();

        $this->option = $option;

        return $this;
    }

    /**
     * Return the option of this validation.
     *
     * @return string|Rule|Closure|callback
     */
    public function getRule()
    {
        return $this->option;
    }

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
     * @return string|Rule|Closure|callback
     */
    public function getValidationRule(array $data)
    {
        return $this->getRule();
    }
}
