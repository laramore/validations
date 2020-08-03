<?php
/**
 * Define a basic validation option.
 *
 * @author Samy Nastuzzi <samy@nastuzzi.fr>
 *
 * @copyright Copyright (c) 2019
 * @license MIT
 */

namespace Laramore\Validations;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Validator as ValidatorReturn;
use Illuminate\Contracts\Validation\Rule;
use Laramore\Observers\BaseObserver;
use Laramore\Contracts\{
    Configured, Field\Field
};
use Closure;

abstract class BaseValidation extends BaseObserver implements Configured
{
    protected $field;

    public const TYPE_PRIORITY = ((self::MAX_PRIORITY + self::HIGH_PRIORITY) / 2);
    public const CONSTRAINT_PRIORITY = ((self::MIN_PRIORITY + self::LOW_PRIORITY) / 2);

    /**
     * An observer needs at least a name and a Closure.
     *
     * @param Field   $field
     * @param integer $priority
     */
    protected function __construct(Field $field, int $priority=self::MEDIUM_PRIORITY)
    {
        $this->setField($field);

        parent::__construct($this->getRuleName(), Closure::fromCallable([$this, 'getValidator']), $priority);
    }

    /**
     * Generate a validation.
     *
     * @param Field   $field
     * @param integer $priority
     * @return static
     */
    public static function validation(Field $field, int $priority=self::MEDIUM_PRIORITY)
    {
        return new static($field, $priority);
    }

    /**
     * Return the generated option name.
     *
     * @return string
     */
    public function getRuleName(): string
    {
        return Str::snake((new \ReflectionClass(static::class))->getShortName());
    }

    /**
     * Return the configuration path for this field.
     *
     * @param string $path
     * @return mixed
     */
    public function getConfigPath(string $path=null)
    {
        $name = Str::snake((new \ReflectionClass(static::class))->getShortName());

        return 'validation.configurations.'.$name.(\is_null($path) ? '' : '.'.$path);
    }

    /**
     * Return the configuration for this field.
     *
     * @param string $path
     * @param mixed  $default
     * @return mixed
     */
    public function getConfig(string $path=null, $default=null)
    {
        return config($this->getConfigPath($path), $default);
    }

    /**
     * Define the proxy field.
     *
     * @param Field $field
     * @return self
     */
    public function setField(Field $field)
    {
        $this->needsToBeUnlocked();

        $this->field = $field;

        return $this;
    }

    /**
     * Return the field which this validation is set for.
     *
     * @return Field
     */
    public function getField(): Field
    {
        return $this->field;
    }

    /**
     * Check if the value is correct.
     *
     * @param  mixed $value
     * @return ValidatorReturn
     */
    public function getValidator($value): ValidatorReturn
    {
        $name = $this->getField()->getName();

        return Validator::make([
            $name => $value,
        ], [
            $name => [$this->getValidationRule([])],
        ]);
    }

    /**
     * Retrieve the appropriate, localized validation message
     * or fall back to the given default.
     *
     * @param string $key
     * @param string $default
     * @return string
     **/
    public function getLocalizedErrorMessage(string $key, string $default): string
    {
        return trans("validation.$key") === "validation.$key" ? $default : trans("validation.$key");
    }

    /**
     * Indicate if the field is for this validation.
     *
     * @param  Field $field
     * @return boolean
     */
    abstract public static function isFieldValid(Field $field): bool;

    /**
     * Return the valdation option for validations.
     *
     * @param array<string,mixed> $data
     * @return string|Rule|Closure|callback
     */
    abstract public function getValidationRule(array $data);
}
