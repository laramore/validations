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

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Laramore\Fields\BaseField;
use Laramore\Traits\HasProperties;
use Laramore\Observers\BaseObserver;
use Laramore\Interfaces\IsConfigurable;
use Closure;

abstract class BaseValidation extends BaseObserver implements IsConfigurable
{
    use HasProperties;

    protected $field;

    /**
     * An observer needs at least a name and a Closure.
     *
     * @param BaseField $field
     * @param integer   $priority
     */
    public function __construct(BaseField $field, int $priority=self::MEDIUM_PRIORITY)
    {
        $this->setField($field);

        parent::__construct(static::getStaticName(), null, $priority);
    }

    public static function getStaticName(): string
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

    public function getField()
    {
        return $this->field;
    }

    /**
     * Return the Closure function.
     *
     * @return Closure
     */
    public function getCallback(): Closure
    {
        return Closure::fromCallable([$this, 'isValueValid']);
    }

    /**
     * Indicate if the value is correct.
     *
     * @param  mixed $value
     * @return boolean
     */
    abstract public function isValueValid($value): bool;

    /**
     * Indicate if the field is for this validation.
     *
     * @param  mixed $value
     * @return boolean
     */
    abstract public static function isFieldValid(BaseField $field): bool;

    /**
     * Return the error message.
     *
     * @return array|string
     */
    abstract public function getMessage();
}
