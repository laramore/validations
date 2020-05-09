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

    'configurations' => [
        'get_validations' => [
            'template' => [
                'name' => 'get$^{fieldname}Validations',
            ],
        ],
        'get_errors' => [
            'template' => [
                'name' => 'get$^{fieldname}Errors',
            ],
        ],
        'is_valid' => [
            'template' => [
                'name' => 'is$^{fieldname}Valid',
            ],
        ],
    ],

];
