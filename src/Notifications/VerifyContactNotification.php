<?php

namespace Persona\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerifyContactNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $code,
        public ?string $url = null,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->subject('Verify your contact')
            ->greeting('Hello!')
            ->line('Your verification code is: ' . $this->code . '.')
            ->line('This code expires shortly and should be treated as confidential.');

        if ($this->url !== null) {
            $message->action('Verify Now', $this->url);
        }

        return $message;
    }
}