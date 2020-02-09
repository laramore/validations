<?php

$constraintPriority = Laramore\Validations\Validation::CONSTRAINT_PRIORITY;

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
            'validation_classes' => [
                [Laramore\Validations\Unique::class, $constraintPriority],
            ],
        ],
        'foreign' => [
            'validation_classes' => [
                [Laramore\Validations\Exists::class, $constraintPriority],
            ],
        ],
    ],

];
