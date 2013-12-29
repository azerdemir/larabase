<?php

namespace Demir\Larabase\Validator;

interface ValidatorInterface
{
    public function getErrors();

    public function isValid($attributes);
}
