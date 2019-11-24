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
            'validation_class' => Laramore\Validations\Required::class,
            'validation_priority' => Laramore\Validations\Validation::MAX_PRIORITY,
        ],
        'not_nullable' => [
            'validation_class' => Laramore\Validations\NotNullable::class,
            'validation_priority' => Laramore\Validations\Validation::MAX_PRIORITY,
        ],
        'unsigned' => [
            'validation_class' => Laramore\Validations\Unsigned::class,
        ],
        'negative' => [
            'validation_class' => Laramore\Validations\Negative::class,
        ],
        'require_sign' => [
            'validation_class' => Laramore\Validations\SignRequired::class,
            'validation_priority' => Laramore\Validations\Validation::HIGH_PRIORITY,
        ],
        'not_zero' => [
            'validation_class' => Laramore\Validations\NotZero::class,
        ],
        'not_blank' => [
            'validation_class' => Laramore\Validations\NotBlank::class,
        ],
        'restrict_domains' => [
            'validation_class' => Laramore\Validations\RestrictDomains::class,
        ],
        'accept_username' => [
            'validation_class' => Laramore\Validations\AcceptUsername::class,
        ],
    ],

];
