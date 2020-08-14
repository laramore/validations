<?php

namespace Laramore\Validations;

return [

    /*
    |--------------------------------------------------------------------------
    | Common proxies for fields with validations
    |--------------------------------------------------------------------------
    |
    | This option defines all proxies used for validations.
    |
    */

    'configurations' => [
        'unique' => [
            'validations' => [
                Unique::class => [
                    'priority' => Validation::CONSTRAINT_PRIORITY,
                ],
            ],
        ],
        'foreign' => [
            'validations' => [
                Exists::class => [
                    'priority' => Validation::CONSTRAINT_PRIORITY,
                ],
            ],
        ],
    ],

];
