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

use Illuminate\Container\Container;
use Illuminate\Support\ServiceProvider;
use Laramore\Facades\{
	Validation, Option, Type, Meta as MetaManager
};
use Laramore\Contracts\{
    Provider\LaramoreProvider, Manager\LaramoreManager
};
use Laramore\Traits\Provider\MergesConfig;
use Laramore\Fields\BaseField;
use Laramore\Eloquent\Meta;

class ValidationProvider extends ServiceProvider implements LaramoreProvider
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
            __DIR__.'/../../config/field/constraint.php',
            'field.constraint',
        );
        $this->mergeConfigFrom(
            __DIR__.'/../../config/field/proxy.php',
            'field.proxy',
        );

        $this->mergeConfigFrom(
            __DIR__.'/../../config/option.php', 'option',
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

        $this->setProxies();
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
     * @return LaramoreManager
     */
    public static function generateManager(): LaramoreManager
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
        $propertyName = config('validation.property_name');

        Option::define($propertyName, []);
        Type::define($propertyName, []);

        // TODO: Factory
        Type::define('factory_name');
        BaseField::macro('generate', function () {
            $name = $this->getType()->getFactoryName();

            return app('Faker\Generator')->format($name);
        });

        $this->setMacros();

        Validation::extendValidatorOptions();
    }

    /**
     * Add all required macros for validations.
     *
     * @return void
     */
    protected function setMacros()
    {
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
    }

    /**
     * Define all proxies
     *
     * @return void
     */
    public function setProxies()
    {
        // $config = Container::getInstance()->config->get('validation.proxy');
        // $class = $config['class'];

        // foreach (MetaManager::getMetas() as $meta) {
        //     $proxy = $meta->getProxyHandler();

        //     foreach ($config['configurations'] as )
        // }
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
