<?php

namespace Demir\Restwell;

use Config;
use View;

class BaseAuthController extends BaseController
{
    public function __construct()
    {
        $this->layout = Config::get('restwell::app.layouts.master');

        $this->beforeFilter('auth');
    }
}
