<?php

namespace Demir\Restwell\Validator;

interface ValidatorInterface
{
    public function getErrors();

    public function isValid($attributes);
}
