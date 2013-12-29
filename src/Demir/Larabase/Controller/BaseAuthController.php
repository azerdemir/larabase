<?php

namespace Demir\Larabase\Controller;

use Illuminate\Support\Facades\Config;
use Demir\Larabase\Repository\RepositoryInterface;

class BaseAuthController extends BaseController
{
    public function __construct(RepositoryInterface $repository)
    {
        $this->beforeFilter(Config::get('larabase::auth_filter'));

        parent::__construct($repository);
    }
}
