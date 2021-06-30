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
use Laramore\Facades\Validation;
use Laramore\Contracts\Manager\LaramoreManager;
use Laramore\Elements\OptionManager;
use Laramore\Traits\Provider\MergesConfig;
use Laramore\Fields\BaseField;
use Laramore\Eloquent\Meta;
use Laramore\Mixins\ValidationField;
use Laramore\Validations\ValidationManager;

class ValidationProvider extends ServiceProvider
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
            __DIR__.'/../../config/constraint/validations.php', 'constraint.validations',
        );

        $this->mergeConfigFrom(
            __DIR__.'/../../config/option/validations.php', 'option.validations',
        );

        $this->mergeConfigFrom(
            __DIR__.'/../../config/field/validations.php', 'field.validations',
        );

        $this->app->singleton('validation', function() {
            return static::generateManager();
        });

        $this->app->booting([$this, 'bootingCallback']);
        $this->app->booted([$this, 'bootedCallback']);
    }

    /**
     * Generate the corresponded manager.
     *
     * @return LaramoreManager
     */
    public static function generateManager(): LaramoreManager
    {
        return new ValidationManager([]);
    }

    /**
     * Before booting, add a new validation definition and fix increment default value.
     * If the manager is locked during booting we need to reset it.
     *
     * @return void
     */
    public function bootingCallback()
    {
        BaseField::$configKeys[] = 'validations';
        OptionManager::$configKeys[] = 'validations';

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
            /** @var \Laramore\Contracts\Eloquent\LaramoreMeta $this */
            return Validation::getHandler($this->getModelClass());
        });

        BaseField::mixin(new ValidationField);
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
