<?php

namespace Demir\Restwell\Controller;

use Illuminate\Support\Facades\Config;
use Illuminate\Routing\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Krucas\Notification\Facades\Notification;
use Demir\Restwell\Repository\RepositoryInterface;

class BaseController extends Controller
{
    /**
     * Repository object.
     *
     * @var Demir\Restwell\BaseRepository
     */
    protected $repository;

    /**
     * Error messages for same requests.
     *
     * @var array
     */
    protected $notifications;

    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;

        if (!isset($this->layout)) {
            $masterLayout = Config::get('restwell::layouts.master');

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

    /**
     * Get all notifications for controller.
     *
     * @return array
     */
    protected function notifications()
    {
        $flashedNotifications = Notification::all();
        Notification::clear();

        foreach ($flashedNotifications as $fNotification) {
            $type = $fNotification->getType();
            $this->notifications[$type][] = array(
                'type'      => $type,
                'placement' => 'title',
                'message'   => $fNotification->getMessage()
            );
        }

        return $this->notifications;
    }
}
