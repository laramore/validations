<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default validation manager
    |--------------------------------------------------------------------------
    |
    | This option defines the manager to handle validations.
    |
    */

    'manager' => Laramore\Validations\ValidationManager::class,

    /*
    |--------------------------------------------------------------------------
    | Name for the defined validation class in rules.
    |--------------------------------------------------------------------------
    |
    | This option defines the key name to use to resolve the validation class
    | specific to a rule.
    |
    */

    'property_name' => 'validation_classes',

    'default_priority' => Laramore\Validations\BaseValidation::MEDIUM_PRIORITY,

    'configurations' => [
        Laramore\Validations\Type\DataTime::class => [
            'allowed' => 'format',

            'date_format' => 'Y-m-d',
        ],
    ],

];
