<?php

$typePriority = (Laramore\Validations\Validation::MAX_PRIORITY + Laramore\Validations\Validation::HIGH_PRIORITY) / 2;

return [

    /*
    |--------------------------------------------------------------------------
    | Common proxies for fields with validations
    |--------------------------------------------------------------------------
    |
    | This option defines all proxies used for validations.
    |
    */

    'proxies' => [
        'common' => [
            'getValidations' => [
                'name_template' => 'get^{fieldname}Validations',
            ],
            'getErrors' => [
                'name_template' => 'get^{fieldname}Errors',
            ],
            'isValid' => [
                'name_template' => 'is^{fieldname}Valid',
            ],
            'check' => [],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Field validations
    |--------------------------------------------------------------------------
    |
    | This option defines if the fields use validations.
    |
    */

    'configurations' => [
        Laramore\Fields\Boolean::class => [
            'validation_classes' => [
                [Laramore\Validations\Type\Boolean::class, $typePriority],
            ],
        ],
        Laramore\Fields\Char::class => [
            'validation_classes' => [
                [Laramore\Validations\Type\Text::class, $typePriority],
            ],
        ],
        Laramore\Fields\Email::class => [
            'validation_classes' => [
                [Laramore\Validations\Type\Text::class, $typePriority],
            ],
        ],
        Laramore\Fields\Increment::class => [
            'validation_classes' => [
                [Laramore\Validations\Type\Number::class, $typePriority],
            ],
        ],
        Laramore\Fields\Integer::class => [
            'validation_classes' => [
                [Laramore\Validations\Type\Number::class, $typePriority],
            ],
        ],
        Laramore\Fields\Password::class => [
            'validation_classes' => [
                [Laramore\Validations\Type\Text::class, $typePriority],
            ],
        ],
        Laramore\Fields\PrimaryId::class => [
            'validation_classes' => [
                [Laramore\Validations\Type\Number::class, $typePriority],
            ],
        ],
        Laramore\Fields\Text::class => [
            'validation_classes' => [
                [Laramore\Validations\Type\Text::class, $typePriority],
            ],
        ],
        Laramore\Fields\Timestamp::class => [
            'validation_classes' => [
                [Laramore\Validations\Type\DataTime::class, $typePriority],
            ],
        ],
    ],

];
