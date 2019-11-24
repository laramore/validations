<?php

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
            'getValidationErrors' => [
                'name_template' => 'get^{fieldname}ValidationErrors',
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
        Laramore\Fields\BelongsToMany::class => [
            'with_validations' => false,
        ],
        Laramore\Fields\Boolean::class => [
            'with_validations' => true,
        ],
        Laramore\Fields\Char::class => [
            'with_validations' => true,
        ],
        Laramore\Fields\Email::class => [
            'with_validations' => true,
        ],
        Laramore\Fields\Enum::class => [
            'with_validations' => true,
        ],
        Laramore\Fields\Foreign::class => [
            'with_validations' => false,
        ],
        Laramore\Fields\HasMany::class => [
            'with_validations' => false,
        ],
        Laramore\Fields\HasManyThrough::class => [
            'with_validations' => false,
        ],
        Laramore\Fields\HasOne::class => [
            'with_validations' => false,
        ],
        Laramore\Fields\Increment::class => [
            'with_validations' => false,
        ],
        Laramore\Fields\Integer::class => [
            'with_validations' => true,
        ],
        Laramore\Fields\ManyToMany::class => [
            'with_validations' => false,
        ],
        Laramore\Fields\MorphToOne::class => [
            'with_validations' => false,
        ],
        Laramore\Fields\OneToOne::class => [
            'with_validations' => false,
        ],
        Laramore\Fields\Password::class => [
            'with_validations' => true,
        ],
        Laramore\Fields\PrimaryId::class => [
            'with_validations' => false,
        ],
        Laramore\Fields\Text::class => [
            'with_validations' => true,
        ],
        Laramore\Fields\Timestamp::class => [
            'with_validations' => true,
        ],
    ],

];
