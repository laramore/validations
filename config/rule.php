<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default rules
    |--------------------------------------------------------------------------
    |
    | This option defines the default rules used in fields.
    |
    */

    'configurations' => [
        'required' => [
            'validation_classes' => [
                [Laramore\Validations\Required::class, Laramore\Validations\Validation::MAX_PRIORITY],
            ],
        ],
        'not_nullable' => [
            'validation_classes' => [
                [Laramore\Validations\NotNullable::class, Laramore\Validations\Validation::MAX_PRIORITY],
            ],
        ],
        'unsigned' => [
            'validation_classes' => [
                Laramore\Validations\Unsigned::class,
            ],
        ],
        'negative' => [
            'validation_classes' => [
                Laramore\Validations\Negative::class,
            ],
        ],
        'not_zero' => [
            'validation_classes' => [
                Laramore\Validations\NotZero::class,
            ],
        ],
        'not_blank' => [
            'validation_classes' => [
                Laramore\Validations\NotBlank::class,
            ],
        ]
    ],

];
