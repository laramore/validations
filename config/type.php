<?php

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
        'char' => [
            'validation_classes' => [
                Laramore\Validations\MinLength::class,
                Laramore\Validations\MaxLength::class,
            ],
        ],
        'email' => [
            'validation_classes' => [
                Laramore\Validations\MinLength::class,
                Laramore\Validations\MaxLength::class,
                Laramore\Validations\Pattern::class,
            ],
        ],
        'password' => [
            'validation_classes' => [
                Laramore\Validations\MinLength::class,
                Laramore\Validations\MaxLength::class,
                Laramore\Validations\Pattern::class,
            ],
        ],
    ],

];
