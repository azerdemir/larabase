<?php

namespace Demir\Larabase\Controller;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;
use Demir\Larabase\Repository\RepositoryInterface;

class BaseController extends Controller
{
    public function __construct()
    {
        if (!isset($this->layout)) {
            $masterLayout = Config::get('larabase::layouts.master');

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
