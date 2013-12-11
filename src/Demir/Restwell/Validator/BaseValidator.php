<?php

namespace Demir\Restwell\Validator;

use BadMethodCallException;
use Illuminate\Support\Facades\Validator;

abstract class BaseValidator
{
    protected $errors;

    public function _getErrors()
    {
        return $this->errors;
    }

    public function _isValid($attributes)
    {
        $v = Validator::make($attributes, static::$rules);

        if ($v->fails()) {
            $this->errors = $v->messages();
            return false;
        }
        
        return true;
    }

    public static function __callStatic($method, $args)
    {
        $allowedMethods = ['isValid', 'getErrors'];

        if (in_array($method, $allowedMethods)) {
            $validator = new static;

            switch ($method) {
                case 'isValid':
                    return $validator->_isValid($args[0]);
                case 'getErrors':
                    return $validator->_getErrors();
            }
        }

        throw new BadMethodCallException('Invalid method call from static context.');
    }
}
