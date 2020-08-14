<?php

namespace Laramore\Validations;

return [

    /*
    |--------------------------------------------------------------------------
    | Validation classes for types
    |--------------------------------------------------------------------------
    |
    | This option defines the validation classes that are added for each types.
    |
    */

    'configurations' => [
        'boolean' => [
            'validations' => [
                Boolean::class => [
                    'priority' => Validation::TYPE_PRIORITY,
                ],
            ],
        ],
        'char' => [
            'validations' => [
                Text::class => [
                    'priority' => Validation::TYPE_PRIORITY,
                ],
                MinLength::class => [],
                MaxLength::class => [],
            ],
        ],
        'email' => [
            'validations' => [
                MinLength::class => [],
                MaxLength::class => [],
                Pattern::class => [],
            ],
        ],
        'integer' => [
            'validations' => [
                Numeric::class => [
                    'priority' => Validation::TYPE_PRIORITY,
                ],
            ],
        ],
        'timestamp' => [
            'validations' => [
                DateTime::class => [
                    'priority' => Validation::TYPE_PRIORITY,
                ],
            ],
        ],
        'password' => [
            'validations' => [
                Pattern::class => [],
            ],
        ],
    ],

];
