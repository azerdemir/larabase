<?php

namespace Demir\Restwell;

use View;

class BaseAuthController extends BaseController
{
    protected $layout = 'layouts.master';

    public function __construct()
    {
        $this->beforeFilter('auth');
    }
}
