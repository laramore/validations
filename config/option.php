<?php

namespace Laramore\Validations;

return [

    /*
    |--------------------------------------------------------------------------
    | Default options
    |--------------------------------------------------------------------------
    |
    | This option defines the default options used in fields.
    |
    */

    'configurations' => [
        'required' => [
            'validations' => [
                Required::class => [
                    'priority' => Validation::MAX_PRIORITY,
                ],
            ],
        ],
        'not_nullable' => [
            'validations' => [
                NotNullable::class => [
                    'priority' => Validation::MAX_PRIORITY,
                ],
            ],
        ],
        'unsigned' => [
            'validations' => [
                Unsigned::class => [],
            ],
        ],
        'negative' => [
            'validations' => [
                Negative::class => [],
            ],
        ],
        'not_zero' => [
            'validations' => [
                NotZero::class => [],
            ],
        ],
        'not_blank' => [
            'validations' => [
                NotBlank::class => [],
            ],
        ]
    ],

];
