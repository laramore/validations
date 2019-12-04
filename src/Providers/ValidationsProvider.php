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
use Laramore\Commands\{
    MigrateClear, MigrateGenerate
};
use Laramore\Validations\ValidationManager;
use Laramore\Fields\BaseField;
use Laramore\Meta;

class ValidationsProvider extends ServiceProvider implements IsALaramoreProvider
{
    /**
     * Validation manager.
     *
     * @var array
     */
    protected static $managers;

    /**
     * Define all proxy files to merge into config.
     *
     * @var array
     */
    protected static $commonProxies = [
        'check', 'getErrors', 'getValidations', 'isValid',
    ];

    /**
     * Define all rules to which we add validation classes.
     *
     * @var array
     */
    protected static $rules = [
        'accept_username', 'negative', 'not_blank', 'not_nullable', 'not_zero', 'required', 'restrict_domains', 'unsigned',
    ];

    /**
     * Define all types to which we add validation classes.
     *
     * @var array
     */
    protected static $types = [
        'boolean', 'char', 'email', 'increment', 'integer', 'password', 'primary_id', 'text', 'timestamp',
    ];

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

        foreach (static::$commonProxies as $proxy) {
            $this->mergeConfigFrom(
                __DIR__."/../../config/fields/proxies/common/$proxy.php",
                "fields.proxies.common.$proxy",
            );
        }

        foreach (static::$rules as $rule) {
            $this->mergeConfigFrom(
                __DIR__."/../../config/rules/configurations/$rule/validation_classes.php",
                "rules.configurations.$rule.validation_classes",
            );
        }

        foreach (static::$types as $type) {
            $this->mergeConfigFrom(
                __DIR__."/../../config/types/configurations/$type/validation_classes.php",
                "types.configurations.$type.validation_classes",
            );
        }

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

         $this->publishes(\array_map(function ($proxy) {
            return [
                __DIR__."/../../config/fields/proxies/common/$proxy.php" => config_path("fields/proxies/common/$proxy.php"),
            ];
         }, static::$commonProxies));

        $this->publishes(\array_map(function ($type) {
            return [
                __DIR__."/../../config/types/configurations/$type/validation_classes.php"
                => config_path("types/configurations/$type/validation_classes.php"),
            ];
        }, static::$types));

        $this->publishes(\array_map(function ($rule) {
            return [
                __DIR__."/../../config/rules/configurations/$rule/validation_classes.php"
                => config_path("rules/configurations/$rule/validation_classes.php"),
            ];
        }, static::$types));
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
        Rules::define(config('validations.property_name'), []);
        Types::define(config('validations.property_name'), []);

        Event::listen('metas.created', function ($meta) {
            Validations::createHandler($meta->getModelClass());
        });

        Event::listen('fields.locked', function ($field) {
            Validations::createValidationsForField($field);
        });

        Meta::macro('getValidationHandler', function () {
            return Validations::getHandler($this->getModelClass());
        });

        BaseField::macro('getValidations', function () {
            $handler = Validations::getHandler($this->getMeta()->getModelClass());

            return ($handler->has($this->getName())) ? $handler->get($this->getName()) : [];
        });

        BaseField::macro('getErrors', function ($value) {
            return Validations::getHandler($this->getMeta()->getModelClass())
            ->getErrors([$this->getName() => $value]);
        });

        BaseField::macro('isValid', function ($value) {
            return Validations::getHandler($this->getMeta()->getModelClass())
            ->getValidator([$this->getName() => $value])->passes();
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
