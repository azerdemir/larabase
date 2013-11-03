<?php

namespace Demir\Restwell;

use Config;
use Controller;
use View;
use Notification;

class BaseController extends Controller
{
    /**
     * Error messages for same requests.
     *
     * @var array
     */
    protected $notifications;

    public function __construct()
    {
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
