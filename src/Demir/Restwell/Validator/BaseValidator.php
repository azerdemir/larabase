<?php

namespace Demir\Restwell\Validator;

use Illuminate\Support\Facades\Validator;

abstract class BaseValidator
{
    protected $errors;

    public function getErrors()
    {
        return $this->errors;
    }

    public function isValidFor($attributes, $ruleset)
    {
        $v = Validator::make($attributes, static::$rules[$ruleset]);

        if ($v->fails()) {
            $this->errors = $v->messages();
            return false;
        }
        
        return true;
    }

    public function isValid($attributes)
    {
        return $this->isValidFor($attributes, 'default');
    }
}
