<?php

namespace Laramore;

return [

    /*
    |--------------------------------------------------------------------------
    | Validation classes for fields
    |--------------------------------------------------------------------------
    |
    | This option defines the validation classes that are added for each fields.
    |
    */

    Fields\Binary::class => [
        Validations\Numeric::class => [],
    ],
    Fields\Boolean::class => [
        Validations\Boolean::class => [
            'priority' => Validations\Validation::TYPE_PRIORITY,
        ],
    ],
    Fields\Char::class => [
        Validations\Text::class => [
            'priority' => Validations\Validation::TYPE_PRIORITY,
        ],
        Validations\MinLength::class => [],
        Validations\MaxLength::class => [],
    ],
    Fields\DateTime::class => [
        Validations\DateTime::class => [
            'priority' => Validations\Validation::TYPE_PRIORITY,
        ],
    ],
    Fields\Decimal::class => [
        Validations\Numeric::class => [],
    ],
    Fields\Email::class => [
        Validations\MinLength::class => [],
        Validations\MaxLength::class => [],
        Validations\Pattern::class => [],
    ],
    Fields\Enum::class => [

    ],
    Fields\Increment::class => [
        Validations\Numeric::class => [],
    ],
    Fields\Integer::class => [
        Validations\Numeric::class => [],
    ],
    Fields\Json::class => [

    ],
    Fields\ManyToMany::class => [

    ],
    Fields\ManyToOne::class => [

    ],
    Fields\OneToOne::class => [

    ],
    Fields\Password::class => [
        Validations\Pattern::class => [],
    ],
    Fields\PrimaryId::class => [

    ],
    Fields\Reversed\BelongsToMany::class => [

    ],
    Fields\Reversed\HasMany::class => [

    ],
    Fields\Reversed\HasOne::class => [

    ],
    Fields\UniqueId::class => [

    ],
    Fields\Text::class => [
        Validations\Text::class => [
            'priority' => Validations\Validation::TYPE_PRIORITY,
        ],
    ],
    Fields\Uri::class => [
        Validations\Text::class => [
            'priority' => Validations\Validation::TYPE_PRIORITY,
        ],
        Validations\MinLength::class => [],
        Validations\MaxLength::class => [],
        Validations\Pattern::class => [],
    ],

];
