<?php
/**
 * Prepare the package.
 *
 * @author Samy Nastuzzi <samy@nastuzzi.fr>
 *
 * @copyright Copyright (c) 2019
 * @license MIT
 */

namespace Laramore\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Laramore\Facades\{
	Validation, Rule, Type
};
use Laramore\Interfaces\{
    IsALaramoreManager, IsALaramoreProvider
};
use Laramore\Traits\Provider\MergesConfig;
use Laramore\Fields\BaseField;
use Laramore\Meta;

class ValidationProvider extends ServiceProvider implements IsALaramoreProvider
{
    use MergesConfig;

    /**
     * Before booting, create our definition for migrations.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/field/configurations.php',
            'field.configurations',
        );
        $this->mergeConfigFrom(
            __DIR__.'/../../config/field/constraints.php',
            'field.constraints',
        );
        $this->mergeConfigFrom(
            __DIR__.'/../../config/field/proxies.php',
            'field.proxies',
        );

        $this->mergeConfigFrom(
            __DIR__.'/../../config/rule.php', 'rule',
        );

        $this->mergeConfigFrom(
            __DIR__.'/../../config/type.php', 'type',
        );

        $this->mergeConfigFrom(
            __DIR__.'/../../config/validation.php', 'validation',
        );

        $this->app->singleton('Validation', function() {
            return static::generateManager();
        });

        $this->app->booting([$this, 'bootingCallback']);
        $this->app->booted([$this, 'bootedCallback']);
    }

    /**
     * During booting, add our custom methods.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../config/validation.php' => config_path('validation.php'),
        ]);
    }

    /**
     * Return the default values for the manager of this provider.
     *
     * @return array
     */
    public static function getDefaults(): array
    {
        return config('validation.configurations', []);
    }

    /**
     * Generate the corresponded manager.
     *
     * @return IsALaramoreManager
     */
    public static function generateManager(): IsALaramoreManager
    {
        $class = config('validation.manager');

        return new $class(static::getDefaults());
    }

    /**
     * Before booting, add a new validation definition and fix increment default value.
     * If the manager is locked during booting we need to reset it.
     *
     * @return void
     */
    public function bootingCallback()
    {
        Rule::define(config('validation.property_name'), []);
        Type::define(config('validation.property_name'), []);

        Event::listen('metas.created', function ($meta) {
            Validation::createHandler($meta->getModelClass());
        });

        Event::listen('fields.locked', function ($field) {
            Validation::createValidationsForField($field);
        });

        Event::listen('constraints.locked', function ($constraint) {
            Validation::createValidationsForConstraint($constraint);
        });

        Meta::macro('getValidationHandler', function () {
            return Validation::getHandler($this->getModelClass());
        });

        BaseField::macro('getValidations', function () {
            $handler = Validation::getHandler($this->getMeta()->getModelClass());

            return ($handler->has($this->getName())) ? $handler->get($this->getName()) : [];
        });

        BaseField::macro('getErrors', function ($value) {
            return Validation::getHandler($this->getMeta()->getModelClass())
                ->getErrors([$this->getName() => $value]);
        });

        BaseField::macro('isValid', function ($value) {
            return Validation::getHandler($this->getMeta()->getModelClass())
                ->getValidator([$this->getName() => $value])->passes();
        });

        Validation::extendValidatorRules();
    }

    /**
     * Lock all managers after booting.
     *
     * @return void
     */
    public function bootedCallback()
    {
        Validation::lock();
    }
}
