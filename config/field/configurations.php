<?php

$typePriority = Laramore\Validations\Validation::TYPE_PRIORITY;

return [

    /*
    |--------------------------------------------------------------------------
    | Field validations
    |--------------------------------------------------------------------------
    |
    | This option defines if the fields use validations.
    |
    */

    'boolean' => [
        'validation_classes' => [
            [Laramore\Validations\Boolean::class, $typePriority],
        ],
    ],
    'char' => [
        'validation_classes' => [
            [Laramore\Validations\Text::class, $typePriority],
        ],
    ],
    'email' => [
        'validation_classes' => [
            [Laramore\Validations\Text::class, $typePriority],
        ],
    ],
    'increment' => [
        'validation_classes' => [
            [Laramore\Validations\Numeric::class, $typePriority],
        ],
    ],
    'integer' => [
        'validation_classes' => [
            [Laramore\Validations\Numeric::class, $typePriority],
        ],
    ],
    'password' => [
        'validation_classes' => [
            [Laramore\Validations\Text::class, $typePriority],
        ],
    ],
    'primary_id' => [
        'validation_classes' => [
            [Laramore\Validations\Numeric::class, $typePriority],
        ],
    ],
    'text' => [
        'validation_classes' => [
            [Laramore\Validations\Text::class, $typePriority],
        ],
    ],
    'timestamp' => [
        'validation_classes' => [
            [Laramore\Validations\DataTime::class, $typePriority],
        ],
    ],

];
