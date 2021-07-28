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

use Laramore\Contracts\Field\Field;
use Illuminate\Contracts\Validation\Rule;
use Closure;

class Validation extends BaseValidation
{
    protected $rule;

    /**
     * An observer needs at least a name and a Closure.
     *
     * @param string|Rule|Closure $rule
     * @param Field               $field
     * @param integer             $priority
     */
    public function __construct($rule, Field $field, int $priority=self::MEDIUM_PRIORITY)
    {
        $this->rule = $rule;

        parent::__construct($field, $priority);
    }

    /**
     * Return the generated option name.
     *
     * @return string
     */
    public function getRuleName(): string
    {
        return \is_string($this->rule) ? $this->rule : parent::getRuleName();
    }

    /**
     * Define the rule.
     *
     * @param string|Rule|Closure $rule
     * @return self
     */
    public function setRule($rule)
    {
        $this->needsToBeUnlocked();

        $this->rule = $rule;

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
     * @param  Field $field
     * @return boolean
     */
    public static function isFieldValid(Field $field): bool
    {
        return true;
    }
}
