<?php

namespace Demir\Restwell;

use View;

class BaseAuthController extends BaseController
{
    public function __construct()
    {
        $this->beforeFilter('auth');

        parent::__construct();
    }
}
