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
                [Laramore\Validations\Required::class, Laramore\Validations\BaseValidation::MAX_PRIORITY],
            ],
        ],
        'not_nullable' => [
            'validation_classes' => [
                [Laramore\Validations\NotNullable::class, Laramore\Validations\BaseValidation::MAX_PRIORITY],
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
        'require_sign' => [
            'validation_classes' => [
                [Laramore\Validations\SignRequired::class, Laramore\Validations\BaseValidation::HIGH_PRIORITY],
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
        ],
        'restrict_domains' => [
            'validation_classes' => [
                Laramore\Validations\RestrictDomains::class,
            ],
        ],
        'accept_username' => [
            'validation_classes' => [
                Laramore\Validations\AcceptUsername::class,
            ],
        ],
    ],

];
