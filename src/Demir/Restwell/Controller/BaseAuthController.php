<?php

namespace Demir\Restwell\Controller;

use Demir\Restwell\Repository\RepositoryInterface;

class BaseAuthController extends BaseController
{
    public function __construct(RepositoryInterface $repository)
    {
        $this->beforeFilter('auth');

        parent::__construct($repository);
    }
}
