<?php

namespace Demir\Restwell\Model;

interface ModelInterface
{
    public function validate();

    public function errors();

    public function save(array $options = array());
}
