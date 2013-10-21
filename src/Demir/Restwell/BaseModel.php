<?php

namespace Demir\Restwell;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Validation\Validator;

class BaseModel extends Eloquent
{
    protected $softDelete = true;

    protected $rules = array();

    protected $errors;

    public function validate()
    {
        $v = Validator::make($this->attributes, $this->rules);

        if ($v->fails()) {
            $this->errors = $v->messages();
            return false;
        }

        return true;
    }

    public function errors()
    {
        return $this->errors;
    }
}
