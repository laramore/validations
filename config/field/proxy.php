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
            'static' => true,
            'template' => [
                'name' => 'get$^{fieldname}Validations',
            ],
        ],
        'get_errors' => [
            'static' => true,
            'template' => [
                'name' => 'get$^{fieldname}Errors',
            ],
        ],
        'is_valid' => [
            'static' => true,
            'template' => [
                'name' => 'is$^{fieldname}Valid',
            ],
        ],
    ],

];
