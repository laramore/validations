<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default validation manager
    |--------------------------------------------------------------------------
    |
    | This option defines the manager to handle validations.
    |
    */

    'manager' => Laramore\Validations\ValidationManager::class,

    /*
    |--------------------------------------------------------------------------
    | Name for the defined validation class in rules.
    |--------------------------------------------------------------------------
    |
    | This option defines the key name to use to resolve the validation class
    | specific to a rule.
    |
    */

    'field_property_name' => 'validation_classes',

    'rule_property_name' => 'validation_class',

    'rule_priority_name' => 'validation_priority',

    'default_priority' => Laramore\Validations\Validation::MEDIUM_PRIORITY,
];
