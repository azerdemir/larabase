<?php

namespace Demir\Restwell;

use Illuminate\Support\Facades\Mail;

abstract class BaseMailer
{
    public function sendTo($user, $subject, $view, $data = [])
    {
        Mail::queue($view, $data, function($message) use($user, $subject)
        {
            $message->to($user['email'], $user['name'])
                    ->subject($subject);
        });
    }

    public static function __callStatic($method, $args)
    {
        return call_user_func_array(array(new static, $method . 'Mail'), $args);
    }
}
