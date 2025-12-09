<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;

class SendEmailService
{
    public function send($studentDetails, $greetingName, $userName, $randomPassword, $email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            \Log::error("Invalid email address: $email");
            return;
        }

        $emailContent = view('email.parentPasswordEmail', [
            'studentDetails' => $studentDetails,
            'userName' => $userName,
            'password' => $randomPassword,
            'greetingName' => $greetingName,
        ])->render();

        Mail::send([], [], function ($message) use ($emailContent, $email) {
            $message->to($email)
                ->subject('Your Account Details')
                ->html($emailContent);
        });
    }
}
