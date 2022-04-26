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
        Validations\Numeric::class,
    ],
    Fields\Boolean::class => [
        Validations\Boolean::class,
    ],
    Fields\Char::class => [
        Validations\Text::class,
        Validations\MinLength::class,
        Validations\MaxLength::class,
    ],
    Fields\DateTime::class => [
        Validations\DateTime::class,
    ],
    Fields\Decimal::class => [
        Validations\Numeric::class,
    ],
    Fields\Email::class => [
        Validations\MinLength::class,
        Validations\MaxLength::class,
        Validations\Pattern::class,
    ],
    Fields\Enum::class => [
        Validations\Exists::class,
    ],
    Fields\Hashed::class => [
        Validations\Text::class,
    ],
    Fields\Increment::class => [
        Validations\Numeric::class,
    ],
    Fields\Integer::class => [
        Validations\Numeric::class,
    ],
    Fields\Json::class => [
        Validations\Array::class,
    ],
    Fields\JsonList::class => [
        Validations\ArrayList::class,
    ],
    Fields\JsonObject::class => [
        Validations\ArrayObject::class,
    ],
    Fields\ManyToMany::class => [
        Validations\ObjectList::class,
    ],
    Fields\ManyToOne::class => [
        Validations\ArrayObject::class,
    ],
    Fields\OneToOne::class => [
        Validations\ArrayObject::class,
    ],
    Fields\Password::class => [
        Validations\Pattern::class,
    ],
    Fields\PrimaryId::class => [

    ],
    Fields\Reversed\BelongsToMany::class => [
        Validations\ObjectList::class,
    ],
    Fields\Reversed\HasMany::class => [
        Validations\ObjectList::class,
    ],
    Fields\Reversed\HasOne::class => [
        Validations\ArrayObject::class,
    ],
    Fields\UniqueId::class => [

    ],
    Fields\Text::class => [
        Validations\Text::class,
    ],
    Fields\Uri::class => [
        Validations\Text::class,
        Validations\MinLength::class,
        Validations\MaxLength::class,
        Validations\Pattern::class,
    ],

];
