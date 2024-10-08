<?php

namespace App\Notifications;

use AllowDynamicProperties;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;


#[AllowDynamicProperties] class VerifyEmail extends Notification
{
    public function __construct($verificationUrl)
    {
        $this->verificationUrl = $verificationUrl;
    }

    public function via($notifiable)
    {
        return ['emails'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Verify Email Address')
            ->view('emails.verify', ['user' => $notifiable, 'verificationUrl' => $this->verificationUrl]);
    }
}
