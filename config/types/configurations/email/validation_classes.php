<?php

/*
|--------------------------------------------------------------------------
| Default validation classes
|--------------------------------------------------------------------------
|
| This option defines the validation classes for this type.
|
*/

return [

    [Laramore\Validations\Text::class, Laramore\Validations\Validation::TYPE_PRIORITY],
    Laramore\Validations\MaxLength::class,
    Laramore\Validations\Pattern::class,

];
