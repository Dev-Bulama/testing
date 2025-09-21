<?php

namespace App\Notifications;

use App\Models\PhoneNumber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NumberExpiredNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected PhoneNumber $phoneNumber)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Phone number rental expired')
            ->line("Your rental for {$this->phoneNumber->number} has expired.")
            ->action('Renew now', url('/customer/numbers'));
    }
}
