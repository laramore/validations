<?php

namespace Laramore\Validations;

return [

    /*
    |--------------------------------------------------------------------------
    | Default validation manager
    |--------------------------------------------------------------------------
    |
    | This option defines the manager to handle validations.
    |
    */

    'manager' => ValidationManager::class,

    /*
    |--------------------------------------------------------------------------
    | Name for the defined validation class in options.
    |--------------------------------------------------------------------------
    |
    | This option defines the key name to use to resolve the validation class
    | specific to a option.
    |
    */

    'property_name' => 'validations',

    'default_priority' => Validation::MEDIUM_PRIORITY,

    'configurations' => [
        'date_time' => [
            'allowed' => 'format',

            'date_format' => 'Y-m-d',
        ],
    ],

];
