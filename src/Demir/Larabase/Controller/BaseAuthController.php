<?php

namespace Demir\Larabase\Controller;

use Illuminate\Support\Facades\Config;
use Demir\Larabase\Repository\RepositoryInterface;

class BaseAuthController extends BaseController
{
    public function __construct()
    {
        parent::__construct();

        $this->beforeFilter(Config::get('larabase::auth_filter'));
    }
}
