<?php

namespace Demir\Restwell\Controller;

use Illuminate\Support\Facades\Config;
use Demir\Restwell\Repository\RepositoryInterface;

class BaseAuthController extends BaseController
{
    public function __construct(RepositoryInterface $repository)
    {
        $this->beforeFilter(Config::get('restwell::auth_filter'));

        parent::__construct($repository);
    }
}
