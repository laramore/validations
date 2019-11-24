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
use Illuminate\Support\Facades\{
	DB, Event
};
use Illuminate\Database\Validations\Migrator;
use Laramore\Facades\{
	Validations, Rules, Types
};
use Laramore\Interfaces\{
    IsALaramoreManager, IsALaramoreProvider
};
use Laramore\Traits\Provider\MergesConfig;
use Laramore\Commands\{
    MigrateClear, MigrateGenerate
};
use Laramore\Validations\ValidationManager;
use Laramore\Meta;

class ValidationsProvider extends ServiceProvider implements IsALaramoreProvider
{
    use MergesConfig;

    /**
     * Validation manager.
     *
     * @var array
     */
    protected static $managers;

    /**
     * Before booting, create our definition for migrations.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/validations.php', 'validations',
        );

        $this->mergeConfigFrom(
            __DIR__.'/../../config/rules.php', 'rules',
        );

        $this->mergeConfigFrom(
            __DIR__.'/../../config/types.php', 'types',
        );

        $this->mergeConfigFrom(
            __DIR__.'/../../config/fields.php', 'fields',
        );

        $this->app->singleton('Validations', function() {
            return static::getManager();
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
            __DIR__.'/../../config/validations.php' => config_path('validations.php'),
        ]);
    }

    /**
     * Return the default values for the manager of this provider.
     *
     * @return array
     */
    public static function getDefaults(): array
    {
        return config('validations.configurations', []);
    }

    /**
     * Generate the corresponded manager.
     *
     * @param  string $key
     * @return IsALaramoreManager
     */
    public static function generateManager(string $key): IsALaramoreManager
    {
        $class = config('validations.manager');

        return static::$managers[$key] = new $class(static::getDefaults());
    }

    /**
     * Return the generated manager for this provider.
     *
     * @return IsALaramoreManager
     */
    public static function getManager(): IsALaramoreManager
    {
        $appHash = \spl_object_hash(app());

        if (!isset(static::$managers[$appHash])) {
            return static::generateManager($appHash);
        }

        return static::$managers[$appHash];
    }

    /**
     * Before booting, add a new validation definition and fix increment default value.
     * If the manager is locked during booting we need to reset it.
     *
     * @return void
     */
    public function bootingCallback()
    {
        Rules::define(config('validations.rule_property_name'), false);
        Types::define(config('validations.field_property_name'), []);

        Event::listen('metas.created', function ($meta) {
            Validations::createHandler($meta->getModelClass());
        });

        Event::listen('fields.locked', function ($field) {
            Validations::createValidationsForField($field);
        });

        Meta::macro('getValidationHandler', function () {
            return Validations::getHandler($this->getModelClass());
        });
    }

    /**
     * Lock all managers after booting.
     *
     * @return void
     */
    public function bootedCallback()
    {
        static::getManager()->lock();
    }
}
