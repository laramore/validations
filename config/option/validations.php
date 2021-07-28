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

    'append' => [

    ],
    'big_number' => [

    ],
    'fillable' => [

    ],
    'fixable' => [

    ],
    'negative' => [
        Negative::class => [],
    ],
    'need_lowercase' => [

    ],
    'need_number' => [

    ],
    'need_special' => [

    ],
    'need_uppercase' => [

    ],
    'not_blank' => [
        NotBlank::class => [],
    ],
    'not_nullable' => [
        NotNullable::class => [
            'priority' => Validation::MAX_PRIORITY,
        ],
    ],
    'not_zero' => [
        NotZero::class => [],
    ],
    'nullable' => [

    ],
    'require_sign' => [

    ],
    'required' => [
        Required::class => [
            'priority' => Validation::MAX_PRIORITY,
        ],
    ],
    'select' => [

    ],
    'small_number' => [

    ],
    'trim' => [

    ],
    'unsigned' => [
        Unsigned::class => [],
    ],
    'use_current' => [

    ],
    'visible' => [

    ],
    'with' => [

    ],
    'with_count' => [

    ],

];
