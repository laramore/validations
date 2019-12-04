<?php
/**
 * Define a basic validation rule.
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
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Validation\Rule;
use Laramore\Fields\BaseField;
use Laramore\Observers\BaseObserver;
use Laramore\Interfaces\IsConfigurable;
use Closure;

abstract class BaseValidation extends BaseObserver implements IsConfigurable
{
    protected $field;

    public const TYPE_PRIORITY = ((self::MAX_PRIORITY + self::HIGH_PRIORITY) / 2);

    /**
     * An observer needs at least a name and a Closure.
     *
     * @param BaseField $field
     * @param integer   $priority
     */
    public function __construct(BaseField $field, int $priority=self::MEDIUM_PRIORITY)
    {
        $this->setField($field);

        parent::__construct($this->getRuleName(), Closure::fromCallable([$this, 'getValidator']), $priority);
    }

    /**
     * Return the generated rule name.
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
        return 'validations.configurations.'.static::class.(\is_null($path) ? '' : '.'.$path);
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
     * @param BaseField $field
     * @return self
     */
    public function setField(BaseField $field)
    {
        $this->needsToBeUnlocked();

        $this->field = $field;

        return $this;
    }

    /**
     * Return the field which this validation is set for.
     *
     * @return BaseField
     */
    public function getField(): BaseField
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
            $name => [$this->getValidationRule()],
        ]);
    }

    /**
     * Indicate if the field is for this validation.
     *
     * @param  BaseField $field
     * @return boolean
     */
    abstract public static function isFieldValid(BaseField $field): bool;

    /**
     * Return the valdation rule for validations.
     *
     * @return string|Rule|Closure
     */
    abstract public function getValidationRule();
}
