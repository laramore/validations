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

];
