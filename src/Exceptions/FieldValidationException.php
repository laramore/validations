<?php
/**
 * Laramore exception class.
 *
 * @author Samy Nastuzzi <samy@nastuzzi.fr>
 *
 * @copyright Copyright (c) 2019
 * @license MIT
 */

namespace Laramore\Exceptions;

use Laramore\Fields\BaseField;
use Laramore\Validations\ValidationErrorBag;

class FieldValidationException extends ValidationException
{
    protected $field;
    protected $errors;

    public function __construct(BaseField $field, ValidationErrorBag $errors, int $code=0, \Throwable $previous=null)
    {
        $this->field = $field;
        $this->errors = $errors;

        parent::__construct(implode(' ', $errors->all()), $code, $previous);
    }

    public function getField()
    {
        return $this->field;
    }

    public function getErrors(): ValidationErrorBag
    {
        return $this->errors;
    }
}
