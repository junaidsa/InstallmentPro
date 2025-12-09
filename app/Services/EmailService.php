<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;

class EmailService
{
    public static function sendCustomEmail($to, $token)
    {
        Mail::to($to)->send(new \App\Mail\MyCustomEmail($token));
    }
}
