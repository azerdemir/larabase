<?php

namespace Demir\Larabase\Model;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\Validator;

abstract class BaseEloquentModel extends Eloquent implements ModelInterface
{
    protected $softDelete = true;

    protected $rules = array();

    protected $errors;

    protected $modelCaching = false;

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

    //TODO: Move validation to static:saving(...) event
    public function save(array $options = array())
    {
        if (!$this->validate()) {
            return false;
        }

        return parent::save($options);
    }
}
