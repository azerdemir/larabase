<?php

namespace Demir\Restwell;

use Config;
use Controller;
use View;

class BaseController extends Controller
{
    public function __construct()
    {
        if (!isset($this->layout)) {
            $masterLayout = Config::get('restwell::app.layouts.master');

            if (!empty($masterLayout)) {
                $this->layout = $masterLayout;
            }
        }
    }

    /**
     * Setup the layout used by the controller.
     *
     * @return void
     */
    protected function setupLayout()
    {
        if (!is_null($this->layout)) {
            $this->layout = View::make($this->layout);
        }
    }
}
